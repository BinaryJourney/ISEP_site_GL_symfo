<?php

namespace App\Controller;

use App\Entity\House;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile/infos", name="app_profile_infos")
     */
    public function myInfos() {

        /** @var User $user */
        $user = $this->getUser();

        return $this->render('profile/profile-mesinfos.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/profile/houses", name="app_profile_house")
     */
    public function myHouses() {

        /** @var User $user */
        $user = $this->getUser();

        return $this->render('profile/profile-myhouses.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/profile/bookings", name="app_profile_booking")
     */
    public function myBookings() {

        /** @var User $user */
        $user = $this->getUser();

        return $this->render('profile/profile-mybooking.html.twig', [
            'user' => $user
        ]);
    }
}