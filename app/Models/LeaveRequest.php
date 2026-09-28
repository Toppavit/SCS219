<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveRequest extends Model
{
    protected $fillable = ['type', 'start_date', 'end_date', 'days', 'reason'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'reviewed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Count working days (Mon–Fri) between two dates, inclusive.
     */
    public static function workingDays(CarbonInterface $start, CarbonInterface $end): int
    {
        $days = 0;

        for ($date = $start->copy()->startOfDay(); $date->lte($end); $date->addDay()) {
            if (! $date->isWeekend()) {
                $days++;
            }
        }

        return $days;
    }

    public function typeLabel(): string
    {
        return config("leave.types.{$this->type}.label", $this->type);
    }
}
