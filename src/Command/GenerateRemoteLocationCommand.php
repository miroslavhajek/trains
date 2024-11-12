<?php declare(strict_types=1);

namespace App\Command;

use App\Service\RemoteLocationGenerator;
use App\Util\Gps;
use InvalidArgumentException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use function is_string;
use function sleep;
use function sprintf;

#[AsCommand(name: 'remote:generate-remote-location', description: 'Generate random Remote Location')]
class GenerateRemoteLocationCommand extends Command
{
    public function __construct(private readonly RemoteLocationGenerator $remoteLocationGenerator)
    {
        parent::__construct();
    }


    protected function configure(): void
    {
        $this->addArgument('gps', InputArgument::REQUIRED, 'Start GPS location');
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $gps = $input->getArgument('gps');
        if (!is_string($gps)) {
            throw new InvalidArgumentException('GPS location must be a string');
        }

        $io = new SymfonyStyle($input, $output);
        $io->success(sprintf('Started on %s', $gps));

        [$latitude, $longitude] = Gps::fromString($gps);

        while (true) { // @phpstan-ignore-line
            $newLocation = $this->remoteLocationGenerator->generate($latitude, $longitude);
            $io->writeln(sprintf('%s, %s', $newLocation->getLat(), $newLocation->getLon()));

            sleep(1);
        }
    }
}
