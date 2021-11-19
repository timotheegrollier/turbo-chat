<?php

namespace App\Controller;

use App\Entity\Room;
use App\Entity\Message;
use App\Form\MessageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
    /**
     * @Route("/rooms/{room<[0-9]+>}/messages/list", name="app_messages_list",methods="GET|POST")
     */
    public function list(Room $room, Request $request, EntityManagerInterface $em): Response
    {
        $messages = $room->getMessages();

        $dataContent = [];
        $dataTime = [];

        foreach($messages as $key=>$message){
            $dataContent[$key] = $message->getContent();
            $dataTime[$key] = $message->getCreatedAt();
        }
      
   return $this->json(['messages_content'=>$dataContent,'messages_createdAt'=>$dataTime]);
    }
}