<?php declare(strict_types=1);

namespace App\Infrastructure\Symfony;

use App\Infrastructure\Symfony\Command\ShoutboxCommand;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class Application extends BaseApplication
{
    /**
     * @var Container
     */
    private $container;

    public function __construct(string $name = 'shoutbox', string $version = 'dev')
    {
        parent::__construct($name, $version);
        $this->setUpDependencyInjection();
        $this->registerCommands();
    }

    private function setUpDependencyInjection(): void
    {
        $file = __DIR__ .'/../var/cache/container.php';

        if (file_exists($file) && $this->getVersion() === 'prod') {
            require_once $file;
            $this->container = new \ProjectServiceContainer();
            return;
        }

        $containerBuilder = new ContainerBuilder();
        $loader = new YamlFileLoader($containerBuilder, new FileLocator(__DIR__ . '/../../../config/'));
        $loader->load('services.yaml');
        $containerBuilder->compile();

        $this->container = $containerBuilder;

        if ($this->getVersion() === 'prod') {
            $dumper = new PhpDumper($containerBuilder);
            file_put_contents($file, $dumper->dump());
        }
    }

    private function registerCommands(): void
    {
        $commands = [
            ShoutboxCommand::class
        ];

        foreach ($commands as $command) {
            $this->add($this->container->get($command));
        }
    }
}
