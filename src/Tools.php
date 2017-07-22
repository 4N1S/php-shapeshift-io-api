<?php

declare(strict_types=1);

namespace Achse\ShapeShiftIo;

use LogicException;
use Nette\StaticClass;
use Nette\Utils\Strings;

class Tools
{

    use StaticClass;

    const LOWERCASE = 'lowercase';
    const UPPERCASE = 'uppercase';

    /**
     * @param string|null $coin1
     * @param string|null $coin2
     * @param string $mode
     * @return string
     */
    public static function buildPair(
        string $coin1 = null,
        string $coin2 = null,
        string $mode = self::UPPERCASE
    ): string {
        if (($coin1 === null || $coin2 === null) && $coin1 !== $coin2) {
            throw new LogicException('You must provide both or none of the coins.');
        }
        $pair = $coin1 !== null ? sprintf('%s_%s', $coin1, $coin2) : '';
        if ($mode === self::LOWERCASE) {
            $pair = Strings::lower($pair);
        }

        return $pair;
    }

    /**
     * @see http://stackoverflow.com/a/35009800
     *
     * @param string $inputJson
     * @return string
     */
    public static function jsonNumbersToString(string $inputJson): string
    {
        return Strings::replace($inputJson, "/(\"\w+\":\s*?)(\d+\.?[^,\}]*\b)/imu", '$1"$2"');
    }

}
