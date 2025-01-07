<?php
function getSiteUrl() {
    if (
        isset($_SERVER['HTTPS']) &&
        ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
        isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
        $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'
    ) {
        $protocol = 'https://';
    }
    else {
        $protocol = 'http://';
    }

    return $protocol.$_SERVER['HTTP_HOST'].'/';
}

$site_url = getSiteUrl();
$current_page = (isset($_SESSION['current_page'])) ? $_SESSION['current_page'] : "";
?>
<nav class="navbar navbar-expand-sm bg-body-tertiary border-bottom border-body sticky-top">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item d-flex align-items-center">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-1" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                            <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                        </svg>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item">Action</a></li>
                        <li><a class="dropdown-item">Another action</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item">Something else here</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == "utente") ? "active" : "" ?>" <?= ($current_page == "utente") ? "aria-current=\"page\"" : "" ?> href="<?= $site_url ?>quiz/templates/admin/utente/">Utenti</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == "test") ? "active" : "" ?>" <?= ($current_page == "test") ? "aria-current=\"page\"" : "" ?> href="<?= $site_url ?>quiz/templates/test">Test Creati</a>
                </li>
            </ul>
        </div>
    </div>
</nav>