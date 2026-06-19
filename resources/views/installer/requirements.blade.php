@extends('installer.layout')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="h4 fw-bold mb-1">Server Requirements</h2>
            <p class="mb-0 text-muted small">Checking server environment compatibility.</p>
        </div>
        
        @if($isPassed)
            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill">
                <i class="fas fa-check-circle me-1"></i> Compatible
            </span>
        @else
            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 rounded-pill">
                <i class="fas fa-exclamation-triangle me-1"></i> Issues Found
            </span>
        @endif
    </div>

    <div class="card border-0 shadow-sm overflow-hidden rounded-3 mb-4">
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead class="bg-light">
                    <tr>
                        <th width="40%" class="ps-4">Requirement</th>
                        <th width="20%">Required</th>
                        <th width="20%">Current</th>
                        <th width="20%" class="text-end pe-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requirements as $req)
                        <tr class="{{ $req['required_status'] ? '' : 'bg-danger-subtle' }}">
                            <td class="ps-4">
                                <span class="fw-semibold text-dark">{{ $req['name'] }}</span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border border-secondary-subtle">
                                    {{ $req['required'] }}
                                </span>
                            </td>
                            <td class="font-monospace small">
                                {{ $req['current'] }}
                            </td>
                            <td class="text-end pe-4">
                                @if($req['required_status'])
                                    <i class="fas fa-check-circle text-success fs-5"></i>
                                @else
                                    <i class="fas fa-times-circle text-danger fs-5"></i>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if (!$isPassed)
        <div class="alert alert-danger d-flex align-items-start shadow-sm border-danger-subtle">
            <i class="fas fa-exclamation-circle fs-4 me-3 mt-1"></i>
            <div>
                <h6 class="fw-bold mb-1">Requirements Not Met</h6>
                <p class="mb-0 small">
                    Please update your server configuration (php.ini) to meet the requirements above. 
                    You may need to restart your web server after making changes.
                </p>
            </div>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center pt-3">
        <a href="{{ route('install.requirements') }}" class="btn btn-light border fw-semibold">
            <i class="fas fa-sync-alt me-2 text-muted"></i> Check Again
        </a>

        @if ($isPassed)
            <a href="{{ route('install.permissions') }}" class="btn btn-primary fw-bold px-4">
                Next: Permissions
                <i class="fas fa-arrow-right ms-2"></i>
            </a>
        @else
            <button disabled class="btn btn-secondary fw-bold px-4 opacity-75">
                Resolve Issues to Continue
            </button>
        @endif
    </div>
@endsection