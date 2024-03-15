<?php

namespace App\Handler;

use App\Message\UserCreated;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UserCreatedHandler
{
    public function __construct(
        protected LoggerInterface $logger,
    )
    {
    }

    public function __invoke(UserCreated $userCreated): void
    {
        $email = $userCreated->getEmail();
        $firstName = $userCreated->getFirstName();
        $lastName = $userCreated->getLastName();

        $this->logger->warning('APP1: {STATUS_UPDATE} - ' . $email);
    }
}