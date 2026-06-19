<section class="py-5 bg-dark text-white">
    <div class="container py-lg-5">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 class="fw-bold mb-4" style="color: #fff !important">{{ get_trans($settings['cms_title'] ?? '') }}</h2>
                <p class="opacity-75 mb-5">{{ get_trans($settings['cms_desc'] ?? '') }}</p>
                <div class="row g-4">
                    @for($i=1; $i<=4; $i++)
                    <div class="col-6">
                        <h6 class="fw-bold mb-2 text-white"><i class="fa-solid fa-circle-check text-success me-2"></i> {{ get_trans($settings["cms_feat_{$i}_title"] ?? '') }}</h6>
                        <p class="small opacity-50">{{ get_trans($settings["cms_feat_{$i}_desc"] ?? '') }}</p>
                    </div>
                    @endfor
                </div>
            </div>
            <div class="col-lg-6 mt-5 mt-lg-0">
                 @if(!empty($rawSettings['cms_section_image']))
                    <img src="{{ asset('storage/' . $rawSettings['cms_section_image']) }}" class="img-fluid rounded-4 shadow-lg border border-secondary" alt="CMS">
                 @endif
            </div>
        </div>
    </div>
</section>