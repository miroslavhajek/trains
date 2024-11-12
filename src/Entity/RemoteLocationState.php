<?php declare(strict_types=1);

namespace App\Entity;

enum RemoteLocationState: string
{
    case New = 'NEW';
    case SyncFailed = 'SYNC_FAILED';
}
