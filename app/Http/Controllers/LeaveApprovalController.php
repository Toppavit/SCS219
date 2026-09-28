<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;

class LeaveApprovalController extends Controller
{
    public function index()
    {
        $pending = LeaveRequest::with('user')->where('status', 'pending')->orderBy('start_date')->get();
        $reviewed = LeaveRequest::with(['user', 'reviewer'])->where('status', '!=', 'pending')->latest('reviewed_at')->limit(20)->get();

        return view('leaves.approvals', compact('pending', 'reviewed'));
    }

    public function update(Request $request, LeaveRequest $leave)
    {
        $validated = $request->validate([
            'decision' => 'required|in:approved,rejected',
            'review_note' => 'nullable|string|max:500',
        ]);

        if ($leave->status !== 'pending') {
            return back()->with('error', 'ใบลานี้ถูกพิจารณาไปแล้ว');
        }

        if ($validated['decision'] === 'approved') {
            // Re-check the balance, ignoring this request's own pending days.
            $remaining = $leave->user->leaveBalances($leave->id)[$leave->type]['remaining'];

            if ($leave->days > $remaining) {
                return back()->with('error', "อนุมัติไม่ได้: {$leave->user->name} มีวันลาเหลือ {$remaining} วัน");
            }
        }

        $leave->status = $validated['decision'];
        $leave->review_note = $validated['review_note'] ?? null;
        $leave->reviewed_by = $request->user()->id;
        $leave->reviewed_at = now();
        $leave->save();

        $label = $leave->status === 'approved' ? 'อนุมัติ' : 'ปฏิเสธ';

        return back()->with('success', "{$label}ใบลาของ {$leave->user->name} แล้ว");
    }
}
