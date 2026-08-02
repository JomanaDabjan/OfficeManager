@if (session('success'))
<div class="alert alert-success alert-dismissible fade show text-white shadow-sm rounded-pill px-4 mb-4 custom-auto-dismiss-alert"
    role="alert" style="background: linear-gradient(135deg, #2dce89 0%, #2d8ceb 100%);">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <i class="now-ui-icons ui-2_like mr-2" style="font-size: 16px;"></i>
            <span class="font-weight-bold">Success!</span> {{ session('success') }}
        </div>
        <button type="button" class="close text-white" data-dismiss="alert" aria-label="Close" style="opacity: 0.9;">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</div>
@endif

@if (session('error'))
<div class="alert alert-danger alert-dismissible fade show text-white shadow-sm rounded-pill px-4 mb-4 custom-auto-dismiss-alert"
    role="alert" style="background: linear-gradient(135deg, #f5365c 0%, #f56036 100%);">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <i class="now-ui-icons ui-1_simple-remove mr-2" style="font-size: 16px;"></i>
            <span class="font-weight-bold">Error!</span> {{ session('error') }}
        </div>
        <button type="button" class="close text-white" data-dismiss="alert" aria-label="Close" style="opacity: 0.9;">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</div>
@endif

@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show text-white shadow-sm rounded px-4 mb-4" role="alert"
    style="background: linear-gradient(135deg, #f5365c 0%, #f56036 100%);">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <i class="now-ui-icons objects_support-17 mr-2" style="font-size: 16px;"></i>
            <span class="font-weight-bold">Please fix the following errors:</span>
            <ul class="mb-0 mt-1 pl-3">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button type="button" class="close text-white" data-dismiss="alert" aria-label="Close" style="opacity: 0.9;">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</div>
@endif

