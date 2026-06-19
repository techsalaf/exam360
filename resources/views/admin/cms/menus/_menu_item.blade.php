@php
    $labelVal = $item['label'] ?? '';
    $json = json_decode($labelVal, true);
    $displayLabel = (json_last_error() === JSON_ERROR_NONE && is_array($json)) ? ($json['en'] ?? '') : $labelVal;
@endphp

<div class="cms-menu-item">
    <div class="cms-drag-handle">
        <i class="fa-solid fa-grip-vertical"></i>
    </div>
    
    <div class="flex-grow-1 row g-2">
        <div class="col-5">
            <input type="text" 
                   name="items[{{ $index }}][label]" 
                   class="form-control-cms" 
                   value="{{ $displayLabel }}" 
                   placeholder="{{ __('cms.menu_label') }}" 
                   required>
        </div>
        <div class="col-7">
            <input type="text" 
                   name="items[{{ $index }}][url]" 
                   class="form-control-cms" 
                   value="{{ $item['url'] ?? '' }}" 
                   placeholder="{{ __('cms.menu_url') }}" 
                   required>
        </div>
    </div>
    
    {{-- Updated Button --}}
    <button type="button" class="btn-cms-danger-icon js-remove-menu-item">
        <i class="fa-solid fa-trash-can"></i>
    </button>
</div>