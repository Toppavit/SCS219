<?php

namespace Tests\Feature;

use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class LeaveManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Wednesday, so a Mon–Fri week is always in the same year.
        Carbon::setTestNow('2026-09-30 09:00:00');
    }

    private function manager(): User
    {
        $user = User::factory()->create();
        $user->role = 'manager';
        $user->save();

        return $user;
    }

    private function requestLeave(User $user, array $overrides = [])
    {
        return $this->actingAs($user)->post('/leaves', $overrides + [
            'type' => 'personal',
            'start_date' => '2026-10-05', // Monday
            'end_date' => '2026-10-06',
            'reason' => 'ธุระส่วนตัว',
        ]);
    }

    public function test_guests_cannot_see_leaves(): void
    {
        $this->get('/leaves')->assertRedirect(route('login'));
    }

    public function test_request_form_shows_remaining_days(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/leaves/create')
            ->assertOk()
            ->assertSee('เหลือ 3 วัน');
    }

    public function test_employee_can_submit_leave_and_weekends_are_not_counted(): void
    {
        $user = User::factory()->create();

        // Friday to Monday = 2 working days
        $this->requestLeave($user, ['start_date' => '2026-10-02', 'end_date' => '2026-10-05'])
            ->assertRedirect(route('leaves.index'));

        $leave = LeaveRequest::sole();
        $this->assertSame(2, $leave->days);
        $this->assertSame('pending', $leave->status);

        $this->actingAs($user)->get('/leaves')->assertOk()->assertSee('รออนุมัติ');
    }

    public function test_request_is_rejected_when_balance_is_not_enough(): void
    {
        $user = User::factory()->create();

        // Personal quota is 3 days; Mon–Thu is 4.
        $this->requestLeave($user, ['start_date' => '2026-10-05', 'end_date' => '2026-10-08'])
            ->assertSessionHasErrors('type');

        $this->assertDatabaseCount('leave_requests', 0);
    }

    public function test_pending_days_count_against_the_balance(): void
    {
        $user = User::factory()->create();

        $this->requestLeave($user); // 2 pending
        $this->assertSame(1, $user->leaveBalances()['personal']['remaining']);

        $this->requestLeave($user, ['start_date' => '2026-10-07', 'end_date' => '2026-10-08'])
            ->assertSessionHasErrors('type');
    }

    public function test_weekend_only_request_is_rejected(): void
    {
        $this->requestLeave(User::factory()->create(), ['start_date' => '2026-10-03', 'end_date' => '2026-10-04'])
            ->assertSessionHasErrors('end_date');
    }

    public function test_employee_cannot_open_approvals(): void
    {
        $this->actingAs(User::factory()->create())->get('/leave-approvals')->assertForbidden();
    }

    public function test_manager_can_approve_and_remaining_days_update(): void
    {
        $employee = User::factory()->create();
        $manager = $this->manager();
        $this->requestLeave($employee);
        $leave = LeaveRequest::sole();

        $this->actingAs($manager)->get('/leave-approvals')->assertOk()->assertSee($employee->name);

        $this->actingAs($manager)
            ->patch("/leave-approvals/{$leave->id}", ['decision' => 'approved', 'review_note' => 'OK'])
            ->assertSessionHas('success');

        $leave->refresh();
        $this->assertSame('approved', $leave->status);
        $this->assertSame($manager->id, $leave->reviewed_by);

        $balance = $employee->leaveBalances()['personal'];
        $this->assertSame(2, $balance['used']);
        $this->assertSame(0, $balance['pending']);
        $this->assertSame(1, $balance['remaining']);
    }

    public function test_rejected_leave_gives_days_back(): void
    {
        $employee = User::factory()->create();
        $this->requestLeave($employee);
        $leave = LeaveRequest::sole();

        $this->actingAs($this->manager())->patch("/leave-approvals/{$leave->id}", ['decision' => 'rejected']);

        $this->assertSame('rejected', $leave->fresh()->status);
        $this->assertSame(3, $employee->leaveBalances()['personal']['remaining']);
    }

    public function test_reviewed_leave_cannot_be_reviewed_again(): void
    {
        $this->requestLeave(User::factory()->create());
        $leave = LeaveRequest::sole();
        $manager = $this->manager();

        $this->actingAs($manager)->patch("/leave-approvals/{$leave->id}", ['decision' => 'rejected']);
        $this->actingAs($manager)->patch("/leave-approvals/{$leave->id}", ['decision' => 'approved'])
            ->assertSessionHas('error');

        $this->assertSame('rejected', $leave->fresh()->status);
    }

    public function test_employee_can_cancel_only_own_pending_leave(): void
    {
        $owner = User::factory()->create();
        $this->requestLeave($owner);
        $leave = LeaveRequest::sole();

        $this->actingAs(User::factory()->create())->delete("/leaves/{$leave->id}")->assertForbidden();

        $this->actingAs($owner)->delete("/leaves/{$leave->id}")->assertRedirect(route('leaves.index'));
        $this->assertDatabaseCount('leave_requests', 0);
    }

    public function test_role_command_makes_user_manager(): void
    {
        $user = User::factory()->create();

        $this->artisan('leave:role', ['email' => $user->email])->assertSuccessful();

        $this->assertTrue($user->fresh()->isManager());
    }

    public function test_new_users_register_as_employees(): void
    {
        $this->post('/register', [
            'name' => 'Test',
            'email' => 'new@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'manager',
        ]);

        $this->assertFalse(User::where('email', 'new@example.com')->sole()->isManager());
    }
}
