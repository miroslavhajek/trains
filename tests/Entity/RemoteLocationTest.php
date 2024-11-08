<?php declare(strict_types=1);

namespace App\Entity;

use PHPUnit\Framework\TestCase;

class RemoteLocationTest extends TestCase
{
    public function testConstruct(): void
    {
        $location = new RemoteLocation();

        self::assertSame(RemoteLocationState::New, $location->getState());
    }
}
