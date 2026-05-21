const toggleDashboard = document.getElementById("toggle-dashboard");

toggleDashboard.addEventListener("change", function () {
    if (this.checked) {
        document.body.classList.add("sidebar-open");
    } else {
        document.body.classList.remove("sidebar-open");
    }
});

function dropdown() {
    document.querySelector("#submenu").classList.toggle("hidden");
    document.querySelector("#arrow").classList.toggle("rotate-0");
}
function toggleDropdown(submenuId, arrowId) {
    const submenu = document.getElementById(submenuId);
    const arrow = document.getElementById(arrowId);
    submenu.classList.toggle("hidden");
    arrow.classList.toggle("rotate-0");
}
