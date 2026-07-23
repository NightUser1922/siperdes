<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPERDES - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 84px;
            --sidebar-bg: #14532d;
            --sidebar-bg-dark: #0f3d23;
            --app-bg: #eef3ef;
        }
        body { background-color: var(--app-bg); overflow-x: hidden; color: #1f2a24; }
        .sidebar {
            height: 100vh;
            background: linear-gradient(180deg, var(--sidebar-bg), var(--sidebar-bg-dark));
            color: white;
            position: fixed;
            width: var(--sidebar-width);
            transition: width 0.25s ease, transform 0.25s ease;
            z-index: 1040;
            box-shadow: 0 16px 40px rgba(15, 61, 35, 0.22);
        }
        .sidebar-brand {
            min-height: 76px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .75rem;
        }
        .sidebar-logo {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: rgba(255,255,255,.14);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 42px;
        }
        .sidebar-nav { padding: .75rem; }
        .sidebar-section-label {
            color: rgba(220, 252, 231, .72);
            letter-spacing: .08em;
            font-size: .72rem;
        }
        .sidebar a {
            color: #d7f7dc;
            text-decoration: none;
            padding: .82rem .95rem;
            display: flex;
            align-items: center;
            gap: .75rem;
            border-radius: 14px;
            transition: background-color .2s ease, color .2s ease, transform .2s ease;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: rgba(255,255,255,.12);
            color: white;
            transform: translateX(2px);
        }
        .sidebar a.active {
            background-color: #ffffff;
            color: #166534;
            font-weight: 600;
            box-shadow: 0 10px 24px rgba(15, 61, 35, .2);
        }
        .sidebar a i {
            width: 1.35rem;
            text-align: center;
            font-size: 1.1rem;
            flex: 0 0 1.35rem;
        }
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 24px;
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
            transition: margin-left .25s ease, width .25s ease;
        }
        .topbar {
            background-color: white;
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.08);
            padding: .9rem 1rem;
            border-radius: 18px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            border: 1px solid rgba(15, 23, 42, .06);
        }
        .topbar-title, .topbar-user {
            display: flex;
            align-items: center;
            gap: .75rem;
        }
        .topbar-user { flex-wrap: wrap; justify-content: flex-end; }
        .sidebar-toggle {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            border: 1px solid rgba(22, 101, 52, .18);
            background: #f0fdf4;
            color: #166534;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .user-chip {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            background: #f8fafc;
            border: 1px solid rgba(15, 23, 42, .08);
            border-radius: 999px;
            padding: .45rem .8rem;
        }
        .btn-logout { border-radius: 999px; }
        .menu-text, .sidebar-brand-text, .sidebar-section-label {
            transition: opacity .2s ease, width .2s ease;
            white-space: nowrap;
        }
        html.sidebar-collapsed .sidebar { width: var(--sidebar-collapsed-width); }
        html.sidebar-collapsed .main-content {
            margin-left: var(--sidebar-collapsed-width);
            width: calc(100% - var(--sidebar-collapsed-width));
        }
        html.sidebar-collapsed .menu-text,
        html.sidebar-collapsed .sidebar-brand-text,
        html.sidebar-collapsed .sidebar-section-label {
            width: 0;
            opacity: 0;
            overflow: hidden;
            pointer-events: none;
        }
        html.sidebar-collapsed .sidebar a {
            justify-content: center;
            padding-left: .75rem;
            padding-right: .75rem;
        }
        html.sidebar-collapsed .sidebar a i { margin-right: 0 !important; }
        .sidebar-backdrop { display: none; }
        .summary-card {
            border-radius: 18px;
            transition: transform .2s ease, box-shadow .2s ease;
        }
        .summary-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 16px 32px rgba(15, 23, 42, .12) !important;
        }
        .summary-icon {
            width: 54px;
            height: 54px;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(22, 101, 52, .08);
        }
        .dashboard-info-card { border-radius: 18px; }
        .user-info-box {
            background: #f8fafc;
            border: 1px solid rgba(15, 23, 42, .08);
            border-radius: 14px;
            padding: .9rem 1rem;
        }
        .module-hero,
        .module-card,
        .form-card {
            border-radius: 18px;
            border: 1px solid rgba(15, 23, 42, .06);
        }
        .module-hero {
            background: linear-gradient(135deg, #ffffff, #f0fdf4);
        }
        .module-icon {
            width: 54px;
            height: 54px;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(22, 101, 52, .1);
            color: #166534;
            flex: 0 0 54px;
        }
        .module-toolbar {
            display: flex;
            gap: 1rem;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        .module-search {
            max-width: 360px;
            width: 100%;
        }
        .module-search .input-group-text,
        .module-search .form-control {
            border-color: rgba(15, 23, 42, .08);
        }
        .module-search .input-group-text {
            background: #f8fafc;
        }
        .data-table {
            margin-bottom: 0;
        }
        .data-table thead th {
            background: #f8fafc;
            color: #475569;
            font-size: .78rem;
            letter-spacing: .04em;
            text-transform: uppercase;
            border-bottom: 1px solid rgba(15, 23, 42, .08);
            white-space: nowrap;
        }
        .data-table tbody tr {
            transition: background-color .18s ease;
        }
        .data-table tbody tr:hover {
            background: #f8fafc;
        }
        .action-btn {
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
        }
        .file-link-btn {
            border-radius: 999px;
        }
        .status-badge {
            border-radius: 999px;
            padding: .42rem .7rem;
            font-weight: 600;
        }
        .empty-state {
            padding: 2.5rem 1rem;
            text-align: center;
            color: #64748b;
        }
        .empty-state i {
            font-size: 2rem;
            color: #94a3b8;
        }
        .form-section-title {
            font-size: .82rem;
            letter-spacing: .05em;
            text-transform: uppercase;
            color: #64748b;
            font-weight: 700;
        }
        .required-dot {
            color: #dc3545;
            font-weight: 700;
        }
        .current-file-badge {
            border-radius: 999px;
            background: #e0f2fe;
            color: #075985;
        }
        @media (max-width: 991.98px) {
            .sidebar {
                width: var(--sidebar-width);
                transform: translateX(-100%);
            }
            body.sidebar-open .sidebar { transform: translateX(0); }
            .main-content {
                margin-left: 0;
                width: 100%;
                padding: 16px;
            }
            html.sidebar-collapsed .sidebar { width: var(--sidebar-width); }
            html.sidebar-collapsed .main-content { margin-left: 0; width: 100%; }
            html.sidebar-collapsed .menu-text,
            html.sidebar-collapsed .sidebar-brand-text,
            html.sidebar-collapsed .sidebar-section-label {
                width: auto;
                opacity: 1;
                overflow: visible;
                pointer-events: auto;
            }
            html.sidebar-collapsed .sidebar a { justify-content: flex-start; }
            .sidebar-backdrop {
                display: block;
                position: fixed;
                inset: 0;
                background: rgba(15, 23, 42, .45);
                opacity: 0;
                pointer-events: none;
                transition: opacity .2s ease;
                z-index: 1030;
            }
            body.sidebar-open .sidebar-backdrop {
                opacity: 1;
                pointer-events: auto;
            }
        }
        @media (max-width: 575.98px) {
            .topbar { align-items: flex-start; flex-direction: column; }
            .topbar-user { width: 100%; justify-content: space-between; }
            .module-toolbar { align-items: stretch; }
            .module-toolbar .btn,
            .module-search { width: 100%; max-width: 100%; }
        }
    </style>
    <script>
        (function () {
            try {
                if (localStorage.getItem('siperdes.sidebar') === 'collapsed') {
                    document.documentElement.classList.add('sidebar-collapsed');
                }
            } catch (error) {}
        })();
    </script>
</head>
<body>

    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <div class="sidebar" id="appSidebar">
        <h4 class="sidebar-brand text-center py-3 fw-bold mb-0 border-bottom border-success">
            <span class="sidebar-logo"><i class="bi bi-bank"></i></span>
            <span class="sidebar-brand-text">SIPERDES</span>
        </h4>
        
        <div class="sidebar-nav">
        <small class="sidebar-section-label text-uppercase px-3 mt-4 d-block fw-bold">Menu Utama</small>
        
        {{-- LINK DASHBOARD --}}
        @php
            $dashboardUrl = Auth::check() && Auth::user()->role === 'Kepala Desa'
                ? url('/kades/dashboard')
                : url('/admin/dashboard');
        @endphp

        <a href="{{ $dashboardUrl }}" class="{{ Request::is('admin/dashboard') || Request::is('kades/dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2 me-2"></i><span class="menu-text">Dashboard</span>
        </a>
        
        {{-- LINK SURAT MASUK --}}
        <a href="{{ url('/surat-masuk') }}" class="{{ Request::is('surat-masuk*') ? 'active' : '' }}">
            <i class="bi bi-inbox me-2"></i><span class="menu-text">Surat Masuk</span>
        </a>
        
        {{-- LINK SURAT KELUAR --}}
        <a href="{{ url('/surat-keluar') }}" class="{{ Request::is('surat-keluar*') ? 'active' : '' }}">
            <i class="bi bi-send me-2"></i><span class="menu-text">Surat Keluar</span>
        </a>

        <small class="sidebar-section-label text-uppercase px-3 mt-4 d-block fw-bold">Ekspansi (Fitur Baru)</small>
        <a href="{{ url('/kegiatan-desa') }}" class="{{ Request::is('kegiatan-desa*') ? 'active' : '' }}">
            <i class="bi bi-calendar-event me-2"></i><span class="menu-text">Kegiatan Desa</span>
        </a>

        <a href="{{ url('/bantuan-sosial') }}" class="{{ Request::is('bantuan-sosial*') ? 'active' : '' }}">
            <i class="bi bi-people me-2"></i><span class="menu-text">Bantuan Sosial</span>
        </a>

        <a href="#"><i class="bi bi-folder2-open me-2"></i><span class="menu-text">Arsip Digital</span></a>
        <a href="#"><i class="bi bi-bar-chart-line me-2"></i><span class="menu-text">Laporan</span></a>

        <small class="sidebar-section-label text-uppercase px-3 mt-4 d-block fw-bold">Sistem</small>
        <a href="#"><i class="bi bi-shield-lock me-2"></i><span class="menu-text">Audit Log</span></a>
        </div>
    </div>

    <div class="main-content">
        <div class="topbar">
            <div class="topbar-title">
                <button type="button" class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar" aria-expanded="true">
                    <i class="bi bi-list"></i>
                </button>
                <h5 class="mb-0 text-success fw-bold">@yield('title')</h5>
            </div>
            <div class="topbar-user">
                <span class="user-chip text-muted"><i class="bi bi-person-circle"></i> Halo, <strong>{{ Auth::user()->username ?? 'User' }}</strong></span>
                <form action="/logout" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger btn-logout"><i class="bi bi-box-arrow-right me-1"></i> Logout</button>
                </form>
            </div>
        </div>

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function () {
            const root = document.documentElement;
            const body = document.body;
            const toggleButton = document.getElementById('sidebarToggle');
            const backdrop = document.getElementById('sidebarBackdrop');
            const storageKey = 'siperdes.sidebar';

            function isMobile() {
                return window.matchMedia('(max-width: 991.98px)').matches;
            }

            function updateExpandedState() {
                if (!toggleButton) return;
                const expanded = isMobile()
                    ? body.classList.contains('sidebar-open')
                    : !root.classList.contains('sidebar-collapsed');
                toggleButton.setAttribute('aria-expanded', expanded ? 'true' : 'false');
            }

            function closeMobileSidebar() {
                body.classList.remove('sidebar-open');
                updateExpandedState();
            }

            if (toggleButton) {
                toggleButton.addEventListener('click', function () {
                    if (isMobile()) {
                        body.classList.toggle('sidebar-open');
                    } else {
                        root.classList.toggle('sidebar-collapsed');
                        try {
                            localStorage.setItem(
                                storageKey,
                                root.classList.contains('sidebar-collapsed') ? 'collapsed' : 'expanded'
                            );
                        } catch (error) {}
                    }
                    updateExpandedState();
                });
            }

            if (backdrop) {
                backdrop.addEventListener('click', closeMobileSidebar);
            }

            document.querySelectorAll('.sidebar a').forEach(function (link) {
                link.addEventListener('click', closeMobileSidebar);
            });

            window.addEventListener('resize', function () {
                if (!isMobile()) closeMobileSidebar();
                updateExpandedState();
            });
        (function () {
            document.querySelectorAll('[data-admin-table]').forEach(function (tableBody) {
                const searchInput = document.querySelector(tableBody.dataset.searchInput || '');
                const pagination = document.querySelector(tableBody.dataset.pagination || '');
                const tableInfo = document.querySelector(tableBody.dataset.tableInfo || '');
                const emptySearchRow = document.querySelector(tableBody.dataset.emptyRow || '');
                const rows = Array.from(tableBody.querySelectorAll('[data-search-row]'));
                const pageSize = parseInt(tableBody.dataset.pageSize || '10', 10);
                const itemLabel = tableBody.dataset.itemLabel || 'data';
                let currentPage = 1;

                function filteredRows() {
                    const keyword = (searchInput?.value || '').trim().toLowerCase();
                    return rows.filter(function (row) {
                        return row.dataset.searchRow.includes(keyword);
                    });
                }

                function render() {
                    const visibleRows = filteredRows();
                    const totalPages = Math.max(1, Math.ceil(visibleRows.length / pageSize));
                    currentPage = Math.min(currentPage, totalPages);
                    const start = (currentPage - 1) * pageSize;
                    const end = start + pageSize;

                    rows.forEach(function (row) {
                        row.classList.add('d-none');
                    });

                    visibleRows.slice(start, end).forEach(function (row) {
                        row.classList.remove('d-none');
                    });

                    if (emptySearchRow) {
                        emptySearchRow.classList.toggle('d-none', visibleRows.length !== 0 || rows.length === 0);
                    }

                    if (tableInfo) {
                        tableInfo.textContent = `Menampilkan ${visibleRows.length} dari ${rows.length} ${itemLabel}`;
                    }

                    if (!pagination) return;
                    pagination.innerHTML = '';
                    if (visibleRows.length <= pageSize) return;

                    for (let page = 1; page <= totalPages; page++) {
                        const item = document.createElement('li');
                        item.className = `page-item ${page === currentPage ? 'active' : ''}`;

                        const button = document.createElement('button');
                        button.type = 'button';
                        button.className = 'page-link';
                        button.textContent = page;
                        button.addEventListener('click', function () {
                            currentPage = page;
                            render();
                        });

                        item.appendChild(button);
                        pagination.appendChild(item);
                    }
                }

                if (searchInput) {
                    searchInput.addEventListener('input', function () {
                        currentPage = 1;
                        render();
                    });
                }

                render();
            });
        })();


            updateExpandedState();
        })();
    </script>
</body>
</html>
