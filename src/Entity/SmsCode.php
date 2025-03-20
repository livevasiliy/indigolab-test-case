<?php

namespace App\Entity;

use App\Repository\SmsCodeRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SmsCodeRepository::class)
 */
class SmsCode
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $phone_number;

    /**
     * @ORM\Column(type="string", length=4)
     */
    private $code;

    /**
     * @ORM\Column(type="integer")
     */
    private $attempts = 0;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $last_sent_at;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $blocked_until;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $created_at;

    public function __construct()
    {
        $this->last_sent_at = new DateTimeImmutable();
        $this->created_at = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phone_number;
    }

    public function setPhoneNumber(string $phone_number): self
    {
        $this->phone_number = $phone_number;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getAttempts(): ?int
    {
        return $this->attempts;
    }

    public function incrementAttempts(): self
    {
        $this->attempts++;
        return $this;
    }

    public function getLastSentAt(): ?DateTimeImmutable
    {
        return $this->last_sent_at;
    }

    public function setLastSentAt(DateTimeImmutable $last_sent_at): self
    {
        $this->last_sent_at = $last_sent_at;

        return $this;
    }

    public function getBlockedUntil(): ?DateTimeImmutable
    {
        return $this->blocked_until;
    }

    public function setBlockedUntil(?DateTimeImmutable $blocked_until): self
    {
        $this->blocked_until = $blocked_until;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function isBlocked(): bool
    {
        return $this->blocked_until !== null && $this->blocked_until > new DateTimeImmutable();
    }

    public function canResend(): bool
    {
        $oneMinuteAgo = (new DateTimeImmutable())->modify('-1 minute');
        return !$this->isBlocked() && $this->last_sent_at <= $oneMinuteAgo;
    }

    public function setAttempts(int $attempts): SmsCode
    {
        $this->attempts = $attempts;
        return $this;
    }
}