<?php

namespace App\Controller;

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

//    /**
//     * @Route("/profile/house", name="app_profile_house")
//     */
//    public function mesHouses() {

        //TODO profile/house

//        /** @var User $user */
//        $user = $this->getUser();
//
//        return $this->render('profile/profile-mesinfos.html.twig', [
//            'user' => $user
//        ]);
//    }
}