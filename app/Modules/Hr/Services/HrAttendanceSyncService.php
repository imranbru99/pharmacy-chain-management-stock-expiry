<?php

declare(strict_types=1);

namespace App\Modules\Hr\Services;

use App\Modules\Hr\Models\Employee;
use App\Support\Adapters\AttendanceHubAdapter;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;

final class HrAttendanceSyncService
{
    public function __construct(
        private readonly AttendanceHubAdapter $attendanceHub,
    ) {}

    public function syncDevice(int|string $deviceId, ?CarbonInterface $since = null): Collection
    {
        return $this->attendanceHub->sync($deviceId, $since);
    }

    /**
     * @return list<int>
     */
    public function employeesPendingDeviceMap(int $tenantId): array
    {
        return Employee::query()
            ->where('tenant_id', $tenantId)
            ->whereNull('attendance_device_user_id')
            ->pluck('id')
            ->all();
    }
}
