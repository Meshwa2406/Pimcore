<?php
declare(strict_types=1);

/**
 * Pimcore
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Commercial License (PCL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 *  @copyright  Copyright (c) Pimcore GmbH (http://www.pimcore.org)
 *  @license    http://www.pimcore.org/license     GPLv3 and PCL
 */

namespace Pimcore\Model\Document\Editable;

use Carbon\Carbon;
use DateTimeInterface;
use Pimcore\Model;

/**
 * @method \Pimcore\Model\Document\Editable\Dao getDao()
 */
class Date extends Model\Document\Editable implements EditmodeDataInterface
{
    /**
     * Contains the date
     *
     * @internal
     *
     */
    protected ?\Carbon\Carbon $date = null;

    public function getType(): string
    {
        return 'date';
    }

    public function getData(): mixed
    {
        return $this->date;
    }

    public function getDate(): ?\Carbon\Carbon
    {
        return $this->getData();
    }

    public function getDataEditmode(): ?int
    {
        if ($this->date) {
            return $this->date->getTimestamp();
        }

        return null;
    }

    public function frontend()
    {
        if ($this->date instanceof Carbon) {
            if (isset($this->config['outputIsoFormat']) && $this->config['outputIsoFormat']) {
                return $this->date->isoFormat($this->config['outputIsoFormat']);
            } elseif (isset($this->config['outputFormat']) && $this->config['outputFormat']) {
                trigger_deprecation(
                    'pimcore/pimcore',
                    '11.2',
                    'Using "outputFormat" config for %s editable is deprecated, use "outputIsoFormat" config instead. The format "%s" should be converted to ISO format.',
                    __CLASS__,
                    $this->config['outputFormat']
                );

                // Convert legacy format to ISO format and use isoFormat
                $isoFormat = $this->convertToIsoFormat($this->config['outputFormat']);
                return $this->date->isoFormat($isoFormat);
            } else {
                if (isset($this->config['format']) && $this->config['format']) {
                    $format = $this->config['format'];
                } else {
                    $format = DateTimeInterface::ATOM;
                }

                return $this->date->format($format);
            }
        }

        return '';
    }

    /**
     * Convert legacy format to ISO format
     * This is a basic conversion that handles common cases
     */
    private function convertToIsoFormat(string $format): string
    {
        $replacements = [
            '%A' => 'dddd',    // Full weekday name
            '%a' => 'ddd',     // Abbreviated weekday name
            '%B' => 'MMMM',    // Full month name
            '%b' => 'MMM',     // Abbreviated month name
            '%d' => 'DD',      // Day of the month, 2 digits
            '%e' => 'D',       // Day of the month
            '%F' => 'YYYY-MM-DD', // Full date
            '%H' => 'HH',      // Hour in 24h format
            '%I' => 'hh',      // Hour in 12h format
            '%M' => 'mm',      // Minutes
            '%m' => 'MM',      // Month as number
            '%p' => 'A',       // AM/PM
            '%R' => 'HH:mm',   // Time in 24h format
            '%S' => 'ss',      // Seconds
            '%T' => 'HH:mm:ss', // Time with seconds
            '%Y' => 'YYYY',    // Full year
            '%y' => 'YY',      // Year, 2 digits
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $format);
    }

    public function getDataForResource(): mixed
    {
        if ($this->date) {
            return $this->date->getTimestamp();
        }

        return null;
    }

    public function setDataFromResource(mixed $data): static
    {
        if ($data) {
            $this->setDateFromTimestamp((int)$data);
        }

        return $this;
    }

    public function setDataFromEditmode(mixed $data): static
    {
        if (strlen((string) $data) > 5) {
            $timestamp = strtotime($data);
            $this->setDateFromTimestamp($timestamp);
        }

        return $this;
    }

    public function isEmpty(): bool
    {
        if ($this->date) {
            return false;
        }

        return true;
    }

    private function setDateFromTimestamp(int $timestamp): void
    {
        $date = new Carbon();
        $this->date = $date->setTimestamp($timestamp);
    }
}
