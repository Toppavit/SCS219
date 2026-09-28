<div class="row g-3 mb-4">
    @foreach ($balances as $balance)
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">{{ $balance['label'] }}</div>
                    <div class="display-6 fw-bold text-success">{{ $balance['remaining'] }}<span class="fs-6 text-muted"> / {{ $balance['quota'] }} วัน</span></div>
                    <div class="small text-muted">ใช้แล้ว {{ $balance['used'] }} วัน · รออนุมัติ {{ $balance['pending'] }} วัน</div>
                    <div class="progress mt-2" role="progressbar" aria-label="{{ $balance['label'] }} used" aria-valuenow="{{ $balance['used'] + $balance['pending'] }}" aria-valuemin="0" aria-valuemax="{{ $balance['quota'] }}" style="height: 6px">
                        <div class="progress-bar bg-success" style="width: {{ $balance['quota'] ? $balance['used'] / $balance['quota'] * 100 : 0 }}%"></div>
                        <div class="progress-bar bg-warning" style="width: {{ $balance['quota'] ? $balance['pending'] / $balance['quota'] * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
