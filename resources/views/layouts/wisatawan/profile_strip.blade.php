<div class="profile-strip">
    <div class="ps-avatar">{{ strtoupper(substr($user?->name ?? 'W', 0, 1)) }}</div>
    <div class="ps-info">
        <div class="ps-name">{{ $user?->name ?? 'Wisatawan' }}</div>
        <div class="ps-email">{{ $user?->email ?? '—' }}</div>
        <div class="ps-badge"><i class="fas fa-check-circle"></i> Wisatawan Aktif</div>
    </div>
    <div class="ps-quick-links">
        <a href="/peta-faskes" class="ps-link" title="Peta Faskes">
            <i class="fas fa-map-marked-alt"></i>
        </a>
        <button class="ps-link" onclick="switchTab('medis'); scrollToMain();" title="Rekam Medis">
            <i class="fas fa-notes-medical"></i>
        </button>
    </div>
</div>
