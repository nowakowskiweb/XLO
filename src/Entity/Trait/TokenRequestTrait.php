<?php

namespace App\Entity\Trait;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait TokenRequestTrait
{
    /**
     * @ORM\Column(type="string", length=20)
     */
    #[ORM\Column(type: Types::STRING, length: 20)]
    protected $selector;

    /**
     * @ORM\Column(type="string", length=100)
     */
    #[ORM\Column(type: Types::STRING, length: 100)]
    protected $hashedToken;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    protected $requestedAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    protected $expiresAt;

    protected function initialize(\DateTimeInterface $expiresAt, string $selector, string $hashedToken)
    {
        $this->requestedAt = new \DateTimeImmutable('now');
        $this->expiresAt = $expiresAt;
        $this->selector = $selector;
        $this->hashedToken = $hashedToken;
    }

    public function getRequestedAt(): \DateTimeInterface
    {
        return $this->requestedAt;
    }

    public function isExpired(): bool
    {
        return $this->expiresAt->getTimestamp() <= time();
    }

    public function getExpiresAt(): \DateTimeInterface
    {
        return $this->expiresAt;
    }

    public function getHashedToken(): string
    {
        return $this->hashedToken;
    }
}