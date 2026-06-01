/* File: public/js/pariwisata-coordinate-picker.js */

(function() {
    var pickedLatP = null, pickedLngP = null;
    var miniMapP = null, pickedMarkerP = null;

    window.syncCoordP = function() {
        var lat = document.getElementById('inputLatP').value;
        var lng = document.getElementById('inputLngP').value;
        document.getElementById('hiddenLatP').value = lat;
        document.getElementById('hiddenLngP').value = lng;
    };

    $('#modalKoordinatPariwisata').on('shown.bs.modal', function() {
        setTimeout(function() {
            if (!miniMapP) {
                miniMapP = L.map('miniMapPariwisata').setView([-6.5718, 107.7600], 12);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap'
                }).addTo(miniMapP);

                miniMapP.on('click', function(e) {
                    pickedLatP = e.latlng.lat.toFixed(6);
                    pickedLngP = e.latlng.lng.toFixed(6);
                    if (pickedMarkerP) miniMapP.removeLayer(pickedMarkerP);
                    pickedMarkerP = L.marker(e.latlng).addTo(miniMapP)
                        .bindPopup('<b>📍 Lokasi Dipilih</b><br>Lat: ' + pickedLatP + '<br>Lng: ' + pickedLngP)
                        .openPopup();
                    document.getElementById('previewLatP').textContent = pickedLatP;
                    document.getElementById('previewLngP').textContent = pickedLngP;
                    document.getElementById('btnApplyP').disabled = false;
                });
            } else {
                miniMapP.invalidateSize();
            }
        }, 300);
    });

    window.applyPickedCoordP = function() {
        if (!pickedLatP || !pickedLngP) return;
        document.getElementById('inputLatP').value = pickedLatP;
        document.getElementById('inputLngP').value = pickedLngP;
        document.getElementById('hiddenLatP').value = pickedLatP;
        document.getElementById('hiddenLngP').value = pickedLngP;
        $('#modalKoordinatPariwisata').modal('hide');
    };
})();
