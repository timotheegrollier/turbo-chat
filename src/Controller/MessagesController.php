<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Room;
use App\Form\MessageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessagesController extends AbstractController
{
    /**
     * @Route("/rooms/{room<[0-9]+>}/messages/new", name="app_messages_new",methods="GET|POST")
     */
    public function new(Room $room, Request $request, EntityManagerInterface $em): Response
    {
        $message = new Message;
        $form = $this->createForm(MessageType::class, $message, ['action' => $this->generateUrl('app_messages_new', ["room" => $room->getId()])]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // dd($form->getData());
            $message->setRoom($room);
            $em->persist($message);
            $em->flush();
            return $this->redirectToRoute('app_rooms_show', ['id' => $room->getId()]);
        }
        return $this->renderForm(
            'messages/new.html.twig',
            ['room' => $room, 'form' => $form]
        );
    }
}