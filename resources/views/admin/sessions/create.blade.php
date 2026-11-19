@extends('layouts.app')

@section('title', 'Post New Session')

@push('styles')
<style>
    .cs-wrap { max-width: 780px; margin: 0 auto; }
    .cs-title { color:#fff; font-size:22px; font-weight:700; margin-bottom:4px; }
    .cs-subtitle { color:#94a3b8; font-size:13px; margin-bottom:22px; }

    .cs-card {
        background: rgba(255,255,255,0.05);
        backdrop-filter: blur(14px);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 16px;
        padding: 24px;
    }

    .cs-label { color:#cbd5f5; font-size:13px; font-weight:500; margin-bottom:4px; display:block; }

    .cs-input, .cs-textarea {
        width: 100%;
        background: #0f172a !important;
        border: 1px solid #253a5c;
        color: #fff !important;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 13.5px;
    }
    .cs-textarea { min-height: 100px; resize: vertical; }

    .cs-input:focus, .cs-textarea:focus {
        outline: none;
        border-color: #ff4d8d;
        box-shadow: 0 0 0 3px rgba(255,77,141,0.15);
    }
    .cs-input::placeholder, .cs-textarea::placeholder { color:#64748b; }

    .cs-input[type="file"] {
        padding: 8px 12px;
        color: #cbd5f5;
    }
    .cs-input[type="file"]::file-selector-button {
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.15);
        color: #fff;
        padding: 6px 12px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 12px;
        margin-right: 10px;
    }

    .cs-btn {
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
    .cs-btn-primary { background: linear-gradient(180deg, #ff4d8d, #c2185b); color: #fff; }
    .cs-btn-primary:hover { color:#fff; transform: translateY(-1px); }
    .cs-btn-outline { background: rgba(255,255,255,0.08); color: #fff; }
    .cs-btn-outline:hover { background: rgba(255,255,255,0.15); color:#fff; }
</style>
@endpush

@section('content')
<div class="cs-wrap">
    <h3 class="cs-title">📅 Post New Session</h3>
    <p class="cs-subtitle">Create a wellness session to share with all users.</p>

    <div class="cs-card">
        <form method="POST" action="{{ route('admin.sessions.store') }}" enctype="multipart/form-data">
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
                    <label class="cs-label">Cover Image <span style="color:#94a3b8;">(optional)</span></label>
                    <input type="file" name="image" accept="image/*" class="cs-input">
                </div>

                <div class="col-12">
                    <label class="cs-label">Session Title</label>
                    <input type="text" name="title" class="cs-input" value="{{ old('title') }}" required
                           placeholder="e.g. Mindful Mondays: Group Meditation">
                </div>

                <div class="col-12">
                    <label class="cs-label">Description</label>
                    <textarea name="description" class="cs-textarea" required
                              placeholder="What is this session about? Who is it for?">{{ old('description') }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="cs-label">Date</label>
                    <input type="date" name="session_date" class="cs-input"
                           value="{{ old('session_date') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="cs-label">Time</label>
                    <input type="time" name="session_time" class="cs-input"
                           value="{{ old('session_time') }}" required>
                </div>

                <div class="col-12">
                    <label class="cs-label">Location</label>
                    <input type="text" name="location" class="cs-input" value="{{ old('location') }}" required
                           placeholder="e.g. Gaborone Community Center or Online via Zoom">
                </div>
            </div>

            <div style="display:flex; gap:10px; margin-top:24px;">
                <a href="{{ route('admin.sessions.index') }}" class="cs-btn cs-btn-outline">Cancel</a>
                <button type="submit" class="cs-btn cs-btn-primary">
                    <i class="bi bi-megaphone"></i> Publish Session
                </button>
            </div>
        </form>
    </div>
</div>
@endsection