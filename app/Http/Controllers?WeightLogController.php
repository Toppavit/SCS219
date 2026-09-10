namespace App\Http\Controllers;

use App\Models\WeightLog;
use Illuminate\Http\Request;

class WeightLogController extends Controller
{
    public function index()
    {
        // ดึงข้อมูลทั้งหมดเรียงตามวันที่
        $logs = WeightLog::orderBy('recorded_at', 'asc')->get();

        // แปลงข้อมูลส่งให้ Google Charts [วันที่, น้ำหนัก]
        $chartData = [
            ['วันที่', 'น้ำหนัก (กก.)']
        ];

        foreach ($logs as $log) {
            $chartData[] = [$log->recorded_at->format('Y-m-d'), (float)$log->weight];
        }

        return view('weights.index', compact('logs', 'chartData'));
    }

    public function store(Request $request)
    {
        // Form Validation
        $validated = $request->validate([
            'weight' => 'required|numeric|min:20|max:300',
            'recorded_at' => 'required|date',
            'note' => 'nullable|string|max:255',
        ], [
            'weight.required' => 'กรุณากรอกน้ำหนัก',
            'weight.numeric' => 'น้ำหนักต้องเป็นตัวเลขเท่านั้น',
            'weight.min' => 'น้ำหนักต้องไม่น้อยกว่า 20 กก.',
            'weight.max' => 'น้ำหนักต้องไม่เกิน 300 กก.',
            'recorded_at.required' => 'กรุณาเลือกวันที่บันทึก',
            'recorded_at.date' => 'รูปแบบวันที่ไม่ถูกต้อง',
            'note.max' => 'โน้ตย่อต้องไม่เกิน 255 ตัวอักษร',
        ]);

        WeightLog::create($validated);

        return redirect()->route('weights.index')->with('success', 'บันทึกข้อมูลน้ำหนักเรียบร้อยแล้ว!');
    }

    public function update(Request $request, WeightLog $weight)
    {
        // Form Validation สำหรับแก้ไข
        $validated = $request->validate([
            'weight' => 'required|numeric|min:20|max:300',
            'recorded_at' => 'required|date',
            'note' => 'nullable|string|max:255',
        ], [
            'weight.required' => 'กรุณากรอกน้ำหนัก',
            'weight.numeric' => 'น้ำหนักต้องเป็นตัวเลขเท่านั้น',
            'recorded_at.required' => 'กรุณาเลือกวันที่บันทึก',
        ]);

        $weight->update($validated);

        return redirect()->route('weights.index')->with('success', 'อัปเดตข้อมูลเรียบร้อยแล้ว!');
    }

    public function destroy(WeightLog $weight)
    {
        $weight->delete();
        return redirect()->route('weights.index')->with('success', 'ลบข้อมูลเรียบร้อยแล้ว!');
    }
}
