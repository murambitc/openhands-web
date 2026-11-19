@extends('layouts.app')

@section('title', 'Add Organization')

@push('styles')
<style>
    .ao-wrap { max-width: 780px; margin: 0 auto; }
    .ao-title { color:#fff; font-size:22px; font-weight:700; margin-bottom:4px; }
    .ao-subtitle { color:#94a3b8; font-size:13px; margin-bottom:22px; }

    .ao-card {
        background: rgba(255,255,255,0.05);
        backdrop-filter: blur(14px);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 16px;
        padding: 24px;
    }

    .ao-label { color:#cbd5f5; font-size:13px; font-weight:500; margin-bottom:4px; display:block; }
    .ao-hint { color:#64748b; font-size:11.5px; margin-top:3px; display:block; }

    .ao-input, .ao-textarea {
        width: 100%;
        background: #0f172a !important;
        border: 1px solid #253a5c;
        color: #fff !important;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 13.5px;
    }
    .ao-textarea { min-height: 90px; resize: vertical; }

    .ao-input:focus, .ao-textarea:focus {
        outline: none;
        border-color: #ff4d8d;
        box-shadow: 0 0 0 3px rgba(255,77,141,0.15);
    }
    .ao-input::placeholder, .ao-textarea::placeholder { color:#64748b; }

    .ao-input[type="file"] {
        padding: 8px 12px;
        color: #cbd5f5;
    }
    .ao-input[type="file"]::file-selector-button {
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.15);
        color: #fff;
        padding: 6px 12px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 12px;
        margin-right: 10px;
    }

    .ao-btn {
        padding: 11px 24px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }
    .ao-btn-primary { background: linear-gradient(180deg, #ff4d8d, #c2185b); color: #fff; }
    .ao-btn-primary:hover { color:#fff; transform: translateY(-1px); }
    .ao-btn-outline { background: rgba(255,255,255,0.08); color: #fff; }
    .ao-btn-outline:hover { background: rgba(255,255,255,0.15); color:#fff; }
</style>
@endpush

@section('content')
<div class="ao-wrap">
    <h3 class="ao-title">🏢 Add Organization</h3>
    <p class="ao-subtitle">Register a new support organization in the directory.</p>

    <div class="ao-card">
        <form method="POST" action="{{ route('admin.organizations.store') }}" enctype="multipart/form-data">
            @csrf

            @if ($errors->any())
                <div class="alert alert-danger mb-3">
                    <ul class="mb-0" style="font-size:13px;">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row g-3">
                <div class="col-12">
                    <label class="ao-label">Logo <span style="color:#94a3b8;">(optional)</span></label>
                    <input type="file" name="logo" accept="image/*" class="ao-input">
                    <span class="ao-hint">Square images work best. Max 2MB.</span>
                </div>

                <div class="col-12">
                    <label class="ao-label">Organization Name</label>
                    <input type="text" name="name" class="ao-input" value="{{ old('name') }}" required
                           placeholder="e.g. Friends of Diversity">
                </div>

                <div class="col-12">
                    <label class="ao-label">Brief Introduction</label>
                    <textarea name="description" class="ao-textarea" required
                              placeholder="What does this organization do? Who do they serve?">{{ old('description') }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="ao-label">Phone Number</label>
                    <input type="text" name="phone" class="ao-input" value="{{ old('phone') }}"
                           placeholder="e.g. 77649963">
                </div>

                <div class="col-md-6">
                    <label class="ao-label">Email Address</label>
                    <input type="email" name="email" class="ao-input" value="{{ old('email') }}"
                           placeholder="contact@example.org">
                </div>

                <div class="col-md-6">
                    <label class="ao-label">WhatsApp Number <span style="color:#94a3b8;">(with country code)</span></label>
                    <input type="text" name="whatsapp" class="ao-input" value="{{ old('whatsapp') }}"
                           placeholder="e.g. 26777649963">
                    <span class="ao-hint">Used to open a WhatsApp chat when clicked.</span>
                </div>

                <div class="col-md-6">
                    <label class="ao-label">Website</label>
                    <input type="text" name="website" class="ao-input" value="{{ old('website') }}"
                           placeholder="https://example.org">
                </div>

                <div class="col-12">
                    <label class="ao-label">Google Maps Link</label>
                    <input type="text" name="map_link" class="ao-input" value="{{ old('map_link') }}"
                           placeholder="https://maps.google.com/?q=...">
                    <span class="ao-hint">Opens in a new tab when users click the map button.</span>
                </div>

                <div class="col-md-6">
                    <label class="ao-label">Latitude</label>
                    <input type="text" name="latitude" class="ao-input" value="{{ old('latitude') }}"
                           placeholder="e.g. -24.6282">
                </div>

                <div class="col-md-6">
                    <label class="ao-label">Longitude</label>
                    <input type="text" name="longitude" class="ao-input" value="{{ old('longitude') }}"
                           placeholder="e.g. 25.9231">
                </div>
            </div>

            <div style="display:flex; gap:10px; margin-top:24px;">
                <a href="{{ route('admin.organizations.index') }}" class="ao-btn ao-btn-outline">Cancel</a>
                <button type="submit" class="ao-btn ao-btn-primary">
                    <i class="bi bi-plus-circle"></i> Add Organization
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
