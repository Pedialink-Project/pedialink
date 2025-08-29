const menuTab = ".menu-link"

document.querySelectorAll(menuTab).forEach(link => {
    link.addEventListener("click", e => {
        const parent = link.closest(".tab");
        if (parent && parent.classList.contains("has-children")) {
            e.preventDefault();
            parent.classList.toggle("open");
        }
    });
});
