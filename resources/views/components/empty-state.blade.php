@props([
    'title' => 'No records found',
    'subtitle' => 'Try adjusting your search filters or create a new record.',
    'icon' => 'fa-solid fa-folder-open', 
])

<div class="empty-state-container">
    
    <!-- Icon Circle -->
    <div class="empty-icon-box">
        <i class="{{ $icon }}"></i>
    </div>
    
    <!-- Text Content -->
    <h5 class="fw-bold text-dark mb-2">{{ $title }}</h5>
    <p class="empty-text">
        {{ $subtitle }}
    </p>

    <!-- Action Buttons Slot -->
    @if(!$slot->isEmpty())
        <div class="empty-actions">
            {{ $slot }}
        </div>
    @endif
    
</div>