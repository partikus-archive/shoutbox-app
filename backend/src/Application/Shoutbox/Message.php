<?php declare(strict_types=1);

namespace App\Application\Shoutbox;

use Ramsey\Uuid\UuidInterface;

class Message
{
    /**
     * @var string
     */
    private $content;

    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var \DateTimeInterface
     */
    private $sentAt;

    /**
     * @var UserData
     */
    private $userData;

    public function __construct(UuidInterface $uuid, string $content, UserData $userData, \DateTimeInterface $sentAt = null)
    {
        $this->id = $uuid;
        $this->content = $content;
        $this->userData = $userData;
        $this->sentAt = $sentAt ?? new \DateTimeImmutable();
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getSentAt(): \DateTimeInterface
    {
        return $this->sentAt;
    }

    /**
     * @return UserData
     */
    public function getUserData(): UserData
    {
        return $this->userData;
    }
}
