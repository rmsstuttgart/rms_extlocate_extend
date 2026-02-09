<?php

declare(strict_types=1);

namespace Rms\RmsExtlocateExtend\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

class CleanuIpCacheCommand extends AbstractCommand
{
    protected ConnectionPool $connectionPool;

    protected ConfigurationManager $configurationManager;

    protected PersistenceManager $persistenceManager;

    private SymfonyStyle $io;

    protected function configure(): void
    {
        parent::configure();
        $this->setHelp('Extlocate extend');
        $this->setDescription('Extlocate: Cleanup IP cache');
    }

    public function __construct(
        ConfigurationManager $configurationManager,
        ConnectionPool $connectionPool
    ) {
        $this->configurationManager = $configurationManager;
        $this->connectionPool = $connectionPool;

        /** @var ConfigurationManager $configurationManager */
        $configurationManager = GeneralUtility::makeInstance(ConfigurationManager::class);
        $typoscript = $configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT,
            'sitepackage'
        );

        parent::__construct();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $time_start = microtime(true);

        $this->io = new SymfonyStyle($input, $output);
        $this->io->title($this->getDescription());
        $this->io->writeln("\nStart Ip-Cache cleanup");

        $queryBuilder = $this->connectionPool->getQueryBuilderForTable('rms_extlocate_extend_domain_model_ip_cache');
        $affectedRows = $queryBuilder
            ->delete('rms_extlocate_extend_domain_model_ip_cache')
            ->where(
                $queryBuilder->expr()->lt('tstamp', 'UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 30 DAY))')
                //$queryBuilder->expr()->lt('tstamp', 'UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 3 MINUTE))')
            )
            ->executeStatement();

        $this->io->writeln("\nClean up ip cache cache - " . $affectedRows . " rows deleted \n");

        $time_elapsed_secs = \round(microtime(true) - $time_start, 2);
        $this->io->writeln('Done, total time: ' . $time_elapsed_secs . " seconds \n");

        return 0;
    }
}
