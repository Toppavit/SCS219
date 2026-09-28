<x-leave>
    <x-slot:title>ใบลาของฉัน - Leave Management</x-slot:title>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 fw-bold mb-0">วันลาคงเหลือปี {{ now()->year }}</h1>
        <a href="{{ route('leaves.create') }}" class="btn btn-success"><i class="fa-solid fa-plus me-1"></i>ยื่นใบลา</a>
    </div>

    @include('leaves._balances')

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h2 class="h5 fw-bold mb-0 text-success">ประวัติการลา</h2>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ประเภท</th>
                        <th>วันที่</th>
                        <th class="text-center">วัน</th>
                        <th>เหตุผล</th>
                        <th>สถานะ</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($leaves as $leave)
                        <tr>
                            <td>{{ $leave->typeLabel() }}</td>
                            <td class="text-nowrap">{{ $leave->start_date->format('d/m/Y') }} – {{ $leave->end_date->format('d/m/Y') }}</td>
                            <td class="text-center">{{ $leave->days }}</td>
                            <td>{{ $leave->reason ?: '-' }}</td>
                            <td>
                                <x-leave-status :status="$leave->status" />
                                @if ($leave->reviewer)
                                    <div class="small text-muted">โดย {{ $leave->reviewer->name }}{{ $leave->review_note ? ': '.$leave->review_note : '' }}</div>
                                @endif
                            </td>
                            <td class="text-end">
                                @if ($leave->status === 'pending')
                                    <form method="POST" action="{{ route('leaves.destroy', $leave) }}" onsubmit="return confirm('ยกเลิกใบลานี้?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">ยกเลิก</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">ยังไม่มีใบลา</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-leave>
