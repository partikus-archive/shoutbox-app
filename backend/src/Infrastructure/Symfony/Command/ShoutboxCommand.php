<?php declare(strict_types=1);

namespace App\Infrastructure\Symfony\Command;

use App\Infrastructure\Symfony\Ratchet\Shoutbox;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ShoutboxCommand extends Command
{
    /**
     * @var Shoutbox
     */
    private $shoutbox;

    public function __construct(?string $name = null, Shoutbox $shoutbox)
    {
        parent::__construct($name);
        $this->shoutbox = $shoutbox;
    }

    protected function configure()
    {
        $this->setName('app:shoutbox')
            ->setDescription('Run websockets server')
            ->addArgument('port', InputArgument::OPTIONAL, 'Websockets port', 8081)
            ->addArgument('address', InputArgument::OPTIONAL, 'Websockets listening address', '0.0.0.0');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $wsServer = new WsServer(
            $this->shoutbox
        );
        $httpServer = new HttpServer($wsServer);
        $server = IoServer::factory(
            $httpServer,
            $input->getArgument('port'),
            $input->getArgument('address')
        );

        $wsServer->enableKeepAlive($server->loop, 15);
        $server->run();
    }
}
