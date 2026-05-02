@php use Carbon\Carbon; @endphp
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    @stack('styles')
    <link rel="icon" type="image/x-icon" href="media/favicon.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
          rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <title>@yield('title')</title>
</head>
<body class="d-flex flex-column min-vh-100">
<header class="sticky-top border-bottom shadow-sm" style="background-color: #7120A6;">
    <nav class="navbar navbar-expand-xl navbar-dark py-2 py-xl-3">
        <div class="container-fluid px-3 px-xl-5">

            <!-- Логотип -->
            <a class="navbar-brand d-flex align-items-center fw-semibold text-white me-2 me-xl-3" href="{{ route('home') }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200" width="40" height="40" width-xl="56" height-xl="56" class="me-2">
                    <defs>
                        <linearGradient id="headerCoverGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                            <stop offset="0%" style="stop-color:#c4b5fd;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#8b5cf6;stop-opacity:1" />
                        </linearGradient>
                        <linearGradient id="headerPageGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                            <stop offset="0%" style="stop-color:#ffffff;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#f1f5f9;stop-opacity:1" />
                        </linearGradient>
                        <radialGradient id="headerStarGold" cx="50%" cy="50%" r="50%">
                            <stop offset="0%" style="stop-color:#fde047;stop-opacity:1" />
                            <stop offset="50%" style="stop-color:#fbbf24;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#d97706;stop-opacity:1" />
                        </radialGradient>
                        <filter id="headerGlow" x="-50%" y="-50%" width="200%" height="200%">
                            <feGaussianBlur stdDeviation="2" result="coloredBlur"/>
                            <feMerge>
                                <feMergeNode in="coloredBlur"/>
                                <feMergeNode in="SourceGraphic"/>
                            </feMerge>
                        </filter>
                    </defs>
                    <path d="M 38 52 Q 100 52 162 52 Q 168 52 168 58 L 168 132 Q 168 138 162 138 L 38 138 Q 32 138 32 132 L 32 58 Q 32 52 38 52 Z" fill="url(#headerCoverGrad)" />
                    <path d="M 42 56 Q 42 56 98 56 L 98 134 Q 42 134 42 134 Q 38 134 38 130 L 38 60 Q 38 56 42 56 Z" fill="url(#headerPageGrad)" />
                    <path d="M 158 56 Q 158 56 102 56 L 102 134 Q 158 134 158 134 Q 162 134 162 130 L 162 60 Q 162 56 158 56 Z" fill="url(#headerPageGrad)" />
                    <path d="M 100 56 L 100 134" stroke="#e2e8f0" stroke-width="1.5" opacity="0.6" />
                    <path d="M 128 68 L 134 78 L 146 84 L 134 90 L 128 102 L 122 90 L 110 84 L 122 78 Z" fill="url(#headerStarGold)" filter="url(#headerGlow)" />
                </svg>
                <span class="d-none d-sm-inline">Блокнот Учителя</span>
            </a>

            <!-- Кнопка гамбургер -->
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNav" aria-controls="navbarNav"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Меню навигации -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-xl-0 gap-1">

                    <li class="nav-item">
                        <a class="nav-link text-white-50 hover-white {{ request()->routeIs('home') ? 'active text-white fw-semibold' : '' }}"
                           href="{{ route('home') }}">Главная</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50 hover-white {{ request()->routeIs('calendar') ? 'active text-white fw-semibold' : '' }}"
                           href="{{ route('calendar') }}">Календарь</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50 hover-white {{ request()->routeIs('schedule.*') ? 'active text-white fw-semibold' : '' }}"
                           href="{{ route('schedule.index') }}">Расписание</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50 hover-white {{ request()->routeIs('database.*') ? 'active text-white fw-semibold' : '' }}"
                           href="{{ route('database.index') }}">База данных</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50 hover-white {{ request()->routeIs('students.*') ? 'active text-white fw-semibold' : '' }}"
                           href="{{ route('students.index') }}">Ученики</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50 hover-white {{ request()->routeIs('attendance.*') ? 'active text-white fw-semibold' : '' }}"
                           href="{{ route('attendance.index') }}">Питание</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50 hover-white {{ request()->routeIs('competitions.*') ? 'active text-white fw-semibold' : '' }}"
                           href="{{ route('competitions.index') }}">Конкурсы</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50 hover-white {{ request()->routeIs('documents.*') ? 'active text-white fw-semibold' : '' }}"
                           href="{{ route('documents.index') }}">Документы</a>
                    </li>

                    <!-- Админ-ссылки (только для админов) -->
                    @admin
                        <li class="nav-item">
                            <a class="nav-link text-white-50 hover-white {{ request()->routeIs('qualifications.*') ? 'active text-white fw-semibold' : '' }}"
                               href="{{ route('qualifications.all') }}">Квалификации</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50 hover-white {{ request()->routeIs('achievements.*') ? 'active text-white fw-semibold' : '' }}"
                               href="{{ route('achievements.all') }}">Достижения</a>
                        </li>
                    @endadmin
                </ul>

                <!-- Правая часть: Профиль и выход -->
                <div class="d-flex align-items-center gap-2">
                    @auth
                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="d-none d-xl-inline ms-2 small fw-medium">{{ Str::limit(auth()->user()->name ?? 'Пользователь', 20) }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow mt-2">
                                <li><a class="dropdown-item {{ request()->routeIs('cabinet.*') ? 'active' : '' }}"
                                       href="{{ route('cabinet.index') }}">👤 Личный кабинет</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">🚪 Выйти</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
</header>
<main class="flex-grow-1">
    @yield('content')
</main>

<footer class="bg-secondary-subtle border-top py-4 py-md-5 mt-auto">
    <div class="container">
        <div class="row g-4 g-md-5">
            <!-- Логотип и описание -->
            <div class="col-12 col-md-4 col-lg-3">
                <a href="{{ route('home') }}" class="d-flex align-items-center mb-3 link-body-emphasis text-decoration-none">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200" width="48" height="48" class="me-2">
                        <defs>
                            <linearGradient id="coverGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                                <stop offset="0%" style="stop-color:#c4b5fd;stop-opacity:1" />
                                <stop offset="100%" style="stop-color:#8b5cf6;stop-opacity:1" />
                            </linearGradient>
                            <linearGradient id="pageGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                                <stop offset="0%" style="stop-color:#ffffff;stop-opacity:1" />
                                <stop offset="100%" style="stop-color:#f1f5f9;stop-opacity:1" />
                            </linearGradient>
                            <radialGradient id="starGold" cx="50%" cy="50%" r="50%">
                                <stop offset="0%" style="stop-color:#fde047;stop-opacity:1" />
                                <stop offset="50%" style="stop-color:#fbbf24;stop-opacity:1" />
                                <stop offset="100%" style="stop-color:#d97706;stop-opacity:1" />
                            </radialGradient>
                            <filter id="glow" x="-50%" y="-50%" width="200%" height="200%">
                                <feGaussianBlur stdDeviation="2" result="coloredBlur"/>
                                <feMerge>
                                    <feMergeNode in="coloredBlur"/>
                                    <feMergeNode in="SourceGraphic"/>
                                </feMerge>
                            </filter>
                        </defs>
                        <path d="M 38 52 Q 100 52 162 52 Q 168 52 168 58 L 168 132 Q 168 138 162 138 L 38 138 Q 32 138 32 132 L 32 58 Q 32 52 38 52 Z" fill="url(#coverGrad)" />
                        <path d="M 42 56 Q 42 56 98 56 L 98 134 Q 42 134 42 134 Q 38 134 38 130 L 38 60 Q 38 56 42 56 Z" fill="url(#pageGrad)" />
                        <path d="M 158 56 Q 158 56 102 56 L 102 134 Q 158 134 158 134 Q 162 134 162 130 L 162 60 Q 162 56 158 56 Z" fill="url(#pageGrad)" />
                        <path d="M 100 56 L 100 134" stroke="#e2e8f0" stroke-width="1.5" opacity="0.6" />
                        <path d="M 128 68 L 134 78 L 146 84 L 134 90 L 128 102 L 122 90 L 110 84 L 122 78 Z" fill="url(#starGold)" filter="url(#glow)" />
                    </svg>
                    <span class="fs-5 fw-semibold">Блокнот Учителя</span>
                </a>
                <p class="text-body-secondary small mb-0">
                    Удобный инструмент для организации учебного процесса, учета успеваемости и взаимодействия с родителями.
                </p>
            </div>

            <!-- Раздел: Навигация -->
            <div class="col-6 col-md-2">
                <h6 class="fw-semibold mb-3">Навигация</h6>
                <ul class="nav flex-column gap-2">
                    <li><a href="{{ route('home') }}" class="nav-link p-0 text-body-secondary link-underline-opacity-0 link-underline-opacity-100-hover">Главная</a></li>
                    <li><a href="{{ route('calendar') }}" class="nav-link p-0 text-body-secondary link-underline-opacity-0 link-underline-opacity-100-hover">Календарь</a></li>
                    <li><a href="{{ route('schedule.index') }}" class="nav-link p-0 text-body-secondary link-underline-opacity-0 link-underline-opacity-100-hover">Расписание</a></li>
                    <li><a href="{{ route('database.index') }}" class="nav-link p-0 text-body-secondary link-underline-opacity-0 link-underline-opacity-100-hover">База данных</a></li>
                </ul>
            </div>

            <!-- Раздел: Ученики -->
            <div class="col-6 col-md-2">
                <h6 class="fw-semibold mb-3">Ученики</h6>
                <ul class="nav flex-column gap-2">
                    <li><a href="{{ route('students.index') }}" class="nav-link p-0 text-body-secondary link-underline-opacity-0 link-underline-opacity-100-hover">Список учеников</a></li>
                    <li><a href="{{ route('attendance.index') }}" class="nav-link p-0 text-body-secondary link-underline-opacity-0 link-underline-opacity-100-hover">Посещаемость</a></li>
                    <li><a href="{{ route('competitions.index') }}" class="nav-link p-0 text-body-secondary link-underline-opacity-0 link-underline-opacity-100-hover">Конкурсы</a></li>
                    <li><a href="{{ route('qualifications.index') }}" class="nav-link p-0 text-body-secondary link-underline-opacity-0 link-underline-opacity-100-hover">Квалификации</a></li>
                </ul>
            </div>

            <!-- Раздел: Документы -->
            <div class="col-6 col-md-2">
                <h6 class="fw-semibold mb-3">Документы</h6>
                <ul class="nav flex-column gap-2">
                    <li><a href="{{ route('documents.index') }}" class="nav-link p-0 text-body-secondary link-underline-opacity-0 link-underline-opacity-100-hover">Все документы</a></li>
                    <li><a href="{{ route('notifications.index') }}" class="nav-link p-0 text-body-secondary link-underline-opacity-0 link-underline-opacity-100-hover">Объявления</a></li>
                    @admin
                    <li><a href="{{ route('register.index') }}" class="nav-link p-0 text-body-secondary link-underline-opacity-0 link-underline-opacity-100-hover">Добавить пользователя</a></li>
                    @endadmin
                    <li><a href="{{ route('cabinet.index') }}" class="nav-link p-0 text-body-secondary link-underline-opacity-0 link-underline-opacity-100-hover">Личный кабинет</a></li>
                </ul>
            </div>

            <!-- Раздел: Контакты / Доп. ссылки -->
            <div class="col-6 col-md-2">
                <h6 class="fw-semibold mb-3">Ещё</h6>
                <ul class="nav flex-column gap-2">
                    <li><a href="#" class="nav-link p-0 text-body-secondary link-underline-opacity-0 link-underline-opacity-100-hover">Помощь</a></li>
                    <li><a href="#" class="nav-link p-0 text-body-secondary link-underline-opacity-0 link-underline-opacity-100-hover">Политика конфиденциальности</a></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="nav-link p-0 text-body-secondary link-underline-opacity-0 link-underline-opacity-100-hover border-0 bg-transparent p-0">Выйти</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Нижняя полоса с копирайтом -->
        <div class="border-top pt-4 mt-4 text-center text-md-start">
            <p class="text-body-secondary small mb-0">
                &copy; {{ date('Y') }} Блокнот Учителя. Все права защищены.
            </p>
        </div>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"
        integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y"
        crossorigin="anonymous"></script>
<script src="scripts/calendar-landing.js"></script>
</body>
</html>
