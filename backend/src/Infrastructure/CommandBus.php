<?php declare(strict_types=1);

namespace App\Infrastructure;

use App\Application\Command;
use App\Application\CommandBus as CommandBusInterface;
use App\Application\Shoutbox\RemoveOutdatedMessages;
use App\Application\Shoutbox\SendMessage;

class CommandBus implements CommandBusInterface
{
    private $commandHandlers = [];

    public function __construct(SendMessage $sendMessage, RemoveOutdatedMessages $removeOutdatedMessages)
    {
        $this->commandHandlers[SendMessage\Command::class] = $sendMessage;
        $this->commandHandlers[RemoveOutdatedMessages\Command::class] = $removeOutdatedMessages;
    }

    public function handle(Command $command)
    {
        $commandClass = get_class($command);
        if (isset($this->commandHandlers[$commandClass])) {
            $this->commandHandlers[$commandClass]->handle($command);
        }
    }
}
