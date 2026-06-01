(function() {
    var t = localStorage.getItem('wanderMedTheme') || 'dark';
    if (t === 'dark') document.write('<style>body{background:#0f172a;color:#f1f5f9}</style>');
})();
