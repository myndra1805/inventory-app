@php
$navs = [
[
"icon" => 'mdi mdi-view-dashboard',
'text' => 'Dashboard',
],
[
"icon" => 'mdi mdi-folder',
'text' => 'Products',
],
[
"icon" => 'mdi mdi-bookmark',
'text' => 'Units',
],
[
"icon" => 'mdi mdi-all-inclusive',
'text' => 'Types',
],
[
"icon" => 'mdi mdi-factory',
'text' => 'Suppliers',
],
[
"icon" => 'mdi mdi-sync',
'text' => 'Transactions',
],
];

if(Auth::user()->hasRole(['admin', 'super-admin'])){
array_splice($navs, 4, 0, [
[
"icon" => 'mdi mdi-account-group',
'text' => 'Users',
]
]);
}
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>
    <link rel="shortcut icon" href="/images/logo.png" type="image/x-icon">
    {{-- Fonts --}}
    <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">
    {{-- Datatables --}}
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.13.1/datatables.min.css" />
    {{-- Scripts --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="container-fluid" style="">
        <div class="row" style="min-height: 100vh">
            {{-- aside --}}
            <div class="col-auto p-2 sticky-top" style="width: 16rem; height: 100vh;">
                <aside class="bg-dark h-100 rounded-4 shadow" style="overflow: auto">

                    {{-- logo --}}
                    <div class="pt-3 px-2 text-center text-white sticky-top">
                        <img src="/images/logo.png" width="50px" alt="Logo" class="img-fluid">
                        <span class="fw-bold">INVENTORY APP</span>
                        <hr class="text-white mx-auto" style="width: 13rem">
                    </div>
                    {{-- end logo --}}


                    {{-- sidenav --}}
                    <nav class="nav flex-column pb-3 px-4">
                        @foreach ($navs as $nav)

                        <a class="nav-link text-white {{Request::is(strtolower($nav['text'] . '*')) ? 'bg-primary' : ''}} rounded-2 side-link d-flex align-items-center"
                            href="/{{strtolower($nav['text'])}}">
                            <i class="{{$nav['icon']}} me-2" style="font-size: 20px"></i>
                            {{$nav['text']}}
                        </a>
                        @endforeach
                    </nav>
                    {{-- end sidenav --}}
                </aside>
            </div>
            {{-- end aside --}}

            <div class="col px-2">
                {{-- navbar --}}
                <div class="bg-white pb-2 sticky-top">
                    <nav class="shadow navbar navbar-expand-lg navbar-light bg-white rounded"
                        style="top: 10px; z-index: 100">
                        <div class="container">
                            <div>
                                {{$breadcrumbs}}
                            </div>
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                                data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup"
                                aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div>
                                <div class="dropdown">
                                    <a class="dropdown-toggle text-decoration-none d-flex align-items-center"
                                        aria-current="page" href="#" role="button" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="mdi mdi-account-circle text-primary" style="font-size: 40px"></i>
                                        <div>
                                            <small class="d-block text-muted fw-semibold">
                                                {{Auth::user()->name}}
                                            </small>
                                            <small class="d-block text-muted fw-semibold"
                                                style="font-size: 12px">{{Auth::user()->getRoleNames()[0]}}</small>
                                        </div>
                                    </a>

                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="/profile">Profile</a></li>
                                        <li><a class="dropdown-item" href="/logout" onclick="logout(event)">Logout</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </nav>
                </div>
                {{-- end navbar --}}


                {{-- main --}}
                <main class="mt-3 d-flex flex-column" style="min-height: 86vh">
                    <div style="flex: 1 0 auto;">
                        {{$slot}}
                    </div>
                    {{-- footer --}}
                    <footer class="pt-3 pb-2 mt-3">
                        <p class="m-0">
                            Copyright &copy; {{now()->year}} InventoryApp made with
                            <i class="mdi mdi-heart text-danger"></i>
                            by
                            <a href="https://github.com/myndra1805" target="_blank">Ari Yuhendra</a>
                        </p>
                    </footer>
                    {{-- end footer --}}
                </main>
                {{-- end main --}}

            </div>
        </div>
    </div>

    {{-- form logout --}}
    <form action="/logout" method="post" id="form-logout">
        @csrf
    </form>
    {{-- end form logout --}}

    {{-- JQuery --}}
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"
        integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    {{-- Datatables --}}
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.13.1/datatables.min.js"></script>
    @yield('scripts')
</body>

<script>
    function logout(event) {
        event.preventDefault()
        document.querySelector('#form-logout').submit()
    }
</script>

</html>