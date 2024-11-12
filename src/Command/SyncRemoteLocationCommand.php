<?php declare(strict_types=1);

namespace App\Command;

use App\Repository\RemoteHubRepository;
use App\Service\RemoteApi;
use LogicException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:sync-remote-location', description: 'Sync Remote device <--> HUB')]
class SyncRemoteLocationCommand extends Command
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

        $hub = $this->remoteHubRepository->findOneBy([]);
        if ($hub === null) {
            throw new LogicException('No hub found');
        }

        $this->remoteApi->syncLocation($hub);

        return Command::SUCCESS;
    }
}
