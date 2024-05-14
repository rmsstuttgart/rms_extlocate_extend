<?php

declare(strict_types=1);

namespace Rms\RmsExtlocateExtend\LocateFactProvider;

use Leuchtfeuer\Locate\FactProvider\AbstractFactProvider;
use Leuchtfeuer\Locate\Utility\LocateUtility;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

class RmsFactProvider extends AbstractFactProvider
{
    /**
     * @var string
     */
    final const PROVIDER_NAME = 'rmsfactprovider';

    private int $storage_pid = 0;

    /**
     * @var array<string, mixed>
     */
    protected array $extConfig;

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
        /** @var ExtensionConfiguration $extconfObj */
        $extconfObj = GeneralUtility::makeInstance(ExtensionConfiguration::class);
        $this->extConfig = $extconfObj->get('rms_extlocate_extend');

        /** @var ConfigurationManager $configurationManager */
        $configurationManager = GeneralUtility::makeInstance(ConfigurationManager::class);
        $typoscript = $configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT,
            'sitepackage'
        );
        $this->storage_pid = (int)$typoscript['plugin.']['rms_extlocate_extend.']['storagePid'];

        foreach ((array)GeneralUtility::getIndpEnv('_ARRAY') as $key => $value) {
            $this->facts[$this->getFactPropertyName((string)$key)] = $value;
        }

        $simulateIp = $this->configuration['settings']['simulateIp'] ?: null;
        if ($simulateIp) {
            $ip = $simulateIp;
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // if this script runs behind a reverse proxy, we do not have the real ip in REMOTE_ADDR
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            //$ip = GeneralUtility::getIndpEnv('REMOTE_ADDR');
            $ip = (string) $_SERVER['REMOTE_ADDR'];
        }

        //$location = $this->getGeolocation(self::API_KEY, $ip);
        $decodedLocation = $this->getGeolocation($this->extConfig['ipgeolocation_api_key'], $ip);

        $iso2 = isset($decodedLocation['country_code2']) ? $decodedLocation['country_code2'] : 'de';

        LocateUtility::mainstreamValue($iso2);
        $this->facts[$this->getBasename()] = $iso2;

        #\debug($this->getBasename());
        //\debug('rmsrmsrms - ' . ' - ' . $ip . ' - ' .   $iso2); die;

        return $this;
    }

    /**
     * @param string $prosecution
     * @return bool
     */
    public function isGuilty($prosecution): bool
    {
        //\debug($prosecution);
        $prosecution = (string)$prosecution;
        LocateUtility::mainstreamValue($prosecution);
        //\debug($this->facts[$this->getBasename()] === $prosecution);
        //\debug($prosecution);
        //die('RmsFactProvider:isGuilty');
        #$is_guilty = ($this->facts[$this->getBasename()] === $prosecution);

        return $this->facts[$this->getBasename()] === $prosecution;
    }

    /**
     * @param string $apiKey
     * @param string $ip
     * @param string $lang
     * @param string $fields
     * @param string $excludes
     * @return array<string, mixed>
     */
    private function getGeolocation(string $apiKey, string $ip, string $lang = "en", string $fields = "*", string $excludes = ""): array
    {
        $ip = \trim($ip);
        $dbutil = new DbUtility();
        $result = $dbutil->getCachedEntry($ip);
        //\debug($result); die;

        if (!\is_array($result)) {
            $agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';

            $url = "https://api.ipgeolocation.io/ipgeo?apiKey=" . $apiKey . "&ip=" . $ip . "&lang=" . $lang . "&fields=" . $fields . "&excludes=" . $excludes;
            $cURL = curl_init();

            curl_setopt($cURL, CURLOPT_URL, $url);
            curl_setopt($cURL, CURLOPT_HTTPGET, true);
            curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cURL, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Accept: application/json',
                'User-Agent: ' . $agent,
            ));

            $result_curl = curl_exec($cURL);
            $result = \json_decode((string) $result_curl, true);

            //\debug($result); die;
            if (\is_array($result) && isset($result['ip']) && isset($result['country_code2'])) {
                $dbutil->addCachedEntry($ip, $result, $this->storage_pid);
            } else {
                $result = [];
            }
        }

        return $result;
    }
}
