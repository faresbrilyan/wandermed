(function() {
    const savedTheme = localStorage.getItem("wanderMedTheme");
    if (savedTheme === "light") {
        document.documentElement.classList.add("light-mode");
        document.body.classList.add("light-mode");
    }
})();
