<?php declare(strict_types=1);

namespace App\Util;

use function json_decode;
use function json_encode;
use const JSON_THROW_ON_ERROR;
use const JSON_UNESCAPED_UNICODE;

class Json
{
    /**
     * @throws \JsonException
     */
    public static function encode(mixed $data, int $options = 0): string
    {
        $options |= JSON_THROW_ON_ERROR;
        $options |= JSON_UNESCAPED_UNICODE;

        // phpcs:ignore Generic.PHP.ForbiddenFunctions.FoundWithAlternative
        return (string) json_encode($data, $options);
    }


    /**
     * @throws \JsonException
     * @return array<string, mixed>
     */
    public static function decode(string $data): array
    {
        // phpcs:ignore Generic.PHP.ForbiddenFunctions.FoundWithAlternative
        return json_decode($data, true, 512, JSON_THROW_ON_ERROR); // @phpstan-ignore-line
    }
}
