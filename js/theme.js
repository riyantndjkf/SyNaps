document.addEventListener("DOMContentLoaded", function () {

    const toggle = document.getElementById("toggleTheme");
    const savedTheme = localStorage.getItem("theme");

    // 1. Saat halaman dibuka
    if (savedTheme === "dark") {
        document.body.classList.add("dark");
        if (toggle) toggle.checked = true;
    }

    // 2. Saat toggle diklik
    if (toggle) {
        toggle.addEventListener("change", function () {

            if (this.checked) {
                document.body.classList.add("dark");
                localStorage.setItem("theme", "dark");
            } else {
                document.body.classList.remove("dark");
                localStorage.setItem("theme", "light");
            }

        });
    }
});
