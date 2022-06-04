<?php

namespace App\Controller;

use App\Entity\House;
use App\Form\HouseFormType;
use App\Form\SearchHouseFormType;
use App\Repository\ListeVilleFranceRepository;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class HouseController extends AbstractController
{
    /**
     * @Route("/house/new", name="app_house_new")
     */
    public function new(Request $request, SluggerInterface $slugger, ManagerRegistry $doctrine)
    {
        /** @var House $house */
        $house = new House();
        $form = $this->createForm(HouseFormType::class, $house);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image_filename')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $house->setImageFilename($newFilename);
            } else {

                $this->addFlash('error', 'Veuillez uploader une image de votre maison !');

                return $this->renderForm('house/house-form.html.twig', [
                    'form' => $form,
                ]);
            }
            // ... persist the $product variable or any other work
            $house = $form->getData();
            $house->setKeyUser($this->getUser());

            $entityManager = $doctrine->getManager();
            $entityManager->persist($house);
            $entityManager->flush();

            $this->addFlash('success', 'Maison postée! Partager c\'est aimer!');
            return $this->redirectToRoute('app_index');

//            return $this->redirectToRoute('app_product_list');
        }

        return $this->renderForm('house/house-form.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/house/edit/{id}", name="app_house_edit")
     */
    public function edit(House $house, Request $request, SluggerInterface $slugger,
                         ManagerRegistry $doctrine)
    {
        $form = $this->createForm(HouseFormType::class, $house);

        $house->setImageFilename(
            new File($this->getParameter('images_directory').'/'.$house->getImageFilename())
        );
        $house->setKeyListeVilleFrance(ClassUtils::getRealClass($house->getKeyListeVilleFrance()));

        $oldBeginDate = $house->getDateBegin();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if($oldBeginDate != $form->get('date_begin')->getData())
            {
                $this->addFlash('error', 'Vous ne pouvez pas changer la date de début de proposition de votre maison !');
                return $this->renderForm('house/house-form.html.twig', [
                    'form' => $form,
                ]);
            }

            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image_filename')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $house->setImageFilename($newFilename);
            }

            // ... persist the $product variable or any other work
            $house = $form->getData();

            $entityManager = $doctrine->getManager();
            $entityManager->persist($house);
            $entityManager->flush();

            $this->addFlash('success', 'Maison postée! Partager c\'est aimer!');
            return $this->redirectToRoute('app_index');

//            return $this->redirectToRoute('app_product_list');
        }

        return $this->renderForm('house/house-form.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/house/view", name="app_house_view")
     */
    public function viewAll(ManagerRegistry $doctrine, Request $request) {

        $em = $doctrine->getManager();

        /** @var House $house */
        $house = new House();
        $form = $this->createForm(SearchHouseFormType::class, $house);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $capacity = $form->get('key_type_accommodation_capacity')->getData();
            $houses = $em->getRepository(House::class)
                ->findAllStillAvailableHousesWithAccommodation($capacity);
        } else {
            $houses = $em->getRepository(House::class)
                ->findAllStillAvailableHouses();
        }

        return $this->renderForm('house/house-view.html.twig', [
            'form' => $form,
            'houses' => $houses,
        ]);
    }

    /**
     * @Route("/house/view/{id}", name="app_house_detailed_view")
     */
    public function viewHouse(int $id, ManagerRegistry $doctrine) {

        $em = $doctrine->getManager();
        $house = $em->getRepository(House::class)
            ->findOneBy(array('id' => $id));

        return $this->render('house/house-detailed-view.html.twig', [
            'house' => $house
        ]);
    }

    /**
     * @Route("/house/listevilles", name="app_api_listevilles")
     */
    public function listeVilles(ListeVilleFranceRepository $listeVilleFranceRepository,
                                Request $request) {
        $listeVilles = $listeVilleFranceRepository->findAllMatching((array)$request->query->get('query'));

        return $this->json([
            'listeVilles' => $listeVilles
        ], 200, [], ['groups'=>['main']]);
    }

}