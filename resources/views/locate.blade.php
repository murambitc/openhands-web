<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Support Map — {{ config('app.name', 'Open Hands') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('img/logo/logo.png') }}">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        html, body { margin: 0; padding: 0; width: 100%; height: 100%; overflow: hidden; background: #0f172a; font-family: 'Instrument Sans', sans-serif; }
        .map-header { position: absolute; top: 0; left: 0; right: 0; height: 60px; background: rgba(11, 18, 32, 0.85); backdrop-filter: blur(12px); border-bottom: 1px solid rgba(255, 255, 255, 0.1); display: flex; align-items: center; justify-content: space-between; padding: 0 20px; z-index: 1000; }
        .map-header a { color: #fff; text-decoration: none; font-weight: 600; font-size: 14px; display: inline-flex; align-items: center; gap: 6px; }
        .map-header a:hover { color: #ff4d8d; }
        #leafletMap { width: 100%; height: calc(100vh - 60px); position: absolute; top: 60px; left: 0; z-index: 1; }
        .leaflet-popup-content-wrapper { background: #1b2a47 !important; color: #e2e8f0 !important; border-radius: 12px; padding: 0; box-shadow: 0 10px 30px rgba(0,0,0,0.5); border: 1px solid rgba(255,255,255,0.1); }
        .leaflet-popup-content { margin: 14px; }
        .leaflet-popup-tip { background: #1b2a47 !important; }
        .leaflet-container a.leaflet-popup-close-button { color: #94a3b8; padding: 8px 8px 0 0; }
        .leaflet-container a.leaflet-popup-close-button:hover { color: #fff; }
    </style>
</head>
<body>

    <div class="map-header">
        <a href="{{ url()->previous() == url()->current() ? route('home') : url()->previous() }}">
            <i class="bi bi-arrow-left"></i> Back
        </a>
        <h5 class="text-white mb-0 fs-6"><i class="bi bi-geo-alt-fill text-danger"></i> Support Partners Map</h5>
        <a href="{{ route('home') }}"><i class="bi bi-house-door"></i> Home</a>
    </div>

    <div id="leafletMap"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const map = L.map('leafletMap').setView([-24.650818, 25.917332], 6);

            // Clean standard OpenStreetMap tiles (normal/bright view)
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 19
            }).addTo(map);

            const organizations = @json($organizations);
            const markerColors = ['#ef4444', '#3b82f6', '#22c55e', '#f59e0b', '#a855f7', '#ec4899', '#06b6d4', '#dc2626'];
            let markerBounds = [];

            organizations.forEach((org, index) => {
                let lat = parseFloat(org.latitude) || (-24.650818 + (Math.random() - 0.5) * 0.4);
                let lng = parseFloat(org.longitude) || (25.917332 + (Math.random() - 0.5) * 0.4);
                let color = markerColors[index % markerColors.length];

                const svgIcon = L.divIcon({
                    className: 'custom-marker',
                    html: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="${color}" width="40" height="40" style="filter: drop-shadow(0 4px 4px rgba(0,0,0,0.6));"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>`,
                    iconSize: [40, 40], iconAnchor: [20, 40], popupAnchor: [0, -40]
                });

                let logoHtml = org.logo 
                    ? `<img src="{{ url('/') }}/${org.logo}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px; margin-bottom: 8px;">` 
                    : `<div style="width: 50px; height: 50px; border-radius: 8px; background: ${color}; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 20px; margin-bottom: 8px;">${org.name.charAt(0)}</div>`;

                let phoneHtml = org.phone ? `<div style="font-size: 12px; margin-top: 4px;"><i class="bi bi-telephone-fill" style="color: ${color};"></i> <a href="tel:${org.phone}" style="color: #3b82f6; text-decoration: none;">${org.phone}</a></div>` : '';
                let emailHtml = org.email ? `<div style="font-size: 12px; margin-top: 4px;"><i class="bi bi-envelope-fill" style="color: ${color};"></i> <a href="mailto:${org.email}" style="color: #3b82f6; text-decoration: none;">${org.email}</a></div>` : '';
                let webHtml = org.website ? `<div style="font-size: 12px; margin-top: 4px;"><i class="bi bi-globe" style="color: ${color};"></i> <a href="${org.website}" target="_blank" style="color: #3b82f6; text-decoration: none;">Website</a></div>` : '';

                let popupContent = `
                    <div style="font-family: 'Instrument Sans', sans-serif; min-width: 220px;">
                        ${logoHtml}<h4 style="margin: 0 0 6px 0; font-size: 15px; font-weight: 700; color: #fff;">${org.name}</h4>
                        <p style="margin: 0 0 10px 0; font-size: 12px; color: #cbd5f5; line-height: 1.4;">${org.description ? org.description.substring(0, 90) + '...' : 'No description available.'}</p>
                        <hr style="margin: 8px 0; border-color: rgba(255,255,255,0.1);">${phoneHtml}${emailHtml}${webHtml}
                    </div>`;

                const marker = L.marker([lat, lng], { icon: svgIcon }).bindPopup(popupContent).addTo(map);
                markerBounds.push([lat, lng]);
            });

            if (markerBounds.length > 0) {
                map.fitBounds(markerBounds, { padding: [50, 50], maxZoom: 14 });
            }
        });
    </script>
</body>
</html>