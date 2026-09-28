<x-leave>
    <x-slot:title>ยื่นใบลา - Leave Management</x-slot:title>

    @include('leaves._balances')

    <div class="card border-0 shadow-sm col-lg-7">
        <div class="card-header bg-white py-3">
            <h1 class="h5 fw-bold mb-0 text-success"><i class="fa-solid fa-file-pen me-2"></i>ยื่นใบลา</h1>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('leaves.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="type" class="form-label">ประเภทการลา <span class="text-danger">*</span></label>
                    <select id="type" name="type" class="form-select @error('type') is-invalid @enderror">
                        @foreach ($balances as $type => $balance)
                            <option value="{{ $type }}" @selected(old('type') === $type)>{{ $balance['label'] }} (เหลือ {{ $balance['remaining'] }} วัน)</option>
                        @endforeach
                    </select>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-sm-6">
                        <label for="start_date" class="form-label">วันเริ่มลา <span class="text-danger">*</span></label>
                        <input type="date" id="start_date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" class="form-control @error('start_date') is-invalid @enderror">
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-sm-6">
                        <label for="end_date" class="form-label">ถึงวันที่ <span class="text-danger">*</span></label>
                        <input type="date" id="end_date" name="end_date" value="{{ old('end_date', date('Y-m-d')) }}" class="form-control @error('end_date') is-invalid @enderror">
                        @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <p class="small text-muted">ระบบนับเฉพาะวันจันทร์–ศุกร์</p>

                <div class="mb-3">
                    <label for="reason" class="form-label">เหตุผล</label>
                    <textarea id="reason" name="reason" rows="3" class="form-control @error('reason') is-invalid @enderror">{{ old('reason') }}</textarea>
                    @error('reason')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-success"><i class="fa-solid fa-paper-plane me-1"></i>ส่งใบลา</button>
                <a href="{{ route('leaves.index') }}" class="btn btn-link">ยกเลิก</a>
            </form>
        </div>
    </div>
</x-leave>
