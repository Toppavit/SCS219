@props(['status'])

@php
    [$class, $label] = match ($status) {
        'approved' => ['text-bg-success', 'อนุมัติ'],
        'rejected' => ['text-bg-danger', 'ปฏิเสธ'],
        default => ['text-bg-warning', 'รออนุมัติ'],
    };
@endphp

<span class="badge {{ $class }}">{{ $label }}</span>
