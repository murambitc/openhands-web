{{-- =====================
     EMERGENCY PANIC BUTTON
===================== --}}
<button class="panic-float-btn" data-bs-toggle="modal" data-bs-target="#panicEmergencyModal" aria-label="Emergency help">
    🚨
</button>

<div class="modal fade panic-emergency-modal" id="panicEmergencyModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title text-danger">
                    <i class="bi bi-exclamation-triangle-fill"></i> Emergency Help
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <p style="color: #94a3b8; margin-bottom: 20px; font-size: 14px;">
                    If you are in immediate danger, reach out now. Your location will be shared with responders.
                </p>

                <button type="button"
                        onclick="triggerPanic()"
                        class="btn btn-danger w-100 mb-2"
                        style="padding: 12px; font-weight: 600; border-radius: 10px;">
                    <i class="bi bi-geo-alt-fill"></i> Call Emergency &amp; Share Location
                </button>

                <a href="https://wa.me/{{ env('EMERGENCY_WHATSAPP', '26777649963') }}?text={{ urlencode('Emergency, I need help now') }}"
                   target="_blank"
                   class="btn btn-success w-100 mb-2"
                   style="padding: 12px; font-weight: 600; border-radius: 10px;">
                    <i class="bi bi-whatsapp"></i> WhatsApp Emergency
                </a>

                <a href="tel:999"
                   class="btn btn-dark w-100 mb-2"
                   style="padding: 12px; font-weight: 600; border-radius: 10px;">
                    <i class="bi bi-telephone-fill"></i> Call Police (999)
                </a>

                <a href="tel:997"
                   class="btn btn-info w-100 mb-2"
                   style="padding: 12px; font-weight: 600; border-radius: 10px;">
                    <i class="bi bi-hospital"></i> Call Ambulance (997)
                </a>

                <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .panic-float-btn {
        position: fixed;
        bottom: 90px;
        right: 20px;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #dc2626;
        border: none;
        color: #fff;
        font-size: 24px;
        cursor: pointer;
        z-index: 998;
        box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.7);
        animation: panicPulse 1.8s infinite;
        transition: transform 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .panic-float-btn:hover {
        transform: scale(1.08);
    }

    @keyframes panicPulse {
        0%   { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.7); }
        70%  { box-shadow: 0 0 0 20px rgba(220, 38, 38, 0); }
        100% { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0); }
    }

    .panic-emergency-modal .modal-content {
        background: #1b2a47;
        color: #fff;
        border-radius: 16px;
        border: 1px solid rgba(220, 38, 38, 0.4);
        box-shadow: 0 0 40px rgba(220, 38, 38, 0.25);
    }

    @media (max-width: 480px) {
        .panic-float-btn {
            width: 54px;
            height: 54px;
            font-size: 20px;
            bottom: 80px;
            right: 15px;
        }
    }
</style>

<script>
    window.triggerPanic = async function () {
        // 1) Try to get current location
        let coords = null;
        try {
            coords = await new Promise((resolve, reject) => {
                if (!navigator.geolocation) return reject();
                navigator.geolocation.getCurrentPosition(
                    pos => resolve(pos.coords),
                    () => reject(),
                    { timeout: 5000 }
                );
            });
        } catch (e) { /* no location */ }

        // 2) Log the panic on the server
        try {
            await fetch('{{ route("panic") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    lat: coords?.latitude ?? null,
                    lng: coords?.longitude ?? null,
                }),
            });
        } catch (e) { /* silent */ }

        // 3) Open WhatsApp with location message
        const mapLink = coords
            ? `https://maps.google.com/?q=${coords.latitude},${coords.longitude}`
            : 'Location unavailable';
        const message = encodeURIComponent(`🚨 EMERGENCY! I need help.\nMy location: ${mapLink}`);

        // 4) Dial emergency number
        window.location.href = 'tel:999';

        // 5) Open WhatsApp in new tab (after small delay so phone dialer doesn't block)
        setTimeout(() => {
            window.open(`https://wa.me/{{ env('EMERGENCY_WHATSAPP', '26777649963') }}?text=${message}`, '_blank');
        }, 800);
    };
</script>