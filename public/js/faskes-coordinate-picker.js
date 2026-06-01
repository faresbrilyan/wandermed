(function() {
    var pickedLat = null, pickedLng = null;
    var miniMapInstance = null, pickedMarker = null;

    window.syncCoord = function() {
        var lat = document.getElementById('inputLat').value;
        var lng = document.getElementById('inputLng').value;
        document.getElementById('hiddenLat').value = lat;
        document.getElementById('hiddenLng').value = lng;
    };

    // Init mini-map setelah modal terbuka penuh (timeout 300ms agar animasi Bootstrap selesai)
    $('#modalKoordinat').on('shown.bs.modal', function() {
        setTimeout(function() {
            if (!miniMapInstance) {
                miniMapInstance = L.map('miniMap').setView([-6.5718, 107.7600], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap'
                }).addTo(miniMapInstance);

                miniMapInstance.on('click', function(e) {
                    pickedLat = e.latlng.lat.toFixed(6);
                    pickedLng = e.latlng.lng.toFixed(6);

                    if (pickedMarker) miniMapInstance.removeLayer(pickedMarker);
                    pickedMarker = L.marker(e.latlng)
                        .addTo(miniMapInstance)
                        .bindPopup('<b>📍 Lokasi Dipilih</b><br>Lat: ' + pickedLat + '<br>Lng: ' + pickedLng)
                        .openPopup();

                    document.getElementById('previewLat').textContent = pickedLat;
                    document.getElementById('previewLng').textContent = pickedLng;
                    document.getElementById('btnApplyCoord').disabled = false;
                });
            } else {
                // Penting: panggil invalidateSize agar tile ter-render ulang
                miniMapInstance.invalidateSize();
            }
        }, 300);
    });

    window.applyPickedCoord = function() {
        if (!pickedLat || !pickedLng) return;
        document.getElementById('inputLat').value = pickedLat;
        document.getElementById('inputLng').value = pickedLng;
        document.getElementById('hiddenLat').value = pickedLat;
        document.getElementById('hiddenLng').value = pickedLng;
        $('#modalKoordinat').modal('hide');
    };
})();
