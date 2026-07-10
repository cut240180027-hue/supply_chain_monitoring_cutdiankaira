document.addEventListener('DOMContentLoaded', function () {
    var mapEl = document.getElementById('map');
    if (!mapEl) {
        return;
    }

    var map = L.map('map').setView([31.2304, 121.4737], 5);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 18,
    }).addTo(map);

    var shipmentIcon = L.icon({
        iconUrl: 'https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/images/marker-icon.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
    });

    L.marker([31.2304, 121.4737], { icon: shipmentIcon })
        .addTo(map)
        .bindPopup('<strong>Shipment SH001</strong><br>Shanghai Port');

    L.marker([1.3521, 103.8198], { icon: shipmentIcon })
        .addTo(map)
        .bindPopup('<strong>Shipment SH002</strong><br>Singapore Port');

    L.marker([22.3964, 114.1095], { icon: shipmentIcon })
        .addTo(map)
        .bindPopup('<strong>Shipment SH003</strong><br>Hong Kong Port');
});