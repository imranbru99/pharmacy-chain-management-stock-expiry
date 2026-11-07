<?php

declare(strict_types=1);

namespace App\Support\Adapters;

use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use ImranDevBd\AttendanceHub\Contracts\DeviceDriverInterface;
use ImranDevBd\AttendanceHub\Facades\AttendanceHub;

final class AttendanceHubAdapter
{
    public function device(int|string $deviceId): DeviceDriverInterface
    {
        return AttendanceHub::device($deviceId);
    }

    public function sync(int|string $deviceId, ?CarbonInterface $since = null): Collection
    {
        return AttendanceHub::sync($deviceId, $since);
    }

    public function calculator(): mixed
    {
        return AttendanceHub::calculator();
    }
}
