<x-weight>
    <x-slot:title>ระบบติดตามน้ำหนัก - Weight Tracker</x-slot:title>

    <div class="row g-4">
        <!-- ฝั่งซ้าย: ฟอร์มเพิ่มข้อมูล -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0 fw-bold text-primary">
                        <i class="fa-solid fa-plus-circle me-2"></i>บันทึกน้ำหนักใหม่
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('weights.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="weight" class="form-label">น้ำหนัก (กิโลกรัม) <span class="text-danger">*</span></label>
                            <input type="number" step="0.1" class="form-control @error('weight') is-invalid @enderror" 
                                   id="weight" name="weight" value="{{ old('weight') }}" placeholder="เช่น 65.5">
                            @error('weight')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="recorded_at" class="form-label">วันที่บันทึก <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('recorded_at') is-invalid @enderror" 
                                   id="recorded_at" name="recorded_at" value="{{ old('recorded_at', date('Y-m-d')) }}">
                            @error('recorded_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="note" class="form-label">บันทึกเพิ่มเติม</label>
                            <textarea class="form-control @error('note') is-invalid @enderror" 
                                      id="note" name="note" rows="2" placeholder="เช่น ชั่งตอนเช้าก่อนทานอาหาร">{{ old('note') }}</textarea>
                            @error('note')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fa-solid fa-save me-1"></i> บันทึกข้อมูล
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- ฝั่งขวา: แสดงกราฟ Google Charts และ ตารางข้อมูล -->
        <div class="col-lg-8">
            <!-- กราฟ Google Charts -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0 fw-bold text-primary">
                        <i class="fa-solid fa-chart-line me-2"></i>แนวโน้มน้ำหนัก
                    </h5>
                </div>
                <div class="card-body">
                    <div id="curve_chart" style="width: 100%; height: 300px"></div>
                </div>
            </div>

            <!-- ตารางรายการข้อมูล -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0 fw-bold text-primary">
                        <i class="fa-solid fa-list me-2"></i>ประวัติการบันทึก
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>วันที่</th>
                                    <th>น้ำหนัก (กก.)</th>
                                    <th>บันทึกเพิ่มเติม</th>
                                    <th class="text-center">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                    <tr>
                                        <td>{{ $log->recorded_at->format('d/m/Y') }}</td>
                                        <td><span class="fw-bold text-dark">{{ number_format($log->weight, 1) }}</span> kg</td>
                                        <td class="text-muted">{{ $log->note ?? '-' }}</td>
                                        <td class="text-center">
                                            <!-- ปุ่มเปิด Modal แก้ไข -->
                                            <button class="btn btn-sm btn-outline-warning me-1" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editModal{{ $log->id }}">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>

                                            <!-- ปุ่มลบข้อมูล -->
                                            <form action="{{ route('weights.destroy', $log) }}" method="POST" class="d-inline"
                                                  onsubmit="return confirm('คุณต้องการลบข้อมูลรายการนี้ใช่หรือไม่?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>

                                    <!-- Modal แก้ไขข้อมูล -->
                                    <div class="modal fade" id="editModal{{ $log->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">แก้ไขข้อมูลน้ำหนัก</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('weights.update', $log) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="mb-3 text-start">
                                                            <label class="form-label">น้ำหนัก (กิโลกรัม)</label>
                                                            <input type="number" step="0.1" name="weight" class="form-control" 
                                                                   value="{{ old('weight', $log->weight) }}" required>
                                                        </div>
                                                        <div class="mb-3 text-start">
                                                            <label class="form-label">วันที่บันทึก</label>
                                                            <input type="date" name="recorded_at" class="form-control" 
                                                                   value="{{ old('recorded_at', $log->recorded_at->format('Y-m-d')) }}" required>
                                                        </div>
                                                        <div class="mb-3 text-start">
                                                            <label class="form-label">บันทึกเพิ่มเติม</label>
                                                            <textarea name="note" class="form-control" rows="2">{{ old('note', $log->note) }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                                                        <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">ยังไม่มีข้อมูลการบันทึกน้ำหนัก</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script สำหรับเรนเดอร์ Google Charts -->
    <x-slot:scripts>
        <script type="text/javascript">
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var data = google.visualization.arrayToDataTable(@json($chartData));

                var options = {
                    title: 'พัฒนาการของน้ำหนักตัว',
                    curveType: 'function',
                    legend: { position: 'bottom' },
                    colors: ['#0d6efd'],
                    pointSize: 6,
                    hAxis: { title: 'วันที่' },
                    vAxis: { title: 'น้ำหนัก (กก.)' }
                };

                var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
                chart.draw(data, options);
            }
        </script>
    </x-slot:scripts>
</x-weight>
