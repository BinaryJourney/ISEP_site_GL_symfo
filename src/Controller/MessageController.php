<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\User;
use App\Form\MessageFormType;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    /**
     * @Route("/messages/{id}", name="app_messages")
     */
    public function messages(int $id, ManagerRegistry $doctrine, MessageRepository $messageRepository,
                             UserRepository $userRepository, Request $request): Response
    {
        /** @var User $connected_user */
        $connected_user = $this->getUser();
        $my_id = $connected_user->getId();

        /** @var User $other_user */
        $other_user = $userRepository->find($id);

        if($my_id == $id) {

            $this->addFlash('error', 'Vous ne pouvez pas vous envoyer des messages a vous même !');
            return $this->redirectToRoute('app_index');
        }

        $message = new Message();
        $form = $this->createForm(MessageFormType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Message $message */
            $message = $form->getData();
            $message->setKeySender($connected_user);
            $message->setKeyReceiver($other_user);
            $message->setTimestamp(new \DateTime());

            $em = $doctrine->getManager();
            $em->persist($message);
            $em->flush();

            unset($message);
            unset($form);
            $message = new Message();
            $form = $this->createForm(MessageFormType::class, $message);
        }

        $messages = $messageRepository->findAllMessagesBetween($my_id, $id);

        return $this->renderForm('message/messages.html.twig', [
            'messages' => $messages,
            'other_user' => $other_user,
            'form' => $form,
        ]);
    }
}