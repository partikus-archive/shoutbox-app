<?php declare(strict_types=1);

namespace App\Infrastructure\Shoutbox;

use App\Application\Shoutbox\Message;
use App\Application\Shoutbox\MessageNormalizer as MessageNormalizerInterface;

class MessageNormalizer implements MessageNormalizerInterface
{
    public function normalize(Message $message): array
    {
        return [
            'id' => $message->getId()->toString(),
            'content' => $message->getContent(),
            'sent_at' => $message->getSentAt()->format(\DateTime::RFC3339),
            'user_agent' => $message->getUserData()->getUserAgent(),
            'user_ip' => $message->getUserData()->getIp(),
        ];
    }
}
