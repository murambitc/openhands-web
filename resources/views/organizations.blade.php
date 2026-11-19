@extends('layouts.app')

@section('title', 'Directory')

@push('styles')
<style>
    .org-wrap { max-width: 980px; margin: 0 auto; }

    .org-title {
        color: #fff;
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .org-subtitle {
        color: #94a3b8;
        font-size: 13px;
        margin-bottom: 20px;
    }

    .org-search {
        width: 100%;
        background: rgba(15, 23, 42, 0.7);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #fff;
        border-radius: 12px;
        padding: 12px 16px 12px 42px;
        font-size: 14px;
        margin-bottom: 20px;
        transition: all 0.2s ease;
        position: relative;
    }

    .org-search-wrap { position: relative; }

    .org-search-wrap i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 16px;
        pointer-events: none;
        z-index: 2;
    }

    .org-search:focus {
        outline: none;
        border-color: #ff4d8d;
        box-shadow: 0 0 0 3px rgba(255, 77, 141, 0.15);
        background: rgba(15, 23, 42, 0.9);
    }

    .org-search::placeholder { color: #94a3b8; }

    .org-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 14px;
    }

    .org-card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(14px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        padding: 18px;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .org-card:hover {
        transform: translateY(-3px);
        border-color: rgba(255, 77, 141, 0.35);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.35);
    }

    .org-card-header {
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .org-logo {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        background: linear-gradient(180deg, #ff4d8d, #c2185b);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 22px;
        flex-shrink: 0;
        overflow: hidden;
    }

    .org-logo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .org-info { flex: 1; min-width: 0; }

    .org-name {
        color: #fff;
        font-size: 15px;
        font-weight: 600;
        margin: 0 0 4px;
        line-height: 1.3;
    }

    .org-desc {
        color: #94a3b8;
        font-size: 12.5px;
        line-height: 1.5;
        margin: 0;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
    }

    .org-actions {
        display: flex;
        gap: 8px;
        margin-top: auto;
        padding-top: 8px;
    }

    .org-btn {
        flex: 1;
        padding: 9px 14px;
        border-radius: 10px;
        font-size: 12.5px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        text-decoration: none;
    }

    .org-btn-primary {
        background: linear-gradient(180deg, #ff4d8d, #c2185b);
        color: #fff;
    }

    .org-btn-primary:hover {
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 6px 15px rgba(190, 24, 93, 0.35);
    }

    .org-btn-outline {
        background: transparent;
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #fff;
    }

    .org-btn-outline:hover {
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
    }

    .org-empty {
        text-align: center;
        padding: 60px 20px;
        background: rgba(255, 255, 255, 0.03);
        border: 1px dashed rgba(255, 255, 255, 0.15);
        border-radius: 16px;
        color: #94a3b8;
    }

    .org-empty .icon { font-size: 48px; opacity: 0.5; margin-bottom: 12px; }
    .org-empty h5 { color: #fff; font-size: 16px; margin-bottom: 6px; }
    .org-empty p { font-size: 13px; margin-bottom: 0; }

    /* Modal */
    .modal-content {
        background: rgba(27, 42, 71, 0.98) !important;
        backdrop-filter: blur(16px);
        color: #fff;
        border-radius: 16px;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .modal-header { border-color: rgba(255, 255, 255, 0.1); }
    .modal-title { color: #fff; font-weight: 600; }
    .btn-close { filter: invert(1); opacity: 0.8; }

    .modal-org-logo {
        width: 70px;
        height: 70px;
        border-radius: 16px;
        background: linear-gradient(180deg, #ff4d8d, #c2185b);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 30px;
        margin: 0 auto 14px;
        overflow: hidden;
    }

    .modal-org-logo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .org-contact-row {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 14px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 10px;
        margin-bottom: 8px;
        color: #fff;
        text-decoration: none;
        font-size: 13.5px;
        transition: background 0.2s;
    }

    .org-contact-row:hover {
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
    }

    .org-contact-row i { font-size: 16px; color: #ff4d8d; flex-shrink: 0; }
    .org-contact-row.whatsapp i { color: #22c55e; }
    .org-contact-row.email i { color: #3b82f6; }
    .org-contact-row.web i { color: #06b6d4; }

    @media (max-width: 576px) {
        .org-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="org-wrap">
    <h3 class="org-title">🏢 Support Directory</h3>
    <p class="org-subtitle">Verified organizations ready to help. Tap any to view contact details.</p>

    {{-- Search --}}
    <div class="org-search-wrap">
        <i class="bi bi-search"></i>
        <input type="text"
               id="orgSearch"
               class="org-search"
               placeholder="Search organizations...">
    </div>

    {{-- Grid --}}
    @if ($organizations->count() > 0)
        <div class="org-grid" id="orgGrid">
            @foreach ($organizations as $org)
                <div class="org-card"
                     data-search="{{ strtolower($org->name . ' ' . $org->description) }}">
                    <div class="org-card-header">
                        <div class="org-logo">
                            @if ($org->logo)
                                <img src="{{ asset('storage/' . $org->logo) }}" alt="{{ $org->name }}">
                            @else
                                <i class="bi bi-building"></i>
                            @endif
                        </div>
                        <div class="org-info">
                            <h5 class="org-name">{{ $org->name }}</h5>
                            <p class="org-desc">{{ $org->description }}</p>
                        </div>
                    </div>

                    <div class="org-actions">
                        <button type="button"
                                class="org-btn org-btn-primary"
                                onclick='openOrgModal(@json($org->name), @json($org->description), @json($org->phone), @json($org->whatsapp), @json($org->email), @json($org->website), @json($org->logo ? asset("storage/" . $org->logo) : null))'>
                            <i class="bi bi-eye"></i> View Details
                        </button>

                        @if ($org->map_link)
                            <a href="{{ $org->map_link }}"
                               target="_blank" rel="noopener"
                               class="org-btn org-btn-outline"
                               style="flex: 0 0 auto; padding: 9px 14px;">
                                <i class="bi bi-geo-alt-fill"></i>
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div id="noResults" class="org-empty" style="display:none; margin-top: 20px;">
            <div class="icon">🔍</div>
            <h5>No organizations match your search</h5>
            <p>Try a different keyword.</p>
        </div>
    @else
        <div class="org-empty">
            <div class="icon">🏢</div>
            <h5>No organizations yet</h5>
            <p>An administrator hasn't added any directory entries.</p>
        </div>
    @endif
</div>

{{-- ORGANIZATION DETAILS MODAL --}}
<div class="modal fade" id="orgModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title">Organization</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <div class="modal-org-logo" id="orgModalLogo">
                        <i class="bi bi-building"></i>
                    </div>
                    <h5 id="orgModalName" style="color:#fff; font-weight:600;"></h5>
                    <p id="orgModalDesc" style="color:#94a3b8; font-size:13px; line-height:1.5; margin:0;"></p>
                </div>

                <hr style="border-color: rgba(255,255,255,0.1);">

                <a id="orgModalCall" class="org-contact-row" style="display:none;">
                    <i class="bi bi-telephone-fill"></i>
                    <span>Call</span>
                </a>
                <a id="orgModalWhatsapp" class="org-contact-row whatsapp" target="_blank" rel="noopener" style="display:none;">
                    <i class="bi bi-whatsapp"></i>
                    <span>WhatsApp</span>
                </a>
                <a id="orgModalEmail" class="org-contact-row email" style="display:none;">
                    <i class="bi bi-envelope-fill"></i>
                    <span>Email</span>
                </a>
                <a id="orgModalWebsite" class="org-contact-row web" target="_blank" rel="noopener" style="display:none;">
                    <i class="bi bi-globe"></i>
                    <span>Visit Website</span>
                </a>

                <button type="button"
                        class="org-btn org-btn-primary w-100 mt-3"
                        data-bs-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    /* ================================
       SEARCH FILTER
    ================================= */
    const searchInput = document.getElementById('orgSearch');
    const grid        = document.getElementById('orgGrid');
    const noResults   = document.getElementById('noResults');

    if (searchInput && grid) {
        searchInput.addEventListener('input', function () {
            const query = this.value.trim().toLowerCase();
            const cards = grid.querySelectorAll('.org-card');
            let visible = 0;

            cards.forEach(card => {
                const match = !query || card.dataset.search.includes(query);
                card.style.display = match ? '' : 'none';
                if (match) visible++;
            });

            if (noResults) {
                noResults.style.display = visible === 0 ? 'block' : 'none';
            }
        });
    }

    /* ================================
       ORG DETAILS MODAL
    ================================= */
    window.openOrgModal = function (name, desc, phone, whatsapp, email, website, logoUrl) {
        document.getElementById('orgModalName').textContent = name || 'Organization';
        document.getElementById('orgModalDesc').textContent = desc || '';

        // Logo
        const logoEl = document.getElementById('orgModalLogo');
        if (logoUrl) {
            logoEl.innerHTML = `<img src="${logoUrl}" alt="">`;
        } else {
            logoEl.innerHTML = '<i class="bi bi-building"></i>';
        }

        // Call
        const callEl = document.getElementById('orgModalCall');
        if (phone) {
            const clean = String(phone).replace(/\s+/g, '');
            callEl.href = `tel:${clean}`;
            callEl.querySelector('span').textContent = phone;
            callEl.style.display = 'flex';
        } else {
            callEl.style.display = 'none';
        }

        // WhatsApp
        const waEl = document.getElementById('orgModalWhatsapp');
        if (whatsapp) {
            const clean = String(whatsapp).replace(/\D/g, '');
            const msg = encodeURIComponent('Hello, I need support from ' + name);
            waEl.href = `https://wa.me/${clean}?text=${msg}`;
            waEl.style.display = 'flex';
        } else {
            waEl.style.display = 'none';
        }

        // Email
        const emailEl = document.getElementById('orgModalEmail');
        if (email) {
            emailEl.href = `mailto:${email}`;
            emailEl.querySelector('span').textContent = email;
            emailEl.style.display = 'flex';
        } else {
            emailEl.style.display = 'none';
        }

        // Website
        const webEl = document.getElementById('orgModalWebsite');
        if (website) {
            let w = String(website).trim();
            if (!/^https?:\/\//i.test(w)) w = 'https://' + w;
            webEl.href = w;
            webEl.style.display = 'flex';
        } else {
            webEl.style.display = 'none';
        }

        new bootstrap.Modal(document.getElementById('orgModal')).show();
    };
})();
</script>
@endpush