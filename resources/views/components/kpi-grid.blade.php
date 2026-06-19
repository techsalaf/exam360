@props(['stats' => [], 'cols' => 3])

@php
    $safeCols = max(1, (int) $cols);
    $colWidth = floor(12 / $safeCols);
@endphp

<div class="row g-4 mb-4">
    @foreach($stats as $stat)
        <div class="col-6 col-md-{{ $colWidth }}">
            <div class="zi-kpi-card {{ $stat['extra_class'] ?? '' }}" id="{{ $stat['id'] ?? '' }}">
                <div class="zi-kpi-content">
                    <h3>{{ str_pad($stat['value'] ?? 0, 2, '0', STR_PAD_LEFT) }}</h3>
                    <p>{{ $stat['label'] ?? '' }}</p>
                </div>
                <div class="zi-kpi-icon {{ $stat['color'] ?? 'neutral' }}">
                    <i class="{{ $stat['icon'] ?? 'fa-solid fa-chart-line' }}"></i>
                </div>
            </div>
        </div>
    @endforeach
</div>