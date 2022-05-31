<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\User;
use App\Form\BookingFormType;
use App\Repository\HouseRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class BookingController extends AbstractController
{
    /**
     * @Route("/booking/new/{id}", name="app_booking_new")
     */
    public function new(int $id, Request $request, ManagerRegistry $doctrine,
                        HouseRepository $houseRepository)
    {
        $booking = new Booking();
        $form = $this->createForm(BookingFormType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Booking $booking */
            $booking = $form->getData();

            /** @var User $loggedInUser */
            $loggedInUser = $this->getUser();
            $booking->setKeyBookerUserId($loggedInUser);
            $booking->setKeyHouseOwnerId(
                $houseRepository->findOneBy(array('id' => $id))->getKeyUser()
            );

            $entityManager = $doctrine->getManager();
            $entityManager->persist($booking);
            $entityManager->flush();

            $this->addFlash('success', 'Réservation postée!');
            return $this->redirectToRoute('app_index');

//            return $this->redirectToRoute('app_product_list');
        }

        return $this->renderForm('booking/booking-form.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/booking/edit/{id}", name="app_booking_edit")
     */
    public function edit(Booking $booking, Request $request, ManagerRegistry $doctrine,
                        HouseRepository $houseRepository)
    {
        $form = $this->createForm(BookingFormType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Booking $booking */
            $booking = $form->getData();

            $entityManager = $doctrine->getManager();
            $entityManager->persist($booking);
            $entityManager->flush();

            $this->addFlash('success', 'Réservation postée!');
            return $this->redirectToRoute('app_index');

//            return $this->redirectToRoute('app_product_list');
        }

        return $this->renderForm('booking/booking-form.html.twig', [
            'form' => $form,
        ]);
    }


}