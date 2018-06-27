<?php declare(strict_types=1);

namespace App\Infrastructure\Shoutbox\InMemory;

use App\Application\Shoutbox\Message;
use App\Application\Shoutbox\MessageRepository as MessageRepositoryInterface;
use Ramsey\Uuid\UuidInterface;

class MessageRepository implements MessageRepositoryInterface
{
    private $messages = [];

    /**
     * @inheritdoc
     */
    public function add(Message $message): void
    {
        $this->messages[$message->getId()->toString()] = $message;
    }

    /**
     * @inheritdoc
     */
    public function get(UuidInterface $id): Message
    {
        return $this->messages[$id->toString()];
    }

    /**
     * @inheritdoc
     */
    public function findRecentMessages(int $limit): iterable
    {
        return array_slice($this->getSortedMessages(), 0, $limit);
    }

    /**
     * @inheritdoc
     */
    public function findOutdatedMessages(int $offset): iterable
    {
        return array_slice($this->getSortedMessages(), $offset - 1);
    }

    /**
     * @inheritdoc
     */
    public function remove(UuidInterface $id): void
    {
        unset($this->messages[$id->toString()]);
    }

    /**
     * @return Message[]
     */
    private function getSortedMessages(): array
    {
        $messages = array_values($this->messages);

        usort( $messages, function (Message $prev, Message $next) {
            if ($prev === $next) {
                return 0;
            }

            return $prev > $next ? -1 : 1;
        });

        return $messages;
    }
}
