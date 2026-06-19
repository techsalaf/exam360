<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Certificate of Completion</title>
    <style>
        /* 1. Page Reset */
        @page { margin: 0; size: a4 landscape; }
        body { margin: 0; padding: 0; font-family: 'Helvetica', serif; width: 100%; height: 100%; }

        /* Typography */
        .font-pinyon { font-family: 'Times New Roman', serif; font-style: italic; }
        .font-great-vibes { font-family: 'Zapfino', cursive; }
        .font-cinzel { font-family: 'Georgia', serif; text-transform: uppercase; letter-spacing: 2px; }
        .font-lato { font-family: 'Helvetica', sans-serif; }

        /* 3. Main Wrapper */
        .page-wrapper {
            position: fixed; top: 0; left: 0; bottom: 0; right: 0;
            padding: 40px; box-sizing: border-box; background: #fff;
        }

        /* 4. Borders */
        .cert-border {
            position: absolute; top: 40px; left: 40px; right: 40px; bottom: 40px;
            border: 5px double {{ $settings['cert_primary_color'] ?? '#111' }};
            padding: 10px; overflow: hidden; 
        }
        
        .cert-inner-border {
            width: 100%; height: 100%;
            border: 1px solid #ddd; position: relative;
        }

        /* 5. Background Image Layer */
        .bg-layer {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            z-index: 1; 
            opacity: 1; 
        }

        /* Watermark */
        .watermark { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 300px; opacity: 0.05; z-index: 5; }
        .watermark-text { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-45deg); font-size: 60px; font-weight: bold; color: #ccc; opacity: 0.2; z-index: 5; white-space: nowrap; text-transform: uppercase; }

        /* 6. Layout Table */
        .layout-table {
            width: 100%; height: 100%; border-collapse: collapse; position: relative; z-index: 15;
            table-layout: fixed;
        }
        
        .row-header { height: 15%; vertical-align: bottom; text-align: center; padding-bottom: 20px; }
        .row-body   { height: 50%; vertical-align: middle; text-align: center; padding: 0 60px; }
        .row-footer { height: 35%; vertical-align: middle; padding-bottom: 20px; }

        /* 7. Typography - CRITICAL COLOR FIXES */
        
        /* Default colors (for white background) */
        .cert-title {
            font-size: 52px; font-weight: bold; margin: 0;
            color: {{ $settings['cert_primary_color'] ?? '#111' }};
        }
        .cert-body {
            font-size: 24px; line-height: 1.6; color: #444;
        }

        /* OVERRIDE COLORS IF BACKGROUND IS PRESENT (The grey is dark) */
        @if(!empty($bg_image))
            .cert-title, .cert-body { color: #222; } /* Keep title dark */
            .cert-body { color: #444; } /* Keep body text slightly lighter grey */
            .date-text { color: #666; } /* Make date visible */
            .sig-line { border-color: #333; }
        @endif

        /* Variables - Need to remain dominant */
        .cert-body strong, .cert-body b, .zi-var-pill {
            font-family: 'Georgia', serif; font-size: 38px; color: #000;
            font-weight: bold; display: block; margin: 15px auto;
            border-bottom: 1px solid #ccc; width: 70%; padding-bottom: 5px;
            background: none !important; text-decoration: none !important;
        }

        /* 8. Footer Table */
        .footer-table { width: 90%; margin: 0 auto; table-layout: fixed; }
        .td-left { width: 35%; text-align: left; vertical-align: bottom; }
        .td-center { width: 30%; }
        .td-right { width: 35%; text-align: right; vertical-align: bottom; }

        .date-text { font-style: italic; font-size: 18px; margin-bottom: 5px; }
        
        .sig-block { display: block; float: right; width: 100%; max-width: 250px; text-align: center; }
        .sig-img { height: 50px; max-width: 200px; display: block; margin: 0 auto 2px auto; object-fit: contain; }
        .sig-line { border-top: 1px solid #333; margin-top: 5px; padding-top: 5px; font-weight: bold; font-size: 18px; }

    </style>
</head>
<body>

    <div class="page-wrapper">
        <div class="cert-border">
            <div class="cert-inner-border">
                
                <!-- Background Layer -->
                @if(!empty($bg_image))
                    <div class="bg-layer" style="z-index: 1; background-image: url('{{ $bg_image }}'); background-size: 100% 100%; opacity: 1;"></div>
                @else
                    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 1; background-image: radial-gradient(#eee 1px, transparent 1px); background-size: 20px 20px;"></div>
                    <!-- Watermark only if no background image is set -->
                    @if(!empty($logo_image))
                        <img src="{{ $logo_image }}" class="watermark">
                    @else
                        <div class="watermark-text">{{ $app_name }}</div>
                    @endif
                @endif

                <table class="layout-table">
                    <tr>
                        <td class="row-header">
                            <h1 class="cert-title {{ $settings['cert_font_family'] ?? 'font-pinyon' }}">
                                {{ $settings['cert_title'] ?? 'Certificate of Completion' }}
                            </h1>
                        </td>
                    </tr>

                    <tr>
                        <td class="row-body">
                            <div class="cert-body {{ $settings['cert_font_family'] ?? 'font-pinyon' }}">
                                {!! nl2br($parsed_body) !!}
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td class="row-footer">
                            <table class="footer-table {{ $settings['cert_font_family'] ?? 'font-pinyon' }}">
                                <tr>
                                    <!-- Date (Left) -->
                                    <td class="td-left">
                                        <p class="date-text">Date: {{ $result->created_at->format('F d, Y') }}</p>
                                        <div style="border-top: 1px solid #ddd; width: 150px; margin-top: 5px;"></div>
                                    </td>
                                    
                                    <!-- Spacer -->
                                    <td class="td-center"></td>
                                    
                                    <!-- Signature (Right) -->
                                    <td class="td-right">
                                        <div class="sig-block">
                                            @if($sig_image)
                                                <img src="{{ $sig_image }}" class="sig-img">
                                            @else
                                                <div style="height: 50px;"></div>
                                            @endif
                                            <div class="sig-line">
                                                {{ $settings['cert_signature'] ?? 'Director' }}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

            </div>
        </div>
    </div>

</body>
</html>