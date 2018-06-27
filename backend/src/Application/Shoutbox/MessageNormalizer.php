<?php declare(strict_types=1);

namespace App\Application\Shoutbox;

interface MessageNormalizer
{
    public function normalize(Message $message): array;
}
