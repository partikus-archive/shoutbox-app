<?php declare(strict_types=1);

namespace App\Infrastructure\Symfony\Ratchet;

use App\Application\CommandBus;
use App\Application\Shoutbox\Message;
use App\Application\Shoutbox\MessageNormalizer;
use App\Application\Shoutbox\MessageRepository;
use App\Application\Shoutbox\RemoveOutdatedMessages\Command as RemoveOutdatedMessagesCommand;
use App\Application\Shoutbox\SendMessage\Command as SendMessageCommand;
use GuzzleHttp\Psr7\Request;
use Psr\Container\ContainerInterface;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class Shoutbox implements MessageComponentInterface
{
    const NUMBER_OF_MESSAGES = 5;

    /**
     * @var \SplObjectStorage|ConnectionInterface[]
     */
    protected $clients;

    /**
     * @var CommandBus
     */
    private $commandBus;
    /**
     * @var MessageRepository
     */
    private $messageRepository;
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->clients = new \SplObjectStorage;
        $this->commandBus = $container->get(CommandBus::class);
        $this->messageRepository = $container->get(MessageRepository::class);
        $this->messageNormalizer = $container->get(MessageNormalizer::class);
        $this->container = $container;
    }

    /**
     * @inheritdoc
     */
    function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        $messages = $this->messageRepository->findRecentMessages(self::NUMBER_OF_MESSAGES);
        $normalizer = $this->container->get(MessageNormalizer::class);
        $data = json_encode(array_map(function (Message $message) use ($normalizer) {
            return $normalizer->normalize($message);
        }, $messages));
        $conn->send($data);
    }

    /**
     * @inheritdoc
     */
    function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    /**
     * @inheritdoc
     */
    function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }

    /**
     * @inheritdoc
     */
    function onMessage(ConnectionInterface $from, $msg)
    {
        /** @var Request $request */
        $request = $from->httpRequest;

        $sendMessageCommand = new SendMessageCommand(
            (string)$msg,
            new \DateTimeImmutable(),
            $request->getHeader('User-Agent')[0],
            $request->getHeader('X-Forwarded-For')[0]
        );

        $this->commandBus->handle($sendMessageCommand);

        $message = $this->messageRepository->get($sendMessageCommand->getId());
        $normalizer = $this->container->get(MessageNormalizer::class);
        $data = json_encode($normalizer->normalize($message));

        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n", $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        foreach ($this->clients as $client) {
            $client->send($data);
        }

        $this->commandBus->handle(new RemoveOutdatedMessagesCommand(self::NUMBER_OF_MESSAGES));
    }
}
