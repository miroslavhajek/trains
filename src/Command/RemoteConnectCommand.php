<?php declare(strict_types=1);

namespace App\Command;

use App\Repository\RemoteHubRepository;
use App\Service\RemoteApi;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use function sprintf;

#[AsCommand(name: 'remote:connect', description: 'Connect Remote device --> HUB')]
class RemoteConnectCommand extends Command
{
    public function __construct(
        private readonly RemoteHubRepository $remoteHubRepository,
        private readonly RemoteApi $remoteApi,
    ) {
        parent::__construct();
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $hub = $this->remoteHubRepository->findHubSettings();
        if ($hub !== null) {
            $io->success(sprintf('Already connected as "%s"', $hub->getRemoteId()));

            return Command::SUCCESS;
        }

        $hub = $this->remoteApi->connect();

        $io->success(sprintf('Connected as "%s"', $hub->getRemoteId()));

        return Command::SUCCESS;
    }
}
