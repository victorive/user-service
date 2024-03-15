<?php

namespace App\Controller;

use App\Entity\User;
use App\Message\UserCreated;
use Doctrine\ORM\EntityManagerInterface;
use JsonException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class UserController extends AbstractController
{
    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }
    /**
     * @throws JsonException
     */
    #[Route('/users', name: 'users', methods: 'POST')]
    public function store(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $user = new User();
        $user->setEmail($data['email']);
        $user->setFirstName($data['firstName']);
        $user->setLastName($data['lastName']);

        $entityManager->persist($user);
        $entityManager->flush();

        $this->messageBus->dispatch(new UserCreated($user->getEmail(), $user->getFirstName(), $user->getLastName()));

        return $this->json(['message' => 'User created successfully'], Response::HTTP_CREATED);
    }
}
