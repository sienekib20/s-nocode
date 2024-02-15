<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

    <!-- ! Hide app brand if navbar-full -->
    <div class="app-brand demo">
        <a href="<?= route('home') ?>" class="app-brand-link">
            <span class="app-brand-text demo menu-text fw-bold ms-2">Sílica Page</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <li class="menu-item <?= request()->path() == '/home' ? 'active' : '' ?>">
            <a href="<?= route('home') ?>" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div class="text-truncate">Dashboards</div>
            </a>
        </li>

        <li class="menu-item <?= request()->path() == '/websites' ? 'active' : '' ?>">
            <a href="<?= route('websites') ?>" class="menu-link">
                <i class="menu-icon tf-icons bx bx-layout"></i>
                <div class="text-truncate">Meus websites</div>
            </a>
        </li>

        <li class="menu-item <?= request()->path() == '/campanhas' ? 'active' : '' ?>">
            <a href="<?= route('campanhas') ?>" class="menu-link">
                <i class="menu-icon tf-icons fa fa-megaphone"></i>
                <div class="text-truncate">Minhas campanhas</div>
                <div class="badge bg-danger rounded-pill ms-auto">0</div>
            </a>
        </li>

        <li class="menu-item <?= request()->path() == '/demand' ? 'active' : '' ?>">
            <a href="<?= route('demand') ?>" class="menu-link">
                <i class="menu-icon tf-icons bx bx-archive"></i>
                <div class="text-truncate">Encomendar website</div>
            </a>
        </li>

    </ul>
</aside>