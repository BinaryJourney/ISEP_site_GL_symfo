<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\House;
use App\Entity\User;
use App\Form\BookingFormType;
use App\Repository\BookingRepository;
use App\Repository\HouseRepository;
use App\Repository\TypeBookingStatusRepository;
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
                        HouseRepository $houseRepository, TypeBookingStatusRepository $statusRepository)
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
            /** @var House $house */
            $house = $houseRepository->findOneBy(array('id' => $id));
            $booking->setKeyHouseOwnerId($house->getKeyUser());
            $booking->setKeyHouse($house);
            $status = $statusRepository->findOneBy(array('status' => 'EN ATTENTE'));
            $booking->setStatus($status);

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
     * @Route("/booking/edit/{id}", name="app_booking_accept")
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

    /**
     * @Route("/booking/accept", name="app_booking_accept")
     */
    public function accept(Request $request, ManagerRegistry $doctrine, BookingRepository $bookingRepository)
    {
        try {
            $em = $doctrine->getManager();
            $id = $request->request->get('id');
            /** @var Booking $booking */
            $booking = $bookingRepository->findOneBy(array('id' => $id));

            //TODO

            return $this->json("ok", 200);
        } catch (\Exception $exception) {
            return $this->json($exception->getMessage(), 400);
        }

        dd($request->request);

        return $this->renderForm('booking/booking-form.html.twig', [
            'form' => $form,
        ]);
    }

}