<?php

declare(strict_types=1);

namespace Rms\RmsExtlocateExtend\Domain\Repository;

use DateTimeImmutable;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * The repository for IpCache
 * @extends \TYPO3\CMS\Extbase\Persistence\Repository<\Rms\RmsExtlocateExtend\Domain\Model\IpCache>
 */
class IpCacheRepository extends Repository
{
    public function initializeObject(): void
    {
        //$querySettings = new Typo3QuerySettings();
        /** @var Typo3QuerySettings $querySettings */
        $querySettings = $this->createQuery()->getQuerySettings();

        // don't add the pid constraint
        $querySettings->setRespectStoragePage(false);
        // set the storagePids to respect
        //$querySettings->setStoragePageIds(array(1052));

        // define the enablecolumn fields to be ignored, true ignores all of them
        //$querySettings->setIgnoreEnableFields(TRUE);

        // define single fields to be ignored
        //$querySettings->setEnableFieldsToBeIgnored(array('disabled', 'starttime'));

        // add deleted rows to the result
        //$querySettings->setIncludeDeleted(TRUE);

        // don't add sys_language_uid constraint
        //$querySettings->setRespectSysLanguage(FALSE);

        $this->setDefaultQuerySettings($querySettings);
    }
}
