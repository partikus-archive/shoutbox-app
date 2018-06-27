<?php declare(strict_types=1);

namespace App\Application\Shoutbox;

use App\Application\Command;
use App\Application\CommandHandler;
use App\Application\Shoutbox\SendMessage\Command as SendMessageCommand;

class SendMessage implements CommandHandler
{
    /**
     * @var MessageRepository
     */
    private $messageRepository;

    public function __construct(MessageRepository $messageRepository)
    {
        $this->messageRepository = $messageRepository;
    }

    /**
     * @param SendMessageCommand $command
     */
    public function handle(Command $command): void
    {
        $this->messageRepository->add(new Message(
            $command->getId(),
            $command->getMessage(),
            new UserData(
                $command->getUserAgent(),
                $command->getIp()
            ),
            $command->getSentAt()
        ));
    }
}
