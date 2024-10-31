<div class="navbar px-3 mb-0 w-100 bg-dark p-1">
    <i class="bi bi-list nav-res-header text-white fs-1 p-0" type="button" data-bs-toggle="offcanvas"
        data-bs-target="#offcanvasWithBothOptions" aria-controls="offcanvasWithBothOptions">
    </i>
    <div class="offcanvas offcanvas-start bg-dark" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions"
        aria-labelledby="offcanvasWithBothOptionsLabel">
        <div class="offcanvas-header flex-row-reverse pb-0">
            <button type="button" class="btn btn-dark" data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="bi bi-x-lg fs-2"></i>
            </button>
        </div>
        <div class="offcanvas-body pt-0 d-flex flex-column justify-content-between">
            <div class="text-center">
                <div class="w-100">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-100 mt-5">
                </div>
                <button class="btn btn-dark w-100">
                    <a class="btn btn-dark border-none rounded p-1 ps-2 text-start text-white w-100" aria-current="page"
                        href="{{ route('user.index', ['type' => 1, 'page' => 1]) }}">Usuarios</a>
                </button>
            </div>
            <div class="w-100 d-flex flex-column">
                <span
                    class="btn btn-dark border  rounded p-1 ps-2 text-start text-warning fw-bold"
                    href="#" role="button" data-bs-toggle="dropdown" aria-expanded="true">
                    <i class="bi bi-person-fill"></i> {{ auth()->user()->name }}
                </span>
                <button
                    class="btn btn-dark border  rounded p-1 ps-2 text-start text-white"
                    href="#" role="button" data-bs-toggle="dropdown" aria-expanded="true">
                    <i class="bi bi-box-arrow-right"></i> {{ __('pagination.button.logout') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Logo del menú -->
    <a href=" {{ !auth()->user()->hasRole('Cliente') ? Route('dashboard') : Route('client.index', ['section' => 1]) }}" class="navbar-brand
        img-width d-flex justify-content-center">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-100 m-0">
    </a>
    <!-- Enlaces del menú -->
    <ul class="nav nav-underline nav-static-header">
        @if (!auth()->user()->hasRole('Cliente'))
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-light" data-bs-toggle="dropdown" href="#" role="button"
                    aria-expanded="false">
                    <i class="bi bi-grid-1x2-fill"></i> {{ __('pagination.navigation.catalogs') }}
                </a>
                <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-lg-end">
                    <li>
                        <a class="dropdown-item text-light" aria-current="page"
                            href="{{ route('user.index', ['type' => 1, 'page' => 1]) }}">
                            <i class="bi bi-person-fill"></i> {{ __('pagination.navigation.users') }}
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item text-light" aria-current="page"
                            href="{{ route('customer.index', ['type' => 1, 'page' => 1]) }}">
                            <i class="bi bi-people-fill"></i> {{ __('pagination.navigation.customers') }}
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item text-light" aria-current="page"
                            href="{{ route('service.index', ['page' => 1]) }}">
                            <i class="bi bi-gear-fill"></i> {{ __('pagination.navigation.services') }}
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item text-light" aria-current="page"
                            href="{{ route('product.index', ['type' => 1, 'page' => 1]) }}">
                            <i class="bi bi-box-fill"></i> {{ __('pagination.navigation.products') }}
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item text-light" aria-current="page"
                            href="{{ route('pest.index', ['page' => 1]) }}">
                            <i class="bi bi-bug-fill"></i> {{ __('pagination.navigation.pests') }}
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item text-light" aria-current="page"
                            href="{{ route('contract.index', ['page' => 1]) }}">
                            <i class="bi bi-calendar-fill"></i> {{ __('pagination.navigation.contract') }}
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item text-light" aria-current="page"
                            href="{{ route('order.index', ['page' => 1]) }}">
                            <i class="bi bi-nut-fill"></i> {{ __('pagination.navigation.orders') }}
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item text-light" aria-current="page"
                            href="{{ route('point.index', ['page' => 1]) }}">
                            <i class="bi bi-hand-index-fill"></i> Puntos de control
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item text-light" aria-current="page" href="{{ route('branch.index') }}">
                            <i class="bi bi-globe-americas"></i> Delegaciones
                        </a>
                    </li>
                </ul>
            </li>
        @endif
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-warning fw-bold" data-bs-toggle="dropdown" href="#"
                role="button" aria-expanded="false">
                <i class="bi bi-person-fill"></i> {{ auth()->user()->name }}
            </a>
            <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-lg-end">
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right"></i>
                            {{ __('pagination.button.logout') }}</button>
                    </form>
                </li>
            </ul>
        </li>
    </ul>
</div>
