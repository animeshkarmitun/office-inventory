@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="page-header mb-4">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <div class="icon-shape bg-primary-soft text-primary rounded-3 me-3">
                    <i class="fas fa-sliders-h fs-4"></i>
                </div>
                <div>
                    <h2 class="fw-bold mb-0">System Customization</h2>
                    <p class="text-muted mb-0">Personalize your application's appearance and branding</p>
                </div>
            </div>
            <div id="save-indicator" class="text-success text-sm fw-bold d-none">
                <i class="fas fa-check-circle me-1"></i> Changes pending save
            </div>
        </div>
    </div>

    @if(session('message'))
        <div class="alert {{ session('alert') }} alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
            <i class="fas {{ session('alert') === 'alert-success' ? 'fa-check-circle' : 'fa-exclamation-circle' }} me-2"></i>
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-xl-3 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 2rem; z-index: 100;">
                <div class="card-body p-3">
                    <div class="nav flex-column nav-pills custom-settings-nav" id="settingsTabs" role="tablist">
                        <button class="nav-link active" id="general-tab" data-bs-toggle="pill" data-bs-target="#general" type="button" role="tab">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-globe me-3"></i>
                                <span>General Info</span>
                            </div>
                        </button>
                        <button class="nav-link" id="branding-tab" data-bs-toggle="pill" data-bs-target="#branding" type="button" role="tab">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-copyright me-3"></i>
                                <span>Branding</span>
                            </div>
                        </button>
                        <button class="nav-link" id="styling-tab" data-bs-toggle="pill" data-bs-target="#styling" type="button" role="tab">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-paint-brush me-3"></i>
                                <span>UI Styling</span>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-9 col-lg-8">
            <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="settings-form">
                @csrf
                <div class="tab-content" id="settingsTabsContent">
                    <!-- General Settings -->
                    <div class="tab-pane fade show active" id="general" role="tabpanel">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                                <h5 class="fw-bold mb-1">General Settings</h5>
                                <p class="text-muted text-sm mb-0">Core application identification</p>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-4">
                                    @foreach($settings['general'] ?? [] as $setting)
                                        <div class="col-12">
                                            <label class="form-label text-sm fw-bold text-dark">{{ ucwords(str_replace('_', ' ', $setting->key)) }}</label>
                                            <input type="text" name="{{ $setting->key }}" class="form-control form-control-premium" value="{{ $setting->value }}">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Branding Settings -->
                    <div class="tab-pane fade" id="branding" role="tabpanel">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                                <h5 class="fw-bold mb-1">Branding & Assets</h5>
                                <p class="text-muted text-sm mb-0">Manage your logos and icons</p>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-4">
                                    @foreach($settings['branding'] ?? [] as $setting)
                                        <div class="col-md-6">
                                            <div class="p-3 border rounded-4 bg-light-soft">
                                                <label class="form-label text-sm fw-bold text-dark d-block mb-3">{{ ucwords(str_replace('_', ' ', $setting->key)) }}</label>
                                                <div class="d-flex align-items-center gap-3">
                                                    @if($setting->value)
                                                        <div class="preview-square bg-white shadow-sm rounded-3">
                                                            <img src="{{ Storage::url($setting->value) }}" alt="Preview">
                                                        </div>
                                                    @endif
                                                    <div class="flex-grow-1">
                                                        <input type="file" name="{{ $setting->key }}" class="form-control text-sm" accept="image/*">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- UI Custom Styling -->
                    <div class="tab-pane fade" id="styling" role="tabpanel">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                            <div class="card-header bg-transparent border-0 pt-4 px-4 pb-3">
                                <h5 class="fw-bold mb-1">UI Custom Styling</h5>
                                <p class="text-muted text-sm mb-0">Fine-tune the visual elements of the interface</p>
                            </div>
                            <div class="card-body p-0">
                                <!-- Groups logic in view -->
                                @php
                                    $stylingGroups = [
                                        'Global' => ['app_bg_color', 'link_color', 'link_hover_color', 'body_text_color'],
                                        'Sidebar' => ['sidebar_bg_color', 'sidebar_text_color', 'sidebar_text_size'],
                                        'Tables' => ['table_header_bg', 'table_header_text_color', 'table_text_size'],
                                        'Buttons' => ['btn_primary_bg', 'btn_primary_color'],
                                        'Headers' => ['header_text_color', 'header_text_size'],
                                    ];
                                    $stylingSettings = collect($settings['styling'] ?? [])->keyBy('key');
                                @endphp

                                @foreach($stylingGroups as $groupName => $keys)
                                    <div class="styling-group border-top px-4 py-4">
                                        <h6 class="text-primary text-xs fw-bold text-uppercase mb-4 tracking-wider">{{ $groupName }}</h6>
                                        <div class="row g-4">
                                            @foreach($keys as $key)
                                                @if($setting = $stylingSettings->get($key))
                                                    <div class="col-lg-4 col-md-6">
                                                        <label class="form-label text-xs fw-bold text-muted text-uppercase d-block mb-2">{{ str_replace('_', ' ', str_replace($groupName.'_', '', $key)) }}</label>
                                                        @if($setting->type === 'color')
                                                            <div class="color-input-wrapper">
                                                                <input type="color" class="form-control-color" name="{{ $setting->key }}" value="{{ $setting->value }}" onchange="this.nextElementSibling.value = this.value">
                                                                <input type="text" class="form-control form-control-sm text-xs font-monospace border-0 bg-transparent" value="{{ $setting->value }}" oninput="this.previousElementSibling.value = this.value">
                                                            </div>
                                                        @else
                                                            <input type="text" name="{{ $setting->key }}" class="form-control form-control-premium py-2 text-sm" value="{{ $setting->value }}">
                                                        @endif
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pb-5">
                    <button type="submit" class="btn btn-dark-premium btn-lg w-100 py-3 shadow-lg">
                        <i class="fas fa-save me-2"></i> Save All Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .bg-primary-soft { background-color: rgba(94, 114, 228, 0.1); }
    .bg-light-soft { background-color: #fcfcfd; }
    
    .custom-settings-nav .nav-link {
        color: #67748e;
        border-radius: 12px;
        padding: 1rem 1.25rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }
    
    .custom-settings-nav .nav-link:hover {
        background-color: #f8f9fa;
        color: #344767;
    }
    
    .custom-settings-nav .nav-link.active {
        background-color: #fff;
        color: #5e72e4;
        border-color: rgba(94, 114, 228, 0.2);
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
    
    .preview-square {
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 5px;
        overflow: hidden;
    }
    
    .preview-square img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
    
    .color-input-wrapper {
        display: flex;
        align-items: center;
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 4px 8px;
        transition: all 0.2s ease;
    }
    
    .color-input-wrapper:focus-within {
        border-color: #5e72e4;
        box-shadow: 0 0 0 2px rgba(94, 114, 228, 0.1);
    }
    
    .form-control-color {
        width: 28px;
        height: 28px;
        padding: 0;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        background: none;
    }
    
    .form-control-color::-webkit-color-swatch {
        border: none;
        border-radius: 6px;
    }
    
    .tracking-wider { letter-spacing: 0.05em; }
    .text-xs { font-size: 0.7rem; }
    
    .styling-group:first-of-type { border-top: none !important; }
    
    .settings-form input:focus {
        background-color: #fff !important;
    }
</style>

@push('scripts')
<script>
    // Show indicator when any input changes
    document.querySelector('.settings-form').addEventListener('input', function() {
        document.getElementById('save-indicator').classList.remove('d-none');
    });
</script>
@endpush
@endsection
