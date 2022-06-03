<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\House;
use App\Entity\Message;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
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

        return $this->render('profile/profile-myinfos.html.twig', [
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

    /**
     * @Route("/profile/discussions", name="app_profile_discussions")
     */
    public function myDiscussions(ManagerRegistry $doctrine) {

        /** @var User $user */
        $user = $this->getUser();

        $em = $doctrine->getManager();

        $threads = $em->getRepository(Message::class)
            ->findAllThreadsOfUser($user->getId());

        //TODO ameliorer processus en 1) Permettant de get les user depuis le message 2) faisant du native SQL

        foreach ($threads as &$thread) {
            if($thread['key_receiver_id'] != $user->getId()) {
                $thread['other_user'] = $em->getRepository(User::class)->find($thread['key_receiver_id']);
            } else {
                $thread['other_user'] = $em->getRepository(User::class)->find($thread['key_sender_id']);
            }
        }
        unset($thread);

        return $this->render('profile/profile-mydiscussions.html.twig', [
            'user' => $user,
            'threads' => $threads,
        ]);
    }
}