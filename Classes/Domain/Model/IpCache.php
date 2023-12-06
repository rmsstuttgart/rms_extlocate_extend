<?php

declare(strict_types=1);

namespace Rms\RmsExtlocateExtend\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * This file is part of the "rms_extlocate" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2023 mk <mkettel@gmail.com>, rms. relationship marketing solutions GmbH
 */

class IpCache extends AbstractEntity
{
    protected string $hashValue = '';

    protected string $ip = '';

    protected string $countryName = '';

    protected string $jsonGeodata = '';

    public function getHashValue(): string
    {
        return $this->hashValue;
    }

    public function setHashValue(string $hashValue): void
    {
        $this->hashValue = $hashValue;
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function setIp(string $ip): void
    {
        $this->ip = $ip;
    }

    public function getCountryName(): string
    {
        return $this->countryName;
    }

    public function setCountryName(string $countryName): void
    {
        $this->countryName = $countryName;
    }

    public function getJsonGeodata(): string
    {
        return $this->jsonGeodata;
    }

    public function setJsonGeodata(string $jsonGeodata): void
    {
        $this->jsonGeodata = $jsonGeodata;
    }
}
