document.addEventListener("DOMContentLoaded", function() {

    var isLoggedIn = window.WanderMed.isLoggedIn;
    var isWisatawan = window.WanderMed.isWisatawan;
    var csrfToken = window.WanderMed.csrfToken;
    var currentFaskesId = null;
    var globalUserLocation = null;
    var userLocationMarker = null;

    // =========================================================
    // DATA MARKER
    // Diisi langsung dari Controller via compact()
    // =========================================================
    var faskesDataRaw = window.WanderMed.faskesDataRaw;
    var pariwisataDataRaw = window.WanderMed.pariwisataDataRaw;

    var faskesData = [];
    if (faskesDataRaw.length > 0) {
        faskesData = faskesDataRaw.map(f => ({
            id:       f.id,
            name:     f.name,
            lat:      f.lat,
            lng:      f.lng,
            type:     f.type,
            bpjs:     f.bpjs,
            status:   f.status,
            address:  f.address,
            phone:    f.phone,
            jam:      f.status === 'open' ? 'Sedang Buka' : 'Sedang Tutup',
            notes:    f.notes,
            jam_buka: f.jam_buka,
            jam_tutup: f.jam_tutup,
            is_24_jam: f.is_24_jam,
            ratingAvg: f.rating_avg || 0,
            ratingCount: f.rating_count || 0,
            facilities: (f.facilities || []).map(label => ({
                icon:  getFacilityIcon(label),
                ico:   getFacilityFaIcon(label),
                label: label
            })),
            jadwals: f.jadwals || []
        }));
    }

    var pariwisataData = pariwisataDataRaw;
    var wisataMarkers  = [];
    var activeMarkers  = [];

    // =========================================================
    // MARKER ICON untuk Pariwisata (ungu)
    // =========================================================
    function createWisataMarkerIcon() {
        return L.divIcon({
            className: '',
            html: `
                <div style="
                    width: 38px; height: 38px;
                    background: linear-gradient(135deg, #8B5CF6, #6D28D9);
                    border-radius: 50%;
                    display: flex; align-items: center; justify-content: center;
                    box-shadow: 0 4px 14px rgba(109,40,217,0.5);
                    border: 3px solid rgba(255,255,255,0.4);
                ">
                    <i class="fas fa-mountain" style="color: #fff; font-size: 13px;"></i>
                </div>`,
            iconSize: [38, 38],
            iconAnchor: [19, 19],
            popupAnchor: [0, -24]
        });
    }

    // Inisialisasi Peta
    var map = L.map('map', { zoomControl: false }).setView([-6.5710, 107.7587], 14);
    L.control.zoom({ position: 'bottomright' }).addTo(map);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);

    // --- Helper: Mapping nama layanan ke class ikon faskes-grid ---
    function getFacilityIcon(label) {
        const map = {
            'UGD 24 Jam': 'fac-icon red', 'Ambulans': 'fac-icon blue',
            'Rawat Inap': 'fac-icon teal', 'Apotek': 'fac-icon green',
            'Laboratorium': 'fac-icon yellow', 'Dok. Spesialis': 'fac-icon orange',
            'Poli Anak': 'fac-icon purple', 'Poli Gigi': 'fac-icon blue',
            'Poli Umum': 'fac-icon green', 'Imunisasi': 'fac-icon teal',
            'Poli Bedah': 'fac-icon yellow', 'Poli Penyakit Dalam': 'fac-icon orange',
            'Poli Kandungan': 'fac-icon purple',
        };
        return map[label] || 'fac-icon orange';
    }
    function getFacilityFaIcon(label) {
        const map = {
            'UGD 24 Jam': 'fas fa-ambulance', 'Ambulans': 'fas fa-car',
            'Rawat Inap': 'fas fa-bed', 'Apotek': 'fas fa-pills',
            'Laboratorium': 'fas fa-flask', 'Dok. Spesialis': 'fas fa-user-md',
            'Poli Anak': 'fas fa-baby', 'Poli Gigi': 'fas fa-tooth',
            'Poli Umum': 'fas fa-stethoscope', 'Imunisasi': 'fas fa-syringe',
            'Poli Bedah': 'fas fa-scissors', 'Poli Penyakit Dalam': 'fas fa-stethoscope',
            'Poli Kandungan': 'fas fa-female',
        };
        return map[label] || 'fas fa-clinic-medical';
    }


    // =========================================================
    // CUSTOM MARKER ICON per kategori Faskes
    // =========================================================
    function createMarkerIcon(type, is_24_jam = false) {
        const colors = {
            "Rumah Sakit": { bg: "#e74a3b", icon: "fa-hospital-alt" },
            "Klinik":      { bg: "#4e73df", icon: "fa-clinic-medical" },
            "Apotek":      { bg: "#1cc88a", icon: "fa-pills" },
            "Puskesmas":   { bg: "#f6c23e", icon: "fa-heartbeat" },
        };
        const c = colors[type] || { bg: "#ff7a00", icon: "fa-map-marker-alt" };

        const indicator24H = is_24_jam 
            ? `<div style="position:absolute; top:-5px; right:-5px; background:red; color:white; font-size:9px; font-weight:bold; padding:2px 4px; border-radius:10px; transform: rotate(45deg); box-shadow:0 2px 4px rgba(0,0,0,0.5); z-index:10;">24J</div>` 
            : ``;

        return L.divIcon({
            className: '',
            html: `
                <div style="position: relative;">
                    <div style="
                        width: 40px; height: 40px;
                        background: ${c.bg};
                        border-radius: 50% 50% 50% 0;
                        transform: rotate(-45deg);
                        display: flex; align-items: center; justify-content: center;
                        box-shadow: 0 4px 14px rgba(0,0,0,0.4);
                        border: 3px solid rgba(255,255,255,0.3);
                        position: relative;
                    ">
                        <i class="fas ${c.icon}" style="transform: rotate(45deg); color: #fff; font-size: 14px;"></i>
                        ${indicator24H}
                    </div>
                </div>`,
            iconSize: [40, 40],
            iconAnchor: [20, 40],
            popupAnchor: [0, -45]
        });
    }

    // =========================================================
    // RENDER MARKER & FILTERING
    // =========================================================
    function matchFaskesSmart(f, searchQuery) {
        if (!searchQuery) return true;
        
        const inNameOrAddress = f.name.toLowerCase().includes(searchQuery) || f.address.toLowerCase().includes(searchQuery);
        
        let inType = f.type.toLowerCase().includes(searchQuery);
        if (searchQuery === 'rs') {
            inType = inType || f.type.toLowerCase() === 'rumah sakit';
        }

        let in24Jam = false;
        if (searchQuery.includes('24') || searchQuery.includes('buka 24') || searchQuery.includes('24 jam') || searchQuery.includes('24j')) {
            in24Jam = f.is_24_jam;
        }

        let inJadwal = false;
        if (f.jadwals && f.jadwals.length > 0) {
            inJadwal = f.jadwals.some(j => {
                const docName = j.dokter ? j.dokter.toLowerCase() : '';
                const spec = j.spesialisasi ? j.spesialisasi.toLowerCase() : '';
                
                let hariList = '';
                if (Array.isArray(j.hari)) {
                    hariList = j.hari.map(h => h.toLowerCase()).join(' ');
                } else if (typeof j.hari === 'string') {
                    hariList = j.hari.toLowerCase();
                }
                
                return docName.includes(searchQuery) || 
                       spec.includes(searchQuery) || 
                       hariList.includes(searchQuery);
            });
        }

        return inNameOrAddress || inType || in24Jam || inJadwal;
    }

    function updateMarkers() {
        // Bersihkan marker sebelumnya
        activeMarkers.forEach(m => map.removeLayer(m));
        activeMarkers = [];
        wisataMarkers.forEach(m => map.removeLayer(m));
        wisataMarkers = [];

        const masterFilter = document.getElementById('masterFilter').value; // 'All', 'Faskes', 'Pariwisata'
        const activeChip = document.querySelector('.filter-chip.active');
        const typeFilter  = activeChip ? activeChip.dataset.type : 'AllFaskes';
        const bpjsActive  = document.getElementById('bpjsChip').classList.contains('active');
        const searchQuery = document.getElementById('searchInput').value.toLowerCase().trim();

        // Toggle subfilter UI
        const faskesSubFilter = document.getElementById('faskesSubFilter');
        if (masterFilter === 'Pariwisata') {
            faskesSubFilter.style.display = 'none';
        } else {
            faskesSubFilter.style.display = 'flex';
        }

        let count = 0;
        let bounds = [];

        // 1. Render Faskes
        if (masterFilter === 'All' || masterFilter === 'Faskes') {
            faskesData.forEach(f => {
                const matchType   = (typeFilter === 'AllFaskes' || f.type === typeFilter);
                const matchBPJS   = (!bpjsActive || f.bpjs);
                const matchSearch = matchFaskesSmart(f, searchQuery);

                if (matchType && matchBPJS && matchSearch) {
                    const marker = L.marker([f.lat, f.lng], { icon: createMarkerIcon(f.type, f.is_24_jam) }).addTo(map);

                    marker.bindTooltip(`<b style="font-size:13px;">${f.name}</b><br><small style="color:rgba(0,0,0,0.5)">${f.type}</small>`, {
                        direction: 'top', offset: [0, -45], className: 'wm-tooltip'
                    });

                    marker.on('click', function () {
                        showDetail(f);
                        map.flyTo([f.lat, f.lng], 16, { duration: 1 });
                    });

                    activeMarkers.push(marker);
                    bounds.push([f.lat, f.lng]);
                    count++;
                }
            });
        }

        // 2. Render Pariwisata
        if (masterFilter === 'All' || masterFilter === 'Pariwisata') {
            pariwisataData.forEach(w => {
                if (!w.lat || !w.lng) return;
                
                const matchSearch = !searchQuery || w.name.toLowerCase().includes(searchQuery) || (w.kategori && w.kategori.toLowerCase().includes(searchQuery));
                
                if (matchSearch) {
                    const marker = L.marker([w.lat, w.lng], { icon: createWisataMarkerIcon() }).addTo(map);

                    marker.bindTooltip(`<b style="font-size:13px;">${w.name}</b><br><small style="color:#8B5CF6;">${w.kategori}</small>`, {
                        direction: 'top', offset: [0, -24], className: 'wm-tooltip'
                    });

                    marker.on('click', function() {
                        map.flyTo([w.lat, w.lng], 14, { duration: 1 });
                        showWisataDetail(w);
                    });

                    wisataMarkers.push(marker);
                    bounds.push([w.lat, w.lng]);
                    count++;
                }
            });
        }

        document.getElementById('resultCount').textContent = count;
        
        // Auto-zoom jika kategori Pariwisata dipilih (Berdasarkan instruksi)
        if (masterFilter === 'Pariwisata' && bounds.length > 0) {
            map.fitBounds(bounds, { padding: [50, 50], maxZoom: 14 });
        }
    }

    // Listener Master Filter
    document.getElementById('masterFilter').addEventListener('change', function() {
        closeDetail();
        updateMarkers();
    });

    // =========================================================
    // TAMPILKAN DETAIL FASKES DI BOTTOM SHEET
    // =========================================================
    window.showDetail = function(data) {
        // Isi data dasar
        document.getElementById('detailName').textContent    = data.name;
        document.getElementById('detailType').textContent    = data.type;
        document.getElementById('detailAddress').textContent = data.address;
        document.getElementById('detailJam').textContent     = data.jam;
        document.getElementById('detailPhone').textContent   = data.phone;

        // Rating
        const ratingContainer = document.getElementById('detailRatingContainer');
        if (data.ratingCount > 0) {
            ratingContainer.style.display = 'flex';
            document.getElementById('detailRatingAvg').textContent = Number(data.ratingAvg).toFixed(1);
            document.getElementById('detailRatingCount').textContent = `(${data.ratingCount})`;
        } else {
            ratingContainer.style.display = 'none';
        }

        // Status visual jam
        const rowJam = document.getElementById('rowJam');
        if (data.status === 'open') {
            rowJam.classList.add('jam-open');
            rowJam.querySelector('i').className = 'fas fa-clock';
        } else {
            rowJam.classList.remove('jam-open');
        }

        // Logika Jam Operasional Dinamis
        let isOpen = false;
        let jamText = "";
        
        if (data.is_24_jam) {
            isOpen = true;
            jamText = "Buka 24 Jam";
        } else if (data.jam_buka && data.jam_tutup) {
            const now = new Date();
            const currentTime = now.getHours() * 60 + now.getMinutes();
            const [bukaH, bukaM] = data.jam_buka.split(':').map(Number);
            const [tutupH, tutupM] = data.jam_tutup.split(':').map(Number);
            const bukaTime = bukaH * 60 + bukaM;
            const tutupTime = tutupH * 60 + tutupM;
            
            if (tutupTime < bukaTime) {
                // Kasus tutup lewat tengah malam (misal 08:00 - 02:00)
                isOpen = currentTime >= bukaTime || currentTime <= tutupTime;
            } else {
                isOpen = currentTime >= bukaTime && currentTime <= tutupTime;
            }
            jamText = `${data.jam_buka} - ${data.jam_tutup}`;
        } else {
            // Fallback ke manual
            isOpen = data.status === 'open';
            jamText = isOpen ? "Buka Sekarang" : "Sekarang Tutup";
        }

        const badgesEl = document.getElementById('detailBadges');
        const statusChip = isOpen
            ? `<span class="info-chip status-open"><i class="fas fa-circle" style="font-size:8px;"></i> Buka Sekarang ${jamText !== "Buka Sekarang" ? `(${jamText})` : ''}</span>`
            : `<span class="info-chip status-closed"><i class="fas fa-circle" style="font-size:8px;"></i> Sekarang Tutup</span>`;
        const bpjsChip = data.bpjs
            ? `<span class="info-chip bpjs-yes"><i class="fas fa-shield-alt"></i> Terima BPJS</span>`
            : `<span class="info-chip bpjs-no"><i class="fas fa-times-circle"></i> Non-BPJS</span>`;
            
        let distText = "Jarak tidak diketahui";
        if (globalUserLocation) {
            const distKm = getDistanceFromLatLonInKm(globalUserLocation.lat, globalUserLocation.lng, data.lat, data.lng);
            distText = distKm < 1 ? `~${Math.round(distKm * 1000)}m dari Anda` : `~${distKm.toFixed(1)}km dari Anda`;
        }
        badgesEl.innerHTML = statusChip + bpjsChip + `<span class="info-chip chip-distance"><i class="fas fa-route"></i> ${distText}</span>`;

        // Fasilitas
        document.getElementById('detailFacilities').innerHTML = data.facilities.map(f => `
            <div class="facility-item">
                <div class="${f.icon}"><i class="${f.ico}"></i></div>
                ${f.label}
            </div>`).join('');

        // Jadwal & Kontak — Tampilkan untuk faskes, sembunyikan deteksi wisata
        const btnJadwal = document.getElementById('btnJadwal');
        const btnDeteksi = document.getElementById('btnDeteksiFaskes');
        btnJadwal.style.display = 'block';
        btnJadwal.href = `/faskes/${data.id}/jadwal`;
        btnDeteksi.style.display = 'none';  // Bukan wisata, tombol ini harus tersembunyi
        btnDeteksi.onclick = null;
        document.getElementById('btnCall').href = `tel:${data.phone.replace(/\D/g, '')}`;

        // Notes
        const notesSection = document.getElementById('rowNotes');
        if (data.notes) {
            notesSection.style.display = 'block';
            document.getElementById('detailNotes').textContent = data.notes;
        } else {
            notesSection.style.display = 'none';
        }

        // Navigasi
        document.getElementById('btnDirection').onclick = function(e) {
            e.preventDefault();
            openSmartNavigation(data.lat, data.lng, data.id, data.name, data.type);
        };

        // Reset ke Mode Detail
        backToDetail();

        // Buka panel
        document.getElementById('faskesDetailPanel').classList.add('active');
    };

    window.closeDetail = function() {
        document.getElementById('faskesDetailPanel').classList.remove('active');
    };

    // =========================================================
    // SMART NAVIGATION & REVIEW TRANSITION
    // =========================================================
    window.openSmartNavigation = function(destLat, destLng, id, name, type) {
        const btn = document.getElementById('btnDirection');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Membuka Rute...';

        const mapUrl = `https://www.google.com/maps/dir/?api=1&destination=${destLat},${destLng}&travelmode=motorcycle`;

        // Redirect & Switch Mode
        window.open(mapUrl, '_blank');
        
        setTimeout(function() {
            btn.innerHTML = originalText;
            switchToReviewMode(id, name);
        }, 800);
    };

    function switchToReviewMode(id, name) {
        currentFaskesId = id;
        document.getElementById('detailContentBlock').style.display = 'none';
        document.getElementById('detailActions').style.display = 'none';
        document.getElementById('reviewContentBlock').style.display = 'block';
        document.getElementById('reviewActions').style.display = 'flex';
        
        // Reset form
        document.getElementById('reviewRating').value = '0';
        document.getElementById('reviewKomentar').value = '';
        setStarColor(0);
        
        // Scroll body ke atas agar form terlihat
        document.querySelector('.panel-body').scrollTop = 0;
    }

    window.backToDetail = function() {
        document.getElementById('detailContentBlock').style.display = 'block';
        document.getElementById('detailActions').style.display = 'flex';
        document.getElementById('reviewContentBlock').style.display = 'none';
        document.getElementById('reviewActions').style.display = 'none';
    };

    // =========================================================
    // REVIEW SYSTEM LOGIC
    // =========================================================
    const RATING_LABELS = ['', 'Sangat Buruk 😞', 'Buruk 😕', 'Cukup 😐', 'Baik 😊', 'Sangat Baik ⭐'];

    function setStarColor(val) {
        document.querySelectorAll('.review-star').forEach(function(s) {
            var sv = parseInt(s.getAttribute('data-val'));
            if (sv <= val) {
                s.style.color = ''; // Remove inline color to allow CSS to take over
                s.classList.add('star-active');
                s.style.transform = 'scale(1.2)';
            } else {
                s.style.color = ''; // Remove inline color
                s.classList.remove('star-active');
                s.style.transform = 'scale(1)';
            }
        });
        var lbl = document.getElementById('starLabel');
        if (lbl) lbl.textContent = RATING_LABELS[val] || '';
    }

    document.querySelectorAll('.review-star').forEach(function(star) {
        star.addEventListener('click', function() {
            var val = parseInt(this.getAttribute('data-val'));
            document.getElementById('reviewRating').value = val;
            setStarColor(val);
        });
        star.addEventListener('mouseenter', function() {
            setStarColor(parseInt(this.getAttribute('data-val')));
        });
        star.addEventListener('mouseleave', function() {
            setStarColor(parseInt(document.getElementById('reviewRating').value) || 0);
        });
    });

    window.submitReview = function() {
        if (!isLoggedIn) {
            Swal.fire({
                title: 'Login Diperlukan',
                text: 'Silakan login sebagai wisatawan untuk memberikan ulasan.',
                icon: 'warning',
                confirmButtonText: 'Login Sekarang',
                showCancelButton: true,
                confirmButtonColor: '#ff7a00'
            }).then(function(r) { if (r.isConfirmed) window.location.href = '/login'; });
            return;
        }
        if (!isWisatawan) {
            Swal.fire({
                title: 'Akses Ditolak',
                text: 'Hanya akun wisatawan yang dapat memberikan ulasan.',
                icon: 'info',
                confirmButtonColor: '#ff7a00'
            });
            return;
        }

        var rating = parseInt(document.getElementById('reviewRating').value);
        var komentar = document.getElementById('reviewKomentar').value.trim();

        if (rating < 1) {
            Swal.fire('Rating Kosong', 'Silakan klik pada bintang untuk memberi skor.', 'warning');
            return;
        }
        if (!komentar) {
            Swal.fire('Ulasan Kosong', 'Silakan tuliskan komentar pengalaman Anda.', 'warning');
            return;
        }

        var btn = document.getElementById('btnSubmitReview');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mengirim...';
        btn.disabled = true;

        fetch('/faskes/' + currentFaskesId + '/ulasan', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ rating: rating, komentar: komentar })
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.success) {
                Swal.fire({
                    title: 'Berhasil! 🎉',
                    text: 'Terima kasih atas ulasan Anda.',
                    icon: 'success',
                    confirmButtonColor: '#28a745'
                }).then(function() { closeDetail(); });
            } else {
                Swal.fire('Gagal', data.message || 'Terjadi kesalahan.', 'error');
                btn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i> Kirim Ulasan';
                btn.disabled = false;
            }
        })
        .catch(function() {
            Swal.fire('Error', 'Masalah koneksi database.', 'error');
            btn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i> Kirim Ulasan';
            btn.disabled = false;
        });
    };

    // =========================================================
    // BAGIKAN LOKASI (Web Share API)
    // =========================================================
    window.shareLocation = function() {
        const name = document.getElementById('detailName').textContent;
        const link = document.getElementById('btnDirection').href;
        if (navigator.share) {
            navigator.share({ title: name, text: `Cek faskes ini di WanderMed: ${name}`, url: link });
        } else {
            navigator.clipboard.writeText(link).then(() => alert('Link lokasi berhasil disalin!'));
        }
    };

    // =========================================================
    // FILTER EVENT LISTENERS
    // =========================================================
    // Filter chip kategori
    document.querySelectorAll('.filter-chip').forEach(chip => {
        chip.addEventListener('click', function() {
            document.querySelector('.filter-chip.active').classList.remove('active');
            this.classList.add('active');
            closeDetail();
            updateMarkers();
        });
    });

    // Chip BPJS toggle
    document.getElementById('bpjsChip').addEventListener('click', function() {
        this.classList.toggle('active');
        this.classList.toggle('inactive');
        closeDetail();
        updateMarkers();
    });
    // Set BPJS chip awalnya inactive
    document.getElementById('bpjsChip').classList.add('inactive');

    // Input pencarian & Autocomplete Dropdown
    document.getElementById('searchInput').addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        closeDetail();
        updateMarkers();

        const dropdown = document.getElementById('searchResultsDropdown');
        dropdown.innerHTML = '';
        
        if (query.length < 2) {
            dropdown.style.display = 'none';
            return;
        }

        let results = [];
        
        // Cari Faskes
        faskesData.forEach(f => {
            if (matchFaskesSmart(f, query)) {
                results.push({ ...f, isWisata: false });
            }
        });

        // Cari Wisata
        pariwisataData.forEach(w => {
            if (w.name.toLowerCase().includes(query) || (w.kategori && w.kategori.toLowerCase().includes(query))) {
                results.push({ ...w, isWisata: true });
            }
        });

        if (results.length === 0) {
            dropdown.innerHTML = '<div class="search-no-result"><i class="fas fa-search-minus mr-2"></i> Pencarian tidak ditemukan.</div>';
            dropdown.style.display = 'block';
            return;
        }

        // Ambil maksimal 8 hasil
        results.slice(0, 8).forEach(item => {
            const div = document.createElement('div');
            div.className = 'search-dropdown-item';
            
            const iconClass = item.isWisata ? 'fa-mountain' : (item.type === 'Rumah Sakit' ? 'fa-hospital-alt' : (item.type === 'Apotek' ? 'fa-pills' : 'fa-clinic-medical'));
            const typeLabel = item.isWisata ? `Pariwisata (${item.kategori || 'Alam'})` : item.type;

            div.innerHTML = `
                <div class="search-item-icon"><i class="fas ${iconClass}"></i></div>
                <div class="search-item-info">
                    <div class="search-item-name">${item.name}</div>
                    <div class="search-item-type">${typeLabel}</div>
                </div>
            `;
            
            div.addEventListener('click', function() {
                // Sembunyikan dropdown dan isi input
                dropdown.style.display = 'none';
                document.getElementById('searchInput').value = item.name;
                
                // Setel filter master sesuai tipe item
                if (item.isWisata) {
                    document.getElementById('masterFilter').value = 'Pariwisata';
                } else {
                    document.getElementById('masterFilter').value = 'Faskes';
                }
                updateMarkers();

                // Terbang ke lokasi
                map.flyTo([item.lat, item.lng], 16, { duration: 1.5 });
                
                // Tampilkan detail (beri sedikit jeda agar animasi zoom terlihat enak)
                setTimeout(() => {
                    if (item.isWisata) {
                        showWisataDetail(item);
                    } else {
                        showDetail(item);
                    }
                }, 800);
            });

            dropdown.appendChild(div);
        });

        dropdown.style.display = 'block';
    });

    // Hilangkan dropdown saat klik di luar
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.search-input-group') && !e.target.closest('.search-dropdown-list')) {
            const dropdown = document.getElementById('searchResultsDropdown');
            if (dropdown) dropdown.style.display = 'none';
        }
    });

    // Tampilkan lagi dropdown jika input difokuskan dan ada isinya
    document.getElementById('searchInput').addEventListener('focus', function() {
        if (this.value.trim().length >= 2) {
            // Trigger input event to re-render dropdown
            this.dispatchEvent(new Event('input'));
        }
    });

    // Tombol Reset Filter
    const btnReset = document.getElementById('btnResetFilter');
    if (btnReset) {
        btnReset.addEventListener('click', function() {
            // Reset Search
            document.getElementById('searchInput').value = '';
            document.getElementById('searchResultsDropdown').style.display = 'none';

            // Reset Master Filter
            document.getElementById('masterFilter').value = 'All';

            // Reset Chips
            document.querySelectorAll('.filter-chip').forEach(c => c.classList.remove('active'));
            const allChip = document.querySelector('.filter-chip[data-type="AllFaskes"]');
            if (allChip) allChip.classList.add('active');

            // Reset BPJS
            const bpjsChip = document.getElementById('bpjsChip');
            if (bpjsChip) {
                bpjsChip.classList.remove('active');
                bpjsChip.classList.add('inactive');
            }

            closeDetail();
            updateMarkers();

            // Kembalikan peta ke posisi awal atau lokasi user
            if (globalUserLocation) {
                map.flyTo([globalUserLocation.lat, globalUserLocation.lng], 14, { duration: 1.5 });
            } else {
                map.flyTo([-6.5710, 107.7587], 14, { duration: 1.5 });
            }
        });
    }

    // =========================================================
    // GEOFENCING LOGIC (HAVERSINE)
    // =========================================================
    function getDistanceFromLatLonInKm(lat1, lon1, lat2, lon2) {
        var R = 6371; // Radius of the earth in km
        var dLat = deg2rad(lat2-lat1);
        var dLon = deg2rad(lon2-lon1); 
        var a = 
            Math.sin(dLat/2) * Math.sin(dLat/2) +
            Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * 
            Math.sin(dLon/2) * Math.sin(dLon/2); 
        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
        var d = R * c; // Distance in km
        return d;
    }

    function deg2rad(deg) {
        return deg * (Math.PI/180);
    }

    window.closeGeofenceModal = function() {
        document.getElementById('geofenceModal').classList.remove('active');
    };

    window.focusFaskesFromModal = function(id, lat, lng) {
        closeGeofenceModal();
        map.flyTo([lat, lng], 16, { duration: 1.5 });
        const f = faskesData.find(item => item.id === id);
        if (f) {
            setTimeout(() => {
                showDetail(f);
            }, 800);
        }
    };

    function checkGeofence(userLat, userLng) {
        if (pariwisataData.length === 0) return;

        let nearestWisata = null;
        let minWisataDist = Infinity;

        // 1. Cari wisata terdekat
        pariwisataData.forEach(w => {
            if(!w.lat || !w.lng) return;
            let dist = getDistanceFromLatLonInKm(userLat, userLng, w.lat, w.lng);
            if(dist < minWisataDist) {
                minWisataDist = dist;
                nearestWisata = w;
            }
        });

        // 2. Jika dalam radius 1km (1.0), trigger modal
        if (nearestWisata && minWisataDist <= 1.0) {
            document.getElementById('gfWisataName').textContent = nearestWisata.name;
            
            // 3. Cari 3 faskes terdekat dari user/wisata
            let faskesWithDist = faskesData.map(f => {
                return {
                    ...f,
                    dist: getDistanceFromLatLonInKm(userLat, userLng, f.lat, f.lng)
                };
            }).filter(f => f.lat && f.lng); // pastikan ada kordinat
            
            // Sort dari yang terdekat
            faskesWithDist.sort((a, b) => a.dist - b.dist);
            
            // Ambil top 3
            let top3 = faskesWithDist.slice(0, 3);
            
            // Render HTML List Faskes
            let listHtml = '';
            top3.forEach(f => {
                let distMeters = Math.round(f.dist * 1000);
                let distText = distMeters > 1000 ? (f.dist).toFixed(1) + ' km' : distMeters + ' m';
                
                let mapsLink = `https://www.google.com/maps/dir/?api=1&destination=${f.lat},${f.lng}`;

                listHtml += `
                    <div class="gm-item">
                        <div class="gm-item-icon">
                            <i class="fas ${f.type === 'Rumah Sakit' ? 'fa-hospital-alt' : (f.type === 'Apotek' ? 'fa-pills' : 'fa-clinic-medical')}"></i>
                        </div>
                        <div class="gm-item-info">
                            <div class="gm-item-name">${f.name}</div>
                            <div class="gm-item-dist"><i class="fas fa-route"></i> ${distText}</div>
                        </div>
                        <button onclick="focusFaskesFromModal(${f.id}, ${f.lat}, ${f.lng})" class="gm-item-btn-show">Lihat</button>
                        <a href="${mapsLink}" onclick="event.preventDefault(); openSmartNavigation(${f.lat}, ${f.lng});" class="gm-item-btn">Rute</a>
                    </div>
                `;
            });

            document.getElementById('gfFaskesList').innerHTML = listHtml;

            // Munculkan Modal
            setTimeout(() => {
                document.getElementById('geofenceModal').classList.add('active');
            }, 800);
        }
    }

    // =========================================================
    // TOMBOL LOKASI SAYA (Geolocation) & AUTO CHECK
    // =========================================================
    function locateUser(isAuto = false) {
        if (!navigator.geolocation) {
            if(!isAuto) alert('Browser Anda tidak mendukung geolocation.');
            return;
        }
        
        const btn = document.getElementById('btnMyLocation');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mencari...';
        
        navigator.geolocation.getCurrentPosition(
            pos => {
                const { latitude: lat, longitude: lng } = pos.coords;
                globalUserLocation = { lat, lng };
                map.flyTo([lat, lng], 15, { duration: 1.5 });

                // Tandai posisi pengguna
                if (userLocationMarker) {
                    map.removeLayer(userLocationMarker);
                }
                userLocationMarker = L.circleMarker([lat, lng], {
                    radius: 10, color: '#ff7a00', fillColor: '#ff7a00', fillOpacity: 0.5, weight: 3
                }).addTo(map).bindTooltip('<b>📍 Lokasi Anda</b>', { permanent: false });

                btn.innerHTML = '<i class="fas fa-crosshairs"></i> <span>Lokasi Saya</span>';

                // Jalankan Geofencing Check
                checkGeofence(lat, lng);
            },
            err => {
                if(!isAuto) alert('Gagal mendapatkan lokasi. Pastikan izin lokasi browser aktif.');
                btn.innerHTML = '<i class="fas fa-crosshairs"></i> <span>Lokasi Saya</span>';
            }
        );
    }

    document.getElementById('btnMyLocation').addEventListener('click', function() {
        locateUser(false);
    });

    // =========================================================
    // DETEKSI 3 FASKES TERDEKAT UTK USER SHORTCUT
    // =========================================================
    function detect3NearestFaskes(lat, lng) {
        if (faskesData.length === 0) return;

        document.getElementById('gfWisataName').innerHTML = '<i class="fas fa-street-view mr-2" style="color: var(--hnb-orange);"></i> Lokasi Anda Sekarang';
        
        let faskesWithDist = faskesData.map(f => {
            return {
                ...f,
                dist: getDistanceFromLatLonInKm(lat, lng, f.lat, f.lng)
            };
        }).filter(f => f.lat && f.lng);
        
        faskesWithDist.sort((a, b) => a.dist - b.dist);
        let top3 = faskesWithDist.slice(0, 3);
        
        let listHtml = '';
        top3.forEach(f => {
            let distMeters = Math.round(f.dist * 1000);
            let distText = distMeters > 1000 ? (f.dist).toFixed(1) + ' km' : distMeters + ' m';
            
            let mapsLink = `https://www.google.com/maps/dir/?api=1&destination=${f.lat},${f.lng}`;

            listHtml += `
                <div class="gm-item">
                    <div class="gm-item-icon">
                        <i class="fas ${f.type === 'Rumah Sakit' ? 'fa-hospital-alt' : (f.type === 'Apotek' ? 'fa-pills' : 'fa-clinic-medical')}"></i>
                    </div>
                    <div class="gm-item-info">
                        <div class="gm-item-name">${f.name}</div>
                        <div class="gm-item-dist"><i class="fas fa-route"></i> ${distText}</div>
                    </div>
                    <button onclick="focusFaskesFromModal(${f.id}, ${f.lat}, ${f.lng})" class="gm-item-btn-show">Lihat</button>
                    <a href="${mapsLink}" onclick="event.preventDefault(); openSmartNavigation(${f.lat}, ${f.lng});" class="gm-item-btn">Rute</a>
                </div>
            `;
        });

        document.getElementById('gfFaskesList').innerHTML = listHtml;
        
        setTimeout(() => {
            document.getElementById('geofenceModal').classList.add('active');
        }, 150);
        
        closeDetail();
    }

    const btnNearbyShortcut = document.getElementById('btnNearbyShortcut');
    if (btnNearbyShortcut) {
        btnNearbyShortcut.addEventListener('click', function() {
            const btn = this;
            const originalHTML = btn.innerHTML;
            
            if (globalUserLocation) {
                detect3NearestFaskes(globalUserLocation.lat, globalUserLocation.lng);
            } else {
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                
                if (!navigator.geolocation) {
                    alert('Browser Anda tidak mendukung geolocation.');
                    btn.innerHTML = originalHTML;
                    return;
                }
                
                navigator.geolocation.getCurrentPosition(
                    pos => {
                        const { latitude: lat, longitude: lng } = pos.coords;
                        globalUserLocation = { lat, lng };
                        
                        if (userLocationMarker) {
                            map.removeLayer(userLocationMarker);
                        }

                        userLocationMarker = L.circleMarker([lat, lng], {
                            radius: 10, color: '#ff7a00', fillColor: '#ff7a00', fillOpacity: 0.5, weight: 3
                        }).addTo(map).bindTooltip('<b>📍 Lokasi Anda</b>', { permanent: false });
                        
                        btn.innerHTML = originalHTML;
                        map.flyTo([lat, lng], 15, { duration: 1.5 });
                        
                        setTimeout(() => {
                            detect3NearestFaskes(lat, lng);
                        }, 1200);
                    },
                    err => {
                        alert('Gagal mendapatkan lokasi. Pastikan izin lokasi browser aktif.');
                        btn.innerHTML = originalHTML;
                    }
                );
            }
        });
    }

    // =========================================================
    // INISIALISASI PERTAMA
    // =========================================================
    updateMarkers();
    
    // Auto trigger pencarian lokasi saat web dibuka
    setTimeout(() => {
        locateUser(true);
    }, 500);

    // Tampilkan panel detail wisata (reuse bottom sheet atau popup sederhana)
    function showWisataDetail(w) {
        // Pakai bottom panel yang sama dengan faskes tapi header ungu
        const name    = document.getElementById('detailName');
        const type    = document.getElementById('detailType');
        const address = document.getElementById('detailAddress');
        const phone   = document.getElementById('detailPhone');
        const badges  = document.getElementById('detailBadges');
        const fac     = document.getElementById('detailFacilities');
        const notes   = document.getElementById('rowNotes');

        name.textContent    = w.name;
        type.textContent    = '🏔 ' + w.kategori;
        address.textContent = w.alamat;
        phone.textContent   = w.telp || '-';
        document.getElementById('detailJam').textContent = 'Harga tiket: ' + (w.tiket ? 'Rp ' + parseInt(w.tiket).toLocaleString('id-ID') : 'Gratis');

        // Status UI
        document.getElementById('rowJam').classList.remove('jam-open');
        document.getElementById('rowJam').querySelector('i').className = 'fas fa-ticket-alt';

        const badgesEl = document.getElementById('detailBadges');
        badgesEl.innerHTML = `<span class="info-chip" style="background:rgba(139, 92, 246, 0.2); color:#C4B5FD;"><i class="fas fa-camera"></i> Pariwisata</span>`;

        // Modul Foto
        const photoContainer = document.getElementById('headerPhotoContainer');
        const photoImg = document.getElementById('detailPhoto');
        if (w.foto) {
             photoContainer.style.display = 'block';
             photoImg.src = w.foto;
        } else {
             photoContainer.style.display = 'none';
        }
        
        // Sembunyikan Fasilitas
        document.getElementById('sectionFasilitas').style.display = 'none';

        if (w.deskripsi) {
            notes.style.display = 'block';
            document.getElementById('detailNotes').textContent = w.deskripsi;
        } else {
            notes.style.display = 'none';
        }

        // Reset state panel ke mode detail
        document.getElementById('detailContentBlock').style.display = 'block';
        document.getElementById('reviewContentBlock').style.display = 'none';
        document.getElementById('detailActions').style.display = 'flex';
        document.getElementById('reviewActions').style.display = 'none';

        // Tombol aksi: Sembunyikan jadwal, tampilkan deteksi faskes terdekat
        const btnJadwalW = document.getElementById('btnJadwal');
        const btnDeteksiW = document.getElementById('btnDeteksiFaskes');
        btnJadwalW.style.display = 'none';   // Bukan faskes, jadwal tidak relevan
        btnJadwalW.href = '#';
        btnDeteksiW.style.display = 'block';
        btnDeteksiW.onclick = function(e) {
            e.preventDefault();
            triggerManualGeofence(w.lat, w.lng, w.name);
        };

        document.getElementById('btnDirection').href = `https://www.google.com/maps/dir/?api=1&destination=${w.lat},${w.lng}`;
        document.getElementById('btnDirection').onclick = function(e) {
            e.preventDefault();
            openSmartNavigation(w.lat, w.lng, w.id, w.name, 'Pariwisata');
        };
        document.getElementById('btnCall').href = `tel:${(w.telp || '').replace(/\D/g, '')}`;
        document.getElementById('faskesDetailPanel').classList.add('active');
    }

    // =========================================================
    // MANUAL DETEKSI FASKES DARI PARIWISATA
    // =========================================================
    window.triggerManualGeofence = function(lat, lng, wisataName) {
        if (faskesData.length === 0) return;

        document.getElementById('gfWisataName').textContent = wisataName;
        
        let faskesWithDist = faskesData.map(f => {
            return {
                ...f,
                dist: getDistanceFromLatLonInKm(lat, lng, f.lat, f.lng)
            };
        }).filter(f => f.lat && f.lng);
        
        faskesWithDist.sort((a, b) => a.dist - b.dist);
        let top3 = faskesWithDist.slice(0, 3);
        
        let listHtml = '';
        top3.forEach(f => {
            let distMeters = Math.round(f.dist * 1000);
            let distText = distMeters > 1000 ? (f.dist).toFixed(1) + ' km' : distMeters + ' m';
            let mapsLink = `https://www.google.com/maps/dir/?api=1&destination=${f.lat},${f.lng}`;

            listHtml += `
                <div class="gm-item">
                    <div class="gm-item-icon">
                        <i class="fas ${f.type === 'Rumah Sakit' ? 'fa-hospital-alt' : (f.type === 'Apotek' ? 'fa-pills' : 'fa-clinic-medical')}"></i>
                    </div>
                    <div class="gm-item-info">
                        <div class="gm-item-name">${f.name}</div>
                        <div class="gm-item-dist"><i class="fas fa-route"></i> ${distText}</div>
                    </div>
                    <button onclick="focusFaskesFromModal(${f.id}, ${f.lat}, ${f.lng})" class="gm-item-btn-show">Lihat</button>
                    <a href="${mapsLink}" onclick="event.preventDefault(); openSmartNavigation(${f.lat}, ${f.lng});" class="gm-item-btn">Rute</a>
                </div>
            `;
        });

        document.getElementById('gfFaskesList').innerHTML = listHtml;
        document.getElementById('geofenceModal').classList.add('active');
        
        // Pindahkan peta ke lokasi pariwisata agar relevan secara visual
        map.flyTo([lat, lng], 15, { duration: 1.5 });
        closeDetail();
    };
});