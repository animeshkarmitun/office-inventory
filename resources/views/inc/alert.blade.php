@if(session()->has('message'))
<div class="alert {{ session('alert') }} alert-dismissible fade show" role="alert">
    <div class="d-flex align-items-start">
        <div class="flex-grow-1">
            <h6 class="alert-heading mb-2">
                @if(session('alert') == 'alert-success')
                    ✅ {{ session('message') }}
                @elseif(session('alert') == 'alert-danger')
                    ❌ {{ session('message') }}
                @elseif(session('alert') == 'alert-warning')
                    ⚠️ {{ session('message') }}
                @else
                    ℹ️ {{ session('message') }}
                @endif
            </h6>
            
            @if(session()->has('details') && session('alert') == 'alert-success')
                <div class="mt-2">
                    <small class="text-muted">
                        <strong>Purchase Details:</strong><br>
                        • Items Added: {{ session('details.items_count') ?? 0 }}<br>
                        • Total Value: ৳{{ session('details.total_value') ?? '0.00' }}<br>
                        • Department: {{ session('details.department') ?? 'Unknown' }}
                    </small>
                </div>
            @endif
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <div class="d-flex align-items-start">
        <div class="flex-grow-1">
            <h6 class="alert-heading mb-2">❌ Please fix the following errors:</h6>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>
@endif
