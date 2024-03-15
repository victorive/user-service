<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;

class UserTest extends ApiTestCase
{
    public function testStoreUser(): void
    {
        $client = static::createClient();

        // Mock EntityManagerInterface
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf(User::class));
        $entityManager->expects($this->once())
            ->method('flush');

        // Mock MessageBusInterface
        $messageBus = $this->createMock(MessageBusInterface::class);
        $messageBus->expects($this->once())
            ->method('dispatch');

        // Replace the service in the container with the mock
        static::$container->set('doctrine.orm.entity_manager', $entityManager);
        static::$container->set('messenger.bus.default', $messageBus);

        // Prepare the request
        $client->request('POST', '/users', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => 'test@example.com',
            'firstName' => 'John',
            'lastName' => 'Doe',
        ]));

        // Assert the response
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertJsonContains(['message' => 'User created successfully']);
    }
}
