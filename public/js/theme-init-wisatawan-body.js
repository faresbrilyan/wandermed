(function() {
    var t = localStorage.getItem('wanderMedTheme') || 'dark';
    document.getElementById('appBody').className = t === 'dark' ? 'dark' : '';
})();
