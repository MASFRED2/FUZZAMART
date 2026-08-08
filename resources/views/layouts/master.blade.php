<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('judul', 'Dashboard') | Fuzza Mart POS</title>

    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">

    <style>
        :root{
            --navy:#0f172a;
            --navy2:#1e293b;
            --cream:#f8fafc;
            --gold:#b69377;
            --green:#16a34a;
            --red:#dc2626;
            --orange:#f59e0b;
        }

        body{
            background:#f4f6fb;
            font-family:"Inter","Segoe UI",Arial,sans-serif;
            color:#1f2937;
        }

        .main-sidebar{
            background:linear-gradient(180deg,#0f172a,#1e293b)!important;
            height:100vh!important;
            overflow:hidden!important;
        }

        .main-sidebar .sidebar{
            height:calc(100vh - 4.6rem)!important;
            overflow-y:auto!important;
            overflow-x:hidden!important;
            padding-bottom:120px!important;
        }

        .main-sidebar .sidebar::-webkit-scrollbar{
            width:6px;
        }

        .main-sidebar .sidebar::-webkit-scrollbar-thumb{
            background:rgba(255,255,255,.28);
            border-radius:10px;
        }

        .main-sidebar .sidebar::-webkit-scrollbar-track{
            background:transparent;
        }

        .brand-link{
            border-bottom:1px solid rgba(255,255,255,.08)!important;
        }

        .brand-text{
            font-weight:800!important;
            letter-spacing:.08em;
        }

        .content-wrapper{
            background:#f4f6fb;
        }

        .content-header h1{
            font-weight:800;
            color:#111827;
            font-size:1.55rem;
        }

        .card{
            border:0!important;
            border-radius:18px!important;
            box-shadow:0 8px 25px rgba(15,23,42,.07)!important;
            overflow:hidden;
        }

        .card-header{
            background:#fff;
            border-bottom:1px solid #eef2f7;
        }

        .btn{
            border-radius:10px;
            font-weight:700;
        }

        .btn-primary,
        .btn-warning{
            background:var(--gold)!important;
            border-color:var(--gold)!important;
            color:#fff!important;
        }

        .btn-primary:hover,
        .btn-warning:hover{
            filter:brightness(.92);
        }

        .btn-dark{
            background:var(--navy2)!important;
            border-color:var(--navy2)!important;
        }

        .form-control,
        .custom-select{
            border-radius:10px;
            border-color:#dbe3ef;
            min-height:42px;
        }

        .form-control:focus,
        .custom-select:focus{
            border-color:var(--gold);
            box-shadow:0 0 0 .15rem rgba(182,147,119,.18);
        }

        .table thead th{
            background:#111827;
            color:#fff;
            border:0;
            font-size:.75rem;
            text-transform:uppercase;
            letter-spacing:.04em;
            vertical-align:middle;
        }

        .table tbody td{
            vertical-align:middle;
        }

        .badge{
            border-radius:999px;
            padding:.4rem .65rem;
        }

        .alert{
            border:0;
            border-radius:14px;
            box-shadow:0 8px 25px rgba(15,23,42,.07);
        }

        .small-box{
            border-radius:18px;
            overflow:hidden;
            box-shadow:0 8px 25px rgba(15,23,42,.08);
        }

        .small-box .inner h3{
            font-weight:900;
        }

        .nav-sidebar .nav-link{
            border-radius:10px;
            margin:.12rem .5rem;
        }

        .nav-sidebar .nav-link.active{
            background:var(--gold)!important;
            color:#fff!important;
        }

        .table-responsive{
            border-radius:14px;
        }

        @media(max-width:768px){
            .content-header h1{
                font-size:1.25rem;
            }

            .card-body{
                padding:1rem!important;
            }

            .display-4{
                font-size:2.1rem;
            }

            .main-footer{
                text-align:center;
            }

            .table{
                font-size:.86rem;
            }

            .main-sidebar{
                overflow-y:auto!important;
            }
        }

        @media print{
            .main-header,
            .main-sidebar,
            .content-header,
            .main-footer,
            .no-print{
                display:none!important;
            }

            .content-wrapper{
                margin:0!important;
                background:#fff;
            }

            .card{
                box-shadow:none!important;
            }
        }
    </style>

    @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    @include('layouts.navbar')
    @include('layouts.sidebar')

    <div class="content-wrapper">
        <section class="content-header pb-2">
            <div class="container-fluid d-flex flex-wrap justify-content-between align-items-center">
                <h1 class="mb-2">@yield('judul', 'Dashboard')</h1>
                <div class="mb-2 text-muted small">
                    <i class="far fa-calendar-alt mr-1"></i>{{ now()->translatedFormat('l, d F Y') }}
                </div>
            </div>
        </section>

        <section class="content pb-4">
            <div class="container-fluid">
                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <strong>Data belum valid:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('isi')
            </div>
        </section>
    </div>

    @include('layouts.footer')
</div>

<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
<script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>

<script>
    function rupiah(value){
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(Number(value || 0));
    }
</script>

@stack('scripts')
</body>
</html>