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
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class BookingController extends AbstractController
{

    /**
     * @Route("/booking/new/{id}", name="app_booking_new")
     * @throws Exception
     */
    public function new(int $id, Request $request, ManagerRegistry $doctrine,
                        HouseRepository $houseRepository, TypeBookingStatusRepository $statusRepository,
                        BookingRepository $bookingRepository)
    {
        $booking = new Booking();
        $form = $this->createForm(BookingFormType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Booking $booking */
            $booking = $form->getData();

            $datesToBook = $form->get('calendar')->getData();
            $beginDate = explode(' ', $datesToBook)[0];
            $endDate = explode(' ', $datesToBook)[2];
            $booking->setDateBegin(new \DateTime($beginDate));
            $booking->setDateEnd(new \DateTime($endDate));

            /** @var User $loggedInUser */
            $loggedInUser = $this->getUser();
            $booking->setKeyBookerUserId($loggedInUser);
            /** @var House $house */
            $house = $houseRepository->find($id);
            $booking->setKeyHouseOwnerId($house->getKeyUser());
            $booking->setKeyHouse($house);
            $status = $statusRepository->findOneBy(array('status' => 'EN ATTENTE'));
            $booking->setStatus($status);

            $entityManager = $doctrine->getManager();
            $entityManager->persist($booking);
            $entityManager->flush();

            $this->addFlash('success', 'Réservation postée!');
            return $this->redirectToRoute('app_index');
        }

        /** @var Booking[] $bookedDates */
        $bookedDates = $bookingRepository->findAllAcceptedBookingDates();

        return $this->renderForm('booking/booking-form.html.twig', [
            'form' => $form,
            'bookedDates' => $bookedDates,
        ]);
    }

    /**
     * @Route("/booking/accept", name="app_booking_accept")
     */
    public function accept(Request $request, ManagerRegistry $doctrine,
                           BookingRepository $bookingRepository, TypeBookingStatusRepository $statusRepository)
    {
        try {
            $em = $doctrine->getManager();
            $id = $request->request->get('id');
            /** @var Booking $booking */
            $booking = $bookingRepository->find($id);

            $statusAccepted = $statusRepository->findOneBy(array('status' => 'ACCEPTE'));
            $statusNonAccepted = $statusRepository->findOneBy(array('status' => 'NON-ACCEPTE'));
            $booking->setStatus($statusAccepted);

            $em->persist($booking);

            /** Drop all other IN WAITING booking with concurent dates */
            $oversteppingBookings = $bookingRepository->findAllOversteppingBookings($booking);
            /** @var Booking $oversteppingBooking */
            foreach ($oversteppingBookings as &$oversteppingBooking) {
                $oversteppingBooking->setStatus($statusNonAccepted);
                $em->persist($oversteppingBooking);
            }

            $em->flush();

            return $this->json("ok", 200);
        } catch (Exception $exception) {
            return $this->json($exception->getMessage(), 400);
        }
    }

    /**
     * @Route("/booking/refuse", name="app_booking_refuse")
     */
    public function refuse(Request $request, ManagerRegistry $doctrine,
                           BookingRepository $bookingRepository, TypeBookingStatusRepository $statusRepository)
    {
        try {
            $em = $doctrine->getManager();
            $id = $request->request->get('id');
            /** @var Booking $booking */
            $booking = $bookingRepository->find($id);
            $status = $statusRepository->findOneBy(array('status' => 'NON-ACCEPTE'));
            $booking->setStatus($status);

            $em->persist($booking);
            $em->flush();

            return $this->json("ok", 200);
        } catch (Exception $exception) {
            return $this->json($exception->getMessage(), 400);
        }
    }

    /**
     * @Route("/booking/cancel", name="app_booking_cancel")
     */
    public function cancel(Request $request, ManagerRegistry $doctrine,
                           BookingRepository $bookingRepository, TypeBookingStatusRepository $statusRepository)
    {
        try {
            $em = $doctrine->getManager();
            $id = $request->request->get('id');
            /** @var Booking $booking */
            $booking = $bookingRepository->find($id);
            $status = $statusRepository->findOneBy(array('status' => 'ANNULE'));
            $booking->setStatus($status);

            $em->persist($booking);
            $em->flush();

            return $this->json("ok", 200);
        } catch (Exception $exception) {
            return $this->json($exception->getMessage(), 400);
        }
    }

}