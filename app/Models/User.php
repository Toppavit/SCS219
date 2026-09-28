<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    /**
     * Leave balance per type for the current year:
     * quota, approved (used), pending, and remaining (quota - approved - pending).
     *
     * @return array<string, array{label: string, quota: int, used: int, pending: int, remaining: int}>
     */
    public function leaveBalances(?int $ignoreRequestId = null): array
    {
        $totals = $this->leaveRequests()
            ->whereYear('start_date', now()->year)
            ->whereIn('status', ['approved', 'pending'])
            ->when($ignoreRequestId, fn ($query) => $query->whereKeyNot($ignoreRequestId))
            ->selectRaw('type, status, SUM(days) as total')
            ->groupBy('type', 'status')
            ->get();

        $balances = [];

        foreach (config('leave.types') as $type => $info) {
            $used = (int) $totals->where('type', $type)->where('status', 'approved')->sum('total');
            $pending = (int) $totals->where('type', $type)->where('status', 'pending')->sum('total');

            $balances[$type] = [
                'label' => $info['label'],
                'quota' => $info['days'],
                'used' => $used,
                'pending' => $pending,
                'remaining' => max(0, $info['days'] - $used - $pending),
            ];
        }

        return $balances;
    }
}
