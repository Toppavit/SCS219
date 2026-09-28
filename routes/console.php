<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('leave:role {email} {role=manager}', function (string $email, string $role) {
    if (! in_array($role, ['employee', 'manager'], true)) {
        $this->error('Role must be employee or manager.');
        return 1;
    }

    $user = User::where('email', $email)->first();

    if (! $user) {
        $this->error("No user with email {$email}.");
        return 1;
    }

    $user->role = $role;
    $user->save();

    $this->info("{$user->name} is now {$role}.");
})->purpose('Set a user as leave manager or employee');
