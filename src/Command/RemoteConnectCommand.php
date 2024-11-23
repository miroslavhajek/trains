<?php declare(strict_types=1);

namespace App\Command;

use App\Service\RemoteService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use function sprintf;

#[AsCommand(name: 'remote:connect', description: 'Connect Remote device --> HUB')]
class RemoteConnectCommand extends Command
{
    public function __construct(private readonly RemoteService $remoteService)
    {
        parent::__construct();
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $hub = $this->remoteService->initializeDevice();

        $io = new SymfonyStyle($input, $output);
        $io->success(sprintf('Connected as "%s: %s"', $hub->getRemoteName(), $hub->getRemoteId()));

        return Command::SUCCESS;
    }
}
