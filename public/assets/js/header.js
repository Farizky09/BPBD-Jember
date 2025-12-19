document.addEventListener("DOMContentLoaded", function () {
    const path = window.location.pathname;
    const menuList = document.getElementById("menu-list");
    if (!menuList) return;
    menuList.innerHTML = "";

    switch (true) {
        case path === "/lapor":
            createLaporMenu(menuList);
            break;
        case path === "/detaildisaster":
            createDetailDisasterMenu(menuList);
            break;
        case path === "/edukasi-bencana":
            createEdukasiMenu(menuList);
            break;
        case path.startsWith("/berita/"):
            createBeritaMenu(menuList, path);
            break;
        default:
          
            break;
    }
});

function createMenuItem(text, href, classes = "") {
    const item = document.createElement("li");
    item.innerHTML = `<a href="${href}" class="${classes}">${text}</a>`;
    return item;
}

function createLaporMenu(menuList) {
    menuList.appendChild(createMenuItem("Lapor Bencana", "#"));
    addCommonMenuItems(menuList);
}

function createDetailDisasterMenu(menuList) {
    menuList.appendChild(
        createMenuItem(
            "Detail Disaster",
            "#",
            "text-white hover:text-blue-200 transition-colors duration-200 py-2 px-3 rounded-md"
        )
    );
    addCommonMenuItems(menuList, true);
}

function createEdukasiMenu(menuList) {
    menuList.appendChild(
        createMenuItem(
            "Edukasi Bencana",
            "#",
            "text-white hover:text-blue-200 transition-colors duration-200 py-2 px-3 rounded-md"
        )
    );
    addCommonMenuItems(menuList, true);
}

function createBeritaMenu(menuList, path) {
    const slug = path.split("/").pop();
    const beritaLink = window.appData.routes.beritaDetail.replace(
        "__slug__",
        slug
    );

    menuList.appendChild(
        createMenuItem(
            "Berita Terkini",
            beritaLink,
            "text-white hover:text-blue-200 transition-colors duration-200 py-2 px-3 rounded-md"
        )
    );

    menuList.appendChild(
        createMenuItem(
            "Kembali ke Home",
            window.appData.routes.home,
            "text-white hover:text-blue-200 transition-colors duration-200 py-2 px-3 rounded-md"
        )
    );
}

function addCommonMenuItems(menuList, withStyles = false) {
    const classes = withStyles
        ? "text-white hover:text-blue-200 transition-colors duration-200 py-2 px-3 rounded-md"
        : "";

    // Home item
    menuList.appendChild(
        createMenuItem("Kembali ke Home", window.appData.routes.home, classes)
    );

    // Dashboard/Login item
    const dashboardText =
        window.appData.isLoggedIn === "true" ||
        window.appData.isLoggedIn === true
            ? "Dashboard"
            : "Login";
    const dashboardHref =
        window.appData.isLoggedIn === "true" ||
        window.appData.isLoggedIn === true
            ? window.appData.routes.reportsIndex
            : window.appData.routes.login;

    menuList.appendChild(createMenuItem(dashboardText, dashboardHref, classes));
}
