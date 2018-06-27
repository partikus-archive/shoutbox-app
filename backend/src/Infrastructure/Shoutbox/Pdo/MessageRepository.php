<?php declare(strict_types=1);

namespace App\Infrastructure\Shoutbox\Pdo;

use App\Application\Shoutbox\Message;
use App\Application\Shoutbox\MessageRepository as MessageRepositoryInterface;
use App\Application\Shoutbox\UserData;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class MessageRepository implements MessageRepositoryInterface
{
    /**
     * @var \PDO
     */
    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @param Message $message
     */
    public function add(Message $message): void
    {
        $stmt = $this->pdo->prepare("INSERT INTO messages (id, content, sent_at, user_agent, user_ip) VALUES (:id, :content, :sent_at, :user_agent, :user_ip)");

        $stmt->execute([
            ':id' => $message->getId()->toString(),
            ':content' => $message->getContent(),
            ':sent_at' => $message->getSentAt()->format('Y-m-d H:i:s'),
            ':user_agent' => $message->getUserData()->getUserAgent(),
            ':user_ip' => ip2long($message->getUserData()->getIp()),
        ]);
    }

    /**
     * @param UuidInterface $id
     * @return Message
     */
    public function get(UuidInterface $id): Message
    {
        $stmt = $this->pdo->prepare("SELECT * FROM messages WHERE id = :id LIMIT 1");

        $stmt->execute([
            ':id' => $id->toString(),
        ]);

        $row = $stmt->fetch();

        if ($row === false) {
            throw new \RuntimeException("Message does not exist");
        }

        return  $this->hydrateMessage($row);
    }

    /**
     * @param UuidInterface $id
     */
    public function remove(UuidInterface $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM messages WHERE id = :id");
        $stmt->bindValue(':id', $id->toString(), \PDO::PARAM_STR);

        $stmt->execute();
    }

    /**
     * @param int $limit
     * @return iterable|Message[]
     */
    public function findRecentMessages(int $limit): iterable
    {
        $stmt = $this->pdo->prepare("SELECT * FROM messages ORDER BY sent_at DESC LIMIT :limit");

        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();

        return array_map(function (array $row) {
            return  $this->hydrateMessage($row);
        }, $stmt->fetchAll());
    }

    /**
     * @param int $offset
     * @return iterable|Message[]
     */
    public function findOutdatedMessages(int $offset): iterable
    {
        $ids = implode(',', array_map(function (Message $message) {
            return "'" . $message->getId()->toString() . "'";
        }, $this->findRecentMessages($offset)));

        $stmt = $this->pdo->prepare(sprintf("SELECT * FROM messages WHERE id NOT IN(%s)", $ids));

        $stmt->execute();

        return array_map(function (array $row) {
            return  $this->hydrateMessage($row);
        }, $stmt->fetchAll());
    }

    private function hydrateMessage(array $row): Message
    {
        return new Message(
            Uuid::fromString($row['id']),
            $row['content'],
            new UserData(
                $row['user_agent'],
                long2ip((int) $row['user_ip'])
            ),
            new \DateTimeImmutable($row['sent_at'])
        );
    }
}
