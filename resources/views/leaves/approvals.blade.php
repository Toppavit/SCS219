<x-leave>
    <x-slot:title>อนุมัติใบลา - Leave Management</x-slot:title>

    <h1 class="h4 fw-bold mb-3">ใบลารออนุมัติ <span class="badge text-bg-warning">{{ $pending->count() }}</span></h1>

    <div class="card border-0 shadow-sm mb-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>พนักงาน</th>
                        <th>ประเภท</th>
                        <th>วันที่</th>
                        <th class="text-center">วัน</th>
                        <th>เหตุผล</th>
                        <th style="min-width: 280px">พิจารณา</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pending as $leave)
                        @php($remaining = $leave->user->leaveBalances($leave->id)[$leave->type]['remaining'] ?? 0)
                        <tr>
                            <td>{{ $leave->user->name }}</td>
                            <td>
                                {{ $leave->typeLabel() }}
                                <div class="small text-muted">เหลือ {{ $remaining }} วัน</div>
                            </td>
                            <td class="text-nowrap">{{ $leave->start_date->format('d/m/Y') }} – {{ $leave->end_date->format('d/m/Y') }}</td>
                            <td class="text-center">{{ $leave->days }}</td>
                            <td>{{ $leave->reason ?: '-' }}</td>
                            <td>
                                <form method="POST" action="{{ route('leaves.review', $leave) }}" class="d-flex gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <input type="text" name="review_note" class="form-control form-control-sm" placeholder="หมายเหตุ" aria-label="หมายเหตุสำหรับ {{ $leave->user->name }}">
                                    <button type="submit" name="decision" value="approved" class="btn btn-sm btn-success" @disabled($leave->days > $remaining)>อนุมัติ</button>
                                    <button type="submit" name="decision" value="rejected" class="btn btn-sm btn-outline-danger">ปฏิเสธ</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">ไม่มีใบลารออนุมัติ</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <h2 class="h5 fw-bold mb-3">พิจารณาล่าสุด</h2>
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>พนักงาน</th>
                        <th>ประเภท</th>
                        <th>วันที่</th>
                        <th class="text-center">วัน</th>
                        <th>สถานะ</th>
                        <th>ผู้พิจารณา</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reviewed as $leave)
                        <tr>
                            <td>{{ $leave->user->name }}</td>
                            <td>{{ $leave->typeLabel() }}</td>
                            <td class="text-nowrap">{{ $leave->start_date->format('d/m/Y') }} – {{ $leave->end_date->format('d/m/Y') }}</td>
                            <td class="text-center">{{ $leave->days }}</td>
                            <td><x-leave-status :status="$leave->status" /></td>
                            <td>{{ $leave->reviewer?->name ?? '-' }}{{ $leave->review_note ? ': '.$leave->review_note : '' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">ยังไม่มีรายการ</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-leave>
