@props([
    'id',
    'title',
    'action' => null,
    'method' => 'POST',
    'isEdit' => false,
    'submitText' => null,
    'hasFile' => false
])

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title">{{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('common.close') }}"></button>
            </div>

            @if($action)
                <form action="{{ $action }}" method="POST"
                      @if($isEdit) id="{{ $id }}Form" @endif
                      @if($hasFile) enctype="multipart/form-data" @endif>
                    @csrf
                    @if($isEdit) @method('PUT') @endif
            @else
                <div>
            @endif

                <div class="modal-body">
                    {{ $slot }}
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                        {{ __('common.cancel') }}
                    </button>

                    @if($action)
                        <button type="submit" class="btn btn-premium">
                            {{ $submitText ?? __('common.save_changes') }}
                        </button>
                    @endif
                </div>

            @if($action)
                </form>
            @else
                </div>
            @endif
            
        </div>
    </div>
</div>
