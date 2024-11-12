<?php declare(strict_types=1);

namespace App\Command;

use App\Repository\RemoteHubRepository;
use App\Repository\RemoteLocationRepository;
use App\Service\RemoteApi;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use function sleep;
use function sprintf;

#[AsCommand(name: 'remote:sync-locations', description: 'Sync Remote device <--> HUB')]
class RemoteSyncLocationsCommand extends Command
{
    public function __construct(
        private readonly RemoteHubRepository $remoteHubRepository,
        private readonly RemoteLocationRepository $remoteLocationRepository,
        private readonly RemoteApi $remoteApi,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->info('Sync started...');

        $hub = $this->remoteHubRepository->findHubSettings()
            ?? throw new LogicException('No HUB found');

        while (true) { // @phpstan-ignore-line
            $io->error('1');
            $locations = $this->remoteLocationRepository->findBy([], ['createdAt' => 'DESC'], 5);

            foreach ($locations as $location) {
                $this->remoteApi->syncLocation($hub, $location);

                $io->writeln(sprintf('%s, %s', $location->getLat(), $location->getLon()));
                $this->entityManager->remove($location);
            }

            $this->entityManager->flush();
            sleep(1);
        }

        return Command::SUCCESS; // @phpstan-ignore-line
    }
}
