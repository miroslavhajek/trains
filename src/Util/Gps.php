<?php declare(strict_types=1);

namespace App\Util;

use function explode;
use function trim;

class Gps
{
    /**
     * @return array{float, float}
     */
    public static function fromString(string $gps): array
    {
        [$lat, $lon] = explode(',', $gps);

        $lat = (float)trim($lat);
        $lon = (float)trim($lon);

        return [$lat, $lon];
    }
}
