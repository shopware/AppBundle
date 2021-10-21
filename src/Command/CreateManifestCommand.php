<?php declare(strict_types=1);

namespace Shopware\AppBundle\Command;

use DOMDocument;
use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;
use Shopware\AppBundle\ManifestGeneration\ManifestCreationService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateManifestCommand extends Command
{
    protected static $defaultName = 'manifest:create';

    protected static $defaultDescription = 'Command to create the manifest file.';

    public function __construct(
        private ManifestCreationService $manifestCreationService,
        private string $rootPath,
        private string $destinationPath
    ) {
        parent::__construct(self::$defaultName);
    }

    protected function configure(): void
    {
        $this
            ->setName(self::$defaultName)
            ->setDescription(self::$defaultDescription)
            ->addOption('secret', 's', InputOption::VALUE_NONE, 'Includes the secret in the manifest.xml.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $withSecret = $input->getOption('secret');
        $manifest = $this->manifestCreationService->generate($withSecret);

        $this->saveToFile($manifest);

        return Command::SUCCESS;
    }

    private function saveToFile(DOMDocument $document): void
    {
        if (empty($this->destinationPath)) {
            throw new \Exception('No destination path given.');
        }

        $adapter = new LocalFilesystemAdapter($this->rootPath);
        $filesystem = new Filesystem($adapter);

        $filesystem->write($this->destinationPath, $document->saveXML());
    }
}
