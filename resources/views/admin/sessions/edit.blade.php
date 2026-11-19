@extends('layouts.app')

@section('title', 'Edit Session')

@push('styles')
<style>
    .es-wrap { max-width: 780px; margin: 0 auto; }
    .es-title { color:#fff; font-size:22px; font-weight:700; margin-bottom:4px; }
    .es-subtitle { color:#94a3b8; font-size:13px; margin-bottom:22px; }

    .es-card {
        background: rgba(255,255,255,0.05);
        backdrop-filter: blur(14px);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 16px;
        padding: 24px;
    }

    .es-label { color:#cbd5f5; font-size:13px; font-weight:500; margin-bottom:4px; display:block; }

    .es-input, .es-textarea {
        width: 100%;
        background: #0f172a !important;
        border: 1px solid #253a5c;
        color: #fff !important;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 13.5px;
    }
    .es-textarea { min-height: 100px; resize: vertical; }

    .es-input:focus, .es-textarea:focus {
        outline: none;
        border-color: #ff4d8d;
        box-shadow: 0 0 0 3px rgba(255,77,141,0.15);
    }

    .es-input[type="file"] {
        padding: 8px 12px;
        color: #cbd5f5;
    }
    .es-input[type="file"]::file-selector-button {
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.15);
        color: #fff;
        padding: 6px 12px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 12px;
        margin-right: 10px;
    }

    .es-current-img {
        width: 180px;
        height: 100px;
        object-fit: cover;
        border-radius: 10px;
        border: 1px solid rgba(255,255,255,0.1);
        margin-bottom: 8px;
        display: block;
    }

    .es-btn {
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
    .es-btn-primary { background: linear-gradient(180deg, #ff4d8d, #c2185b); color: #fff; }
    .es-btn-primary:hover { color:#fff; transform: translateY(-1px); }
    .es-btn-outline { background: rgba(255,255,255,0.08); color: #fff; }
    .es-btn-outline:hover { background: rgba(255,255,255,0.15); color:#fff; }
    .es-btn-danger { background: #dc2626; color: #fff; }
    .es-btn-danger:hover { background: #b91c1c; color:#fff; }

    .es-active-toggle {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 14px;
        background: rgba(255,255,255,0.04);
        border-radius: 10px;
        border: 1px solid rgba(255,255,255,0.08);
    }
    .es-active-toggle input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: #22c55e;
        cursor: pointer;
    }
    .es-active-toggle label {
        color: #fff;
        font-size: 13.5px;
        cursor: pointer;
        margin: 0;
    }
</style>
@endpush

@section('content')
<div class="es-wrap">
    <h3 class="es-title">✏️ Edit Session</h3>
    <p class="es-subtitle">Update the details of this session.</p>

    <div class="es-card">
        <form method="POST" action="{{ route('admin.sessions.update', $session) }}" enctype="multipart/form-data">
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
                    <label class="es-label">Cover Image <span style="color:#94a3b8;">(leave empty to keep current)</span></label>
                    @if ($session->image)
                        <img src="{{ asset('storage/' . $session->image) }}" alt="" class="es-current-img">
                    @endif
                    <input type="file" name="image" accept="image/*" class="es-input">
                </div>

                <div class="col-12">
                    <label class="es-label">Session Title</label>
                    <input type="text" name="title" class="es-input"
                           value="{{ old('title', $session->title) }}" required>
                </div>

                <div class="col-12">
                    <label class="es-label">Description</label>
                    <textarea name="description" class="es-textarea" required>{{ old('description', $session->description) }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="es-label">Date</label>
                    <input type="date" name="session_date" class="es-input"
                           value="{{ old('session_date', $session->session_date ? \Carbon\Carbon::parse($session->session_date)->format('Y-m-d') : '') }}"
                           required>
                </div>
                <div class="col-md-6">
                    <label class="es-label">Time</label>
                    <input type="time" name="session_time" class="es-input"
                           value="{{ old('session_time', $session->session_time ? \Carbon\Carbon::parse($session->session_time)->format('H:i') : '') }}"
                           required>
                </div>

                <div class="col-12">
                    <label class="es-label">Location</label>
                    <input type="text" name="location" class="es-input"
                           value="{{ old('location', $session->location) }}" required>
                </div>

                <div class="col-12">
                    <div class="es-active-toggle">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" id="isActive"
                               {{ old('is_active', $session->is_active) ? 'checked' : '' }}>
                        <label for="isActive">Session is active and visible to users</label>
                    </div>
                </div>
            </div>

            <div style="display:flex; gap:10px; margin-top:24px; flex-wrap: wrap;">
                <a href="{{ route('admin.sessions.index') }}" class="es-btn es-btn-outline">Cancel</a>
                <button type="submit" class="es-btn es-btn-primary">
                    <i class="bi bi-save"></i> Save Changes
                </button>
            </div>
        </form>

        <hr style="border-color: rgba(255,255,255,0.1); margin: 24px 0;">

        {{-- DELETE --}}
        <form method="POST" action="{{ route('admin.sessions.destroy', $session) }}"
              onsubmit="return confirm('Permanently delete this session? This cannot be undone.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="es-btn es-btn-danger">
                <i class="bi bi-trash"></i> Delete Session
            </button>
        </form>
    </div>
</div>
@endsection