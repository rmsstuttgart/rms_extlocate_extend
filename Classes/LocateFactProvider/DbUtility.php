<?php

declare(strict_types=1);

namespace Rms\RmsExtlocateExtend\LocateFactProvider;

use DateTimeImmutable;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class DbUtility
{
    /**
     * get an array with lat long coordinates for a given address
     * from the local cache or false if not found
     *
     * @return array|false
     */
    public function getCachedEntry(string $ip)
    {
        $ip = \strtolower($ip);
        $ip = \trim($ip);

        $hash = md5($ip);

        /** @var ConnectionPool $pool */
        $pool = GeneralUtility::makeInstance(ConnectionPool::class);
        $queryBuilder = $pool->getQueryBuilderForTable('rms_extlocate_extend_domain_model_ip_cache');

        $result = $queryBuilder
            ->select('*')
            ->from('rms_extlocate_extend_domain_model_ip_cache')
            ->where(
                $queryBuilder->expr()->eq('hash_value', $queryBuilder->createNamedParameter($hash, \PDO::PARAM_STR))
            )
            ->executeQuery()
            ->fetchAssociative();

        if (is_array($result)) {
            // set last accessed time for this cache entry
            $pool->getConnectionForTable('rms_extlocate_extend_domain_model_ip_cache')
                ->update(
                    'rms_extlocate_extend_domain_model_ip_cache',
                    ['tstamp' => time()], // set
                    ['uid' => $result['uid']]  // where
                );

            return \json_decode((string) $result['json_geodata'], true);
        }

        return false;
    }

    /**
     * add a new entry to the local cache
     *
     * @param string $ip
     * @param array $json_data
     * @param int $pid
     * @return void
     */
    public function addCachedEntry(string $ip, array $json_data, int $pid): void
    {
        $ip = \strtolower($ip);
        $ip = \trim($ip);

        $hash = md5($ip);
        $json_string = (string)\json_encode($json_data);

        $date = new DateTimeImmutable();
        $tstamp = $date->getTimestamp();

        /** @var ConnectionPool $pool */
        $pool = GeneralUtility::makeInstance(ConnectionPool::class);
        $queryBuilder = $pool->getQueryBuilderForTable('rms_extlocate_extend_domain_model_ip_cache');

        $queryBuilder
            ->insert('rms_extlocate_extend_domain_model_ip_cache')
            ->values([
                'tstamp' => $tstamp,
                'crdate' => $tstamp,
                'pid' => $pid,
                'hash_value' => $hash,
                'ip' => $ip,
                'country_name' => $json_data['country_name'],
                'country_code' => $json_data['country_code2'],
                'json_geodata' => $json_string,
            ])
            ->executeStatement();
    }
}
