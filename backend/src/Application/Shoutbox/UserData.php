<?php declare(strict_types=1);

namespace App\Application\Shoutbox;

class UserData
{
    /**
     * @var string
     */
    private $userAgent;

    /**
     * @var string
     */
    private $ip;

    public function __construct(string $userAgent, string $ip)
    {
        $this->userAgent = $userAgent;
        $this->ip = $ip;
    }

    /**
     * @return string
     */
    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }
}
