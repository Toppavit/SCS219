<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class LeaveRequestController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $leaves = $user->leaveRequests()->with('reviewer')->latest()->get();
        $balances = $user->leaveBalances();

        return view('leaves.index', compact('leaves', 'balances'));
    }

    public function create(Request $request)
    {
        $balances = $request->user()->leaveBalances();

        return view('leaves.create', compact('balances'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', Rule::in(array_keys(config('leave.types')))],
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:500',
        ]);

        $start = Carbon::parse($validated['start_date']);
        $end = Carbon::parse($validated['end_date']);

        if ($start->year !== $end->year) {
            throw ValidationException::withMessages(['end_date' => 'วันลาต้องอยู่ในปีเดียวกัน']);
        }

        $days = LeaveRequest::workingDays($start, $end);

        if ($days === 0) {
            throw ValidationException::withMessages(['end_date' => 'ช่วงวันที่เลือกไม่มีวันทำงาน (จันทร์–ศุกร์)']);
        }

        $remaining = $request->user()->leaveBalances()[$validated['type']]['remaining'];

        if ($days > $remaining) {
            throw ValidationException::withMessages(['type' => "วันลาคงเหลือไม่พอ (ขอ {$days} วัน เหลือ {$remaining} วัน)"]);
        }

        $request->user()->leaveRequests()->create($validated + ['days' => $days]);

        return redirect()->route('leaves.index')->with('success', "ส่งใบลา {$days} วันเรียบร้อย รอหัวหน้าอนุมัติ");
    }

    public function destroy(Request $request, LeaveRequest $leave)
    {
        abort_unless($leave->user->is($request->user()), 403);

        if ($leave->status !== 'pending') {
            return back()->with('error', 'ยกเลิกได้เฉพาะใบลาที่ยังรออนุมัติ');
        }

        $leave->delete();

        return redirect()->route('leaves.index')->with('success', 'ยกเลิกใบลาแล้ว');
    }
}
