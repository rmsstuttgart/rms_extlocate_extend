<?php

declare(strict_types=1);

namespace Rms\RmsExtlocateExtend\LocateFactProvider;

use Leuchtfeuer\Locate\FactProvider\AbstractFactProvider;
use Leuchtfeuer\Locate\Utility\LocateUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

class RmsFactProvider extends AbstractFactProvider
{
    // https://app.ipgeolocation.io/
    const API_KEY = '38433d1b928341c285d3251b5bcb46a6';
    const PROVIDER_NAME = 'rmsfactprovider';
    private int $storage_pid = 0;

    /**
     * @inheritDoc
     */
    public function getBasename(): string
    {
        return self::PROVIDER_NAME;
    }

    /**
     * @inheritDoc
     */
    public function process(): self
    {
        /** @var ConfigurationManager $configurationManager */
        $configurationManager = GeneralUtility::makeInstance(ConfigurationManager::class);
        $typoscript = $configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT,
            'sitepackage'
        );
        $this->storage_pid = (int)$typoscript['plugin.']['rms_extlocate_extend.']['storagePid'];

        //foreach (GeneralUtility::getIndpEnv('_ARRAY') as $key => $value) {
        //\debug($key);
        //$this->facts[$this->getFactPropertyName($key)] = $value;
        //}

        $simulateIp = $this->configuration['settings']['simulateIp'] ?: null;
        if ($simulateIp) {
            $ip = $simulateIp;
        } else {
            $ip = (string)GeneralUtility::getIndpEnv('REMOTE_ADDR');
        }

        //$location = $this->getGeolocation(self::API_KEY, $ip);
        $decodedLocation = $this->getGeolocation(self::API_KEY, $ip);
        $iso2 = $decodedLocation['country_code2'];

        LocateUtility::mainstreamValue($iso2);
        $this->facts[$this->getBasename()] = $iso2;

        //\debug(GeneralUtility::getIndpEnv('_ARRAY'));
        //\debug('rmsrmsrms - ' .  $iso2);
        //die;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function isGuilty($prosecution): bool
    {
        $prosecution = (string)$prosecution;
        LocateUtility::mainstreamValue($prosecution);
        //\debug($prosecution);
        //die('xxx');
        return $this->facts[$this->getBasename()] === $prosecution;
    }

    private function getGeolocation(string $apiKey, string $ip, string $lang = "en", string $fields = "*", string $excludes = ""): array
    {
        $dbutil = new DbUtility();
        $result = $dbutil->getCachedEntry($ip);

        if (!\is_array($result)) {

            $url = "https://api.ipgeolocation.io/ipgeo?apiKey=" . $apiKey . "&ip=" . $ip . "&lang=" . $lang . "&fields=" . $fields . "&excludes=" . $excludes;
            $cURL = curl_init();

            curl_setopt($cURL, CURLOPT_URL, $url);
            curl_setopt($cURL, CURLOPT_HTTPGET, true);
            curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cURL, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Accept: application/json',
                'User-Agent: ' . $_SERVER['HTTP_USER_AGENT']
            ));

            $result_curl = curl_exec($cURL);
            $result = \json_decode((string) $result_curl, true);

            //\debug($coords);
            if (\is_array($result)) {
                $dbutil->addCachedEntry($ip, $result, $this->storage_pid);
            }
        }

        return $result;
    }
}
