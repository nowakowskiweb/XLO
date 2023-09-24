<?php
namespace App\Service;

use App\Entity\DeleteAccountRequest;
use App\Entity\User;
use App\Repository\DeleteAccountRequestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class DeleteAccountService {

    private $entityManager;
    private $tokenGenerator;
    private $deleteAccountRepository;

    public function __construct(EntityManagerInterface $entityManager, TokenGeneratorInterface $tokenGenerator, DeleteAccountRequestRepository $deleteAccountRepository) {
        $this->entityManager = $entityManager;
        $this->tokenGenerator = $tokenGenerator;
        $this->deleteAccountRepository = $deleteAccountRepository;
    }

    public function createDeleteAccountRequest($user) {
        $expiresAt = new \DateTimeImmutable('+1 hour');
        $selector = substr($this->tokenGenerator->generateToken(), 0, 20);
        $hashedToken = hash('sha256', $selector);

        $deleteAccountRequest = new DeleteAccountRequest($user, $expiresAt, $selector, $hashedToken);

        $this->entityManager->persist($deleteAccountRequest);
        $this->entityManager->flush();

        return $selector;
    }

    public function validateDeleteAccountToken(string $token): ?DeleteAccountRequest {
        return $this->deleteAccountRepository->findOneBy(['selector' => $token]);
    }

    public function removeAccount(User $user) {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
}
