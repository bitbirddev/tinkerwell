<?php

use App\Kernel;
use Symfony\Component\Dotenv\Dotenv;
use Tinkerwell\ContextMenu\Label;
use Tinkerwell\ContextMenu\OpenURL;

class SymfonyTinkerwellDriver extends TinkerwellDriver
{
    protected $kernel;

    public function canBootstrap($projectPath)
    {
        return file_exists($projectPath.'/public/index.php') &&
            file_exists($projectPath.'/symfony.lock') &&
            file_exists($projectPath.'/bin/console');
    }

    public function bootstrap($projectPath)
    {
        require_once $projectPath.'/vendor/autoload.php';

        (new Dotenv())->bootEnv($projectPath.'/.env');

        $this->kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
        $this->kernel->boot();
    }

    public function appVersion()
    {
        return 'Symfony '.Symfony\Component\HttpKernel\Kernel::VERSION;
    }

    public function getAvailableVariables()
    {
        return [
            'kernel' => $this->kernel,
            'container' => $this->kernel->getContainer(),
        ];
    }

    public function contextMenu()
    {
        $version = Symfony\Component\HttpKernel\Kernel::VERSION;

        return [
            Label::create('Detected Symfony v'.$version),
            OpenURL::create('Documentation', "https://symfony.com/doc/{$version}/setup.html"),
        ];
    }
}
