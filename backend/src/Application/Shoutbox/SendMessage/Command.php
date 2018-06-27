<?php declare(strict_types=1);

namespace App\Application\Shoutbox\SendMessage;

use App\Application\Command as CommandInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Command implements CommandInterface
{
    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var string
     */
    private $message;

    /**
     * @var \DateTimeInterface
     */
    private $sentAt;
    /**
     * @var string
     */
    private $userAgent;
    /**
     * @var string
     */
    private $ip;

    public function __construct(string $message, \DateTimeInterface $sentAt, string $userAgent, string $ip)
    {
        $this->id = Uuid::uuid4();
        $this->message = $message;
        $this->sentAt = $sentAt;
        $this->userAgent = $userAgent;
        $this->ip = $ip;
    }

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getSentAt(): \DateTimeInterface
    {
        return $this->sentAt;
    }

    /**
     * @return string
     */
    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }
}
