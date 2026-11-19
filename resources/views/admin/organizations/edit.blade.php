@extends('layouts.app')

@section('title', 'Edit Organization')

@push('styles')
<style>
    .eo-wrap { max-width: 780px; margin: 0 auto; }
    .eo-title { color:#fff; font-size:22px; font-weight:700; margin-bottom:4px; }
    .eo-subtitle { color:#94a3b8; font-size:13px; margin-bottom:22px; }

    .eo-card {
        background: rgba(255,255,255,0.05);
        backdrop-filter: blur(14px);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 16px;
        padding: 24px;
    }

    .eo-label { color:#cbd5f5; font-size:13px; font-weight:500; margin-bottom:4px; display:block; }
    .eo-hint { color:#64748b; font-size:11.5px; margin-top:3px; display:block; }

    .eo-input, .eo-textarea {
        width: 100%;
        background: #0f172a !important;
        border: 1px solid #253a5c;
        color: #fff !important;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 13.5px;
    }
    .eo-textarea { min-height: 90px; resize: vertical; }

    .eo-input:focus, .eo-textarea:focus {
        outline: none;
        border-color: #ff4d8d;
        box-shadow: 0 0 0 3px rgba(255,77,141,0.15);
    }

    .eo-input[type="file"] {
        padding: 8px 12px;
        color: #cbd5f5;
    }
    .eo-input[type="file"]::file-selector-button {
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.15);
        color: #fff;
        padding: 6px 12px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 12px;
        margin-right: 10px;
    }

    .eo-current-logo {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 12px;
        border: 1px solid rgba(255,255,255,0.1);
        margin-bottom: 8px;
        display: block;
    }

    .eo-btn {
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
    .eo-btn-primary { background: linear-gradient(180deg, #ff4d8d, #c2185b); color: #fff; }
    .eo-btn-primary:hover { color:#fff; transform: translateY(-1px); }
    .eo-btn-outline { background: rgba(255,255,255,0.08); color: #fff; }
    .eo-btn-outline:hover { background: rgba(255,255,255,0.15); color:#fff; }
    .eo-btn-danger { background: #dc2626; color: #fff; }
    .eo-btn-danger:hover { background: #b91c1c; color:#fff; }
</style>
@endpush

@section('content')
<div class="eo-wrap">
    <h3 class="eo-title">✏️ Edit Organization</h3>
    <p class="eo-subtitle">Update directory information for {{ $organization->name }}.</p>

    <div class="eo-card">
        <form method="POST" action="{{ route('admin.organizations.update', $organization) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

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
                    <label class="eo-label">Logo <span style="color:#94a3b8;">(leave empty to keep current)</span></label>
                    @if ($organization->logo)
                        <img src="{{ asset('storage/' . $organization->logo) }}" alt="" class="eo-current-logo">
                    @endif
                    <input type="file" name="logo" accept="image/*" class="eo-input">
                </div>

                <div class="col-12">
                    <label class="eo-label">Organization Name</label>
                    <input type="text" name="name" class="eo-input"
                           value="{{ old('name', $organization->name) }}" required>
                </div>

                <div class="col-12">
                    <label class="eo-label">Brief Introduction</label>
                    <textarea name="description" class="eo-textarea" required>{{ old('description', $organization->description) }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="eo-label">Phone Number</label>
                    <input type="text" name="phone" class="eo-input" value="{{ old('phone', $organization->phone) }}">
                </div>

                <div class="col-md-6">
                    <label class="eo-label">Email Address</label>
                    <input type="email" name="email" class="eo-input" value="{{ old('email', $organization->email) }}">
                </div>

                <div class="col-md-6">
                    <label class="eo-label">WhatsApp Number</label>
                    <input type="text" name="whatsapp" class="eo-input" value="{{ old('whatsapp', $organization->whatsapp) }}">
                </div>

                <div class="col-md-6">
                    <label class="eo-label">Website</label>
                    <input type="text" name="website" class="eo-input" value="{{ old('website', $organization->website) }}">
                </div>

                <div class="col-12">
                    <label class="eo-label">Google Maps Link</label>
                    <input type="text" name="map_link" class="eo-input" value="{{ old('map_link', $organization->map_link) }}">
                </div>

                <div class="col-md-6">
                    <label class="eo-label">Latitude</label>
                    <input type="text" name="latitude" class="eo-input"
                           value="{{ old('latitude', $organization->latitude) }}" placeholder="e.g. -24.6282">
                </div>

                <div class="col-md-6">
                    <label class="eo-label">Longitude</label>
                    <input type="text" name="longitude" class="eo-input"
                           value="{{ old('longitude', $organization->longitude) }}" placeholder="e.g. 25.9231">
                </div>
            </div>

            <div style="display:flex; gap:10px; margin-top:24px;">
                <a href="{{ route('admin.organizations.index') }}" class="eo-btn eo-btn-outline">Cancel</a>
                <button type="submit" class="eo-btn eo-btn-primary">
                    <i class="bi bi-save"></i> Save Changes
                </button>
            </div>
        </form>

        <hr style="border-color: rgba(255,255,255,0.1); margin: 24px 0;">

        <form method="POST" action="{{ route('admin.organizations.destroy', $organization) }}"
              onsubmit="return confirm('Permanently delete this organization?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="eo-btn eo-btn-danger">
                <i class="bi bi-trash"></i> Delete Organization
            </button>
        </form>
    </div>
</div>
@endsection
```[cite: 8]