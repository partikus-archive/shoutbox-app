<?php declare(strict_types=1);

namespace App\Application\Shoutbox;

use Ramsey\Uuid\UuidInterface;

interface MessageRepository
{
    /**
     * @param Message $message
     */
    public function add(Message $message): void;

    /**
     * @param UuidInterface $id
     * @return Message
     */
    public function get(UuidInterface $id): Message;

    /**
     * @param UuidInterface $id
     */
    public function remove(UuidInterface $id): void;

    /**
     * @param int $limit
     * @return iterable|Message[]
     */
    public function findRecentMessages(int $limit): iterable;

    /**
     * @param int $offset
     * @return iterable|Message[]
     */
    public function findOutdatedMessages(int $offset): iterable;
}
