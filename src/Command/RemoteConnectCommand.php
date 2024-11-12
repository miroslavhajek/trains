<?php declare(strict_types=1);

namespace App\Command;

use App\Service\RemoteApi;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function sprintf;

#[AsCommand(name: 'remote:connect', description: 'Connect Remote device --> HUB')]
class RemoteConnectCommand extends Command
{
    public function __construct(private readonly RemoteApi $remoteApi)
    {
        parent::__construct();
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $hub = $this->remoteApi->connect();

        $output->writeln(sprintf('Connected as %s', $hub->getRemoteId()));
    }
}
