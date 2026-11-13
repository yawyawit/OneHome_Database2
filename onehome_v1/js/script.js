document.addEventListener('DOMContentLoaded', function () {
    // Open and close the administrator menu on smaller screens.
    var sidebar = document.getElementById('adminSidebar');
    var sidebarToggle = document.getElementById('sidebarToggle');
    var sidebarBackdrop = document.getElementById('sidebarBackdrop');

    function closeSidebar() {
        if (sidebar) sidebar.classList.remove('is-open');
        if (sidebarBackdrop) sidebarBackdrop.classList.remove('is-visible');
        if (sidebarToggle) sidebarToggle.setAttribute('aria-expanded', 'false');
    }

    if (sidebar && sidebarToggle && sidebarBackdrop) {
        sidebarToggle.addEventListener('click', function () {
            var isOpen = sidebar.classList.toggle('is-open');
            sidebarBackdrop.classList.toggle('is-visible', isOpen);
            sidebarToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });

        sidebarBackdrop.addEventListener('click', closeSidebar);
        sidebar.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', closeSidebar);
        });
    }

    // Register page: show employee fields only when "Employee" is chosen
    var role = document.getElementById('role');
    var emp = document.getElementById('emp-fields');
    if (role && emp) {
        var toggle = function () { emp.style.display = role.value === 'employee' ? 'block' : 'none'; };
        role.addEventListener('change', toggle);
        toggle();
    }
    // Ask "Are you sure?" on forms that have data-confirm
    document.querySelectorAll('form[data-confirm]').forEach(function (f) {
        f.addEventListener('submit', function (e) {
            if (!confirm(f.dataset.confirm)) e.preventDefault();
        });
    });
    // Format card number as 1234 5678 9012 3456
    var card = document.getElementById('card_number');
    if (card) {
        card.addEventListener('input', function () {
            var v = card.value.replace(/\D/g, '').substring(0, 16);
            card.value = v.replace(/(.{4})/g, '$1 ').trim();
        });
    }
});
