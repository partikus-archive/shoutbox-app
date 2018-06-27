<?php declare(strict_types=1);

namespace App\Application\Shoutbox\RemoveOutdatedMessages;

use App\Application\Command as CommandInterface;

class Command implements CommandInterface
{
    /**
     * @var int
     */
    private $noMessagesToKeep;

    public function __construct(int $noMessagesToKeep)
    {
        $this->noMessagesToKeep = $noMessagesToKeep;
    }

    /**
     * @return int
     */
    public function getNoMessagesToKeep(): int
    {
        return $this->noMessagesToKeep;
    }
}
