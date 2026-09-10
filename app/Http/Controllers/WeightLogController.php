<?php

namespace App\Http\Controllers;

use App\Models\WeightLog;
use Illuminate\Http\Request;

class WeightLogController extends Controller
{
    public function index()
    {
        $logs = WeightLog::orderBy('recorded_at', 'asc')->get();

        $chartData = [
            ['Date', 'Weight (kg)']
        ];

        foreach ($logs as $log) {
            $chartData[] = [$log->recorded_at->format('Y-m-d'), (float)$log->weight];
        }

        // Render ไปที่ view
        return view('index_2', compact('logs', 'chartData'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'weight' => 'required|numeric|min:20|max:300',
            'recorded_at' => 'required|date',
            'note' => 'nullable|string|max:255',
        ]);

        WeightLog::create($validated);

        return redirect()->route('weights.index')->with('success', 'บันทึกข้อมูลสำเร็จ!');
    }

    public function update(Request $request, WeightLog $weight)
    {
        $validated = $request->validate([
            'weight' => 'required|numeric|min:20|max:300',
            'recorded_at' => 'required|date',
            'note' => 'nullable|string|max:255',
        ]);

        $weight->update($validated);

        return redirect()->route('weights.index')->with('success', 'แก้ไขข้อมูลสำเร็จ!');
    }

    public function destroy(WeightLog $weight)
    {
        $weight->delete();
        return redirect()->route('weights.index')->with('success', 'ลบข้อมูลสำเร็จ!');
    }
}
