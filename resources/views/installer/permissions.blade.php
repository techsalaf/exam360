@extends('installer.layout')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="h4 fw-bold mb-1">Folder Permissions</h2>
            <p class="mb-0 text-muted small">Checking write access for system directories.</p>
        </div>

        @if($isPassed)
            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill">
                <i class="fas fa-check-circle me-1"></i> Writable
            </span>
        @else
            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 rounded-pill">
                <i class="fas fa-lock me-1"></i> Access Denied
            </span>
        @endif
    </div>

    <div class="card border-0 shadow-sm overflow-hidden rounded-3 mb-4">
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead class="bg-light">
                    <tr>
                        <th width="45%" class="ps-4">Directory</th>
                        <th width="20%">Required</th>
                        <th width="20%">Current</th>
                        <th width="15%" class="text-end pe-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($permissions as $perm)
                        @php $isPass = $perm['isWritable']; @endphp
                        <tr class="{{ $isPass ? '' : 'bg-danger-subtle' }}">
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-folder{{ $isPass ? '-open' : '' }} fs-5 me-2 {{ $isPass ? 'text-warning' : 'text-danger' }}"></i>
                                    <span class="fw-semibold text-dark">{{ $perm['folder'] }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border border-secondary-subtle font-monospace">
                                    0775
                                </span>
                            </td>
                            <td class="font-monospace small">
                                {{ $perm['currentPermission'] }}
                            </td>
                            <td class="text-end pe-4">
                                @if($isPass)
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
        <div class="alert alert-danger shadow-sm border-danger-subtle">
            <div class="d-flex mb-2">
                <i class="fas fa-exclamation-circle fs-4 me-3 mt-1"></i>
                <div>
                    <h6 class="fw-bold mb-1">Permission Errors Detected</h6>
                    <p class="small mb-2">The installer cannot write to the folders highlighted above. Please update them via your File Manager or FTP.</p>
                </div>
            </div>
            
            <div class="bg-white p-3 rounded border border-danger-subtle mt-2">
                <p class="small text-muted fw-bold mb-1 text-uppercase">SSH Command (VPS/Terminal):</p>
                <code class="d-block text-dark user-select-all small bg-light p-2 rounded">
                    chmod -R 775 storage bootstrap/cache
                </code>
            </div>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center pt-3">
        <a href="{{ route('install.permissions') }}" class="btn btn-light border fw-semibold">
            <i class="fas fa-sync-alt me-2 text-muted"></i> Check Again
        </a>

        @if ($isPassed)
            <a href="{{ route('install.database') }}" class="btn btn-primary fw-bold px-4">
                Next: Database Setup
                <i class="fas fa-arrow-right ms-2"></i>
            </a>
        @else
            <button disabled class="btn btn-secondary fw-bold px-4 opacity-75">
                Fix Permissions to Continue
            </button>
        @endif
    </div>
@endsection