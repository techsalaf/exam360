@props(['headers' => [], 'empty' => false])

<div class="zi-table-container">
    
    <!-- Table Header -->
    <div class="zi-table-header">
        @foreach($headers as $header)
            <div class="zi-col-header {{ $header['classes'] ?? 'col-flex-1' }}">
                {{ $header['name'] }}
            </div>
        @endforeach
    </div>

    <!-- Table Body / Rows -->
    <div>
        {{ $slot }}
    </div>

    <!-- Empty State -->
    @if($empty)
        <div class="zi-table-empty">
            <div class="zi-table-empty-icon">
                <i class="fa-regular fa-folder-open"></i>
            </div>
            <h5 class="fw-bold">{{ __('common.empty_title') }}</h5>
            <p class="small">{{ __('common.empty_desc') }}</p>
        </div>
    @endif
</div>
