<?php declare(strict_types=1);

namespace App\Application\Shoutbox;

use App\Application\Command;
use App\Application\CommandHandler;
use App\Application\Shoutbox\RemoveOutdatedMessages\Command as RemoveOutdatedMessagesCommand;

class RemoveOutdatedMessages implements CommandHandler
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
     * @param RemoveOutdatedMessagesCommand $command
     */
    public function handle(Command $command): void
    {
        $messages = $this->messageRepository->findOutdatedMessages($command->getNoMessagesToKeep());

        foreach ($messages as $message) {
            $this->messageRepository->remove($message->getId());
        }
    }
}
