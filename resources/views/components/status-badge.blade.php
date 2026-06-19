@props(['status', 'text' => null])

@php
    // Normalize status
    $statusValue = is_string($status) ? strtolower($status) : $status;

    $isActive  = $statusValue === true || $statusValue === 1 || $statusValue === 'active' || $statusValue === 'enabled';
    $isPending = $statusValue === 'pending';

    // Default translated text
    if (!$text) {
        $text = $isActive
            ? __('common.status_enabled')
            : ($isPending
                ? __('common.status_pending')
                : __('common.status_disabled'));
    }

    // CSS Classes
    $classes = 'status-pill ';
    if ($isActive) {
        $classes .= 'active';
    } elseif ($isPending) {
        $classes .= 'pending';
    } else {
        $classes .= 'disabled';
    }
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    <span class="status-dot"></span> {{ $text }}
</span>
