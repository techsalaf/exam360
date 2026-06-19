@php
    // Fetch Tawk Settings
    $tawkSettings = \App\Models\SystemSetting::whereIn('key', [
        'ext_tawk_enable', 
        'ext_tawk_link'
    ])->pluck('value', 'key');

    $isTawkEnabled = ($tawkSettings['ext_tawk_enable'] ?? '0') === '1';
    $tawkLink = $tawkSettings['ext_tawk_link'] ?? '';
    
    // Extract Property ID and Widget ID from the link
    // Example Link: https://tawk.to/chat/PROPERTY_ID/WIDGET_ID
    $tawkId = '';
    $widgetId = '';
    
    if ($isTawkEnabled && !empty($tawkLink)) {
        // Remove 'https://tawk.to/chat/' to get the IDs
        $cleanLink = str_replace('https://tawk.to/chat/', '', $tawkLink);
        $parts = explode('/', $cleanLink);
        
        if (count($parts) >= 2) {
            $tawkId = $parts[0];
            $widgetId = $parts[1];
        }
    }
@endphp

@if($isTawkEnabled && !empty($tawkId) && !empty($widgetId))
    <script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    s1.src='https://embed.tawk.to/{{ $tawkId }}/{{ $widgetId }}';
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
    s0.parentNode.insertBefore(s1,s0);
    })();
    </script>
@endif