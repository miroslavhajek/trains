<?php declare(strict_types=1);

namespace App\Command;

use App\Entity\RemoteLocationState;
use App\Repository\RemoteHubRepository;
use App\Repository\RemoteLocationRepository;
use App\Service\RemoteService;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use RuntimeException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use function sprintf;
use function usleep;

#[AsCommand(name: 'remote:sync-locations', description: 'Sync Remote device <--> HUB')]
class RemoteSyncLocationsCommand extends Command
{
    public function __construct(
        private readonly RemoteHubRepository $remoteHubRepository,
        private readonly RemoteLocationRepository $remoteLocationRepository,
        private readonly RemoteService $remoteService,
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
            $locations = $this->remoteLocationRepository->findBy([], ['createdAt' => 'DESC'], 5);

            foreach ($locations as $location) {
                try {
                    $this->remoteService->sendLocation($hub, $location);

                    $io->success(sprintf('%s, %s', $location->getLat(), $location->getLon()));
                } catch (RuntimeException $e) {
                    $io->error(sprintf('%s, %s (%s)', $location->getLat(), $location->getLon(), $e->getMessage()));

                    $location->setState(RemoteLocationState::SyncFailed);
                }

                $this->entityManager->remove($location);
            }

            $this->entityManager->flush();
            usleep(500);
        }
    }
}
