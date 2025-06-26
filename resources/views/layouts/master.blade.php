<!DOCTYPE html>
<html lang="en">

<head>
    <title>Sistem LPG | @yield('title')</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="{{ asset('adm/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('adm/css/bootstrap-responsive.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('adm/css/fullcalendar.css') }}" />
    <link rel="stylesheet" href="{{ asset('adm/css/maruti-style.css') }}" />
    <link rel="stylesheet" href="{{ asset('adm/css/uniform.css') }}" />
    <link rel="stylesheet" href="{{ asset('adm/css/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('adm/css/maruti-media.css') }}" class="skin-color" />
    <link rel="stylesheet" href="{{ asset('adm/css/datepicker.css') }}" />

    <link rel="stylesheet" href="{{ asset('adm/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('adm/css/bootstrap-responsive.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('adm/css/uniform.css') }}" />
    <link rel="stylesheet" href="{{ asset('adm/css/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('adm/css/maruti-style.css') }}" />
    <link rel="stylesheet" href="{{ asset('adm/css/maruti-media.css') }}" class="skin-color" />

    <link rel="stylesheet" href="{{ asset('adm/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('adm/css/bootstrap-responsive.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('adm/css/colorpicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('adm/css/datepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('adm/css/uniform.css') }}" />
    <link rel="stylesheet" href="{{ asset('adm/css/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('adm/css/maruti-style.css') }}" />
    <link rel="stylesheet" href="{{ asset('adm/css/maruti-media.css') }}" class="skin-color" />
</head>

<body>

    @include('layouts.header')

    @include('layouts.sidebar')

    @yield('content')

    @include('layouts.footer')

    <script src="{{ asset('adm/js/excanvas.min.js') }}"></script>
    <script src="{{ asset('adm/js/jquery.min.js') }}"></script>
    <script src="{{ asset('adm/js/jquery.ui.custom.js') }}"></script>
    <script src="{{ asset('adm/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('adm/js/jquery.flot.min.js') }}"></script>
    <script src="{{ asset('adm/js/jquery.flot.resize.min.js') }}"></script>
    <script src="{{ asset('adm/js/jquery.peity.min.js') }}"></script>
    <script src="{{ asset('adm/js/fullcalendar.min.js') }}"></script>
    <script src="{{ asset('adm/js/maruti.js') }}"></script>
    <script src="{{ asset('adm/js/maruti.dashboard.js') }}"></script>
    <script src="{{ asset('adm/js/maruti.chat.js') }}"></script>
    <script src="{{ asset('adm/js/select2.min.js') }}"></script>
    <script src="{{ asset('adm/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adm/js/maruti.tables.js') }}"></script>
    <script src="{{ asset('adm/js/jquery.uniform.js') }}"></script>
    <script src="{{ asset('adm/js/maruti.form_common.js') }}"></script>
    <script src="{{ asset('adm/js/bootstrap-datepicker.js') }}"></script>

    <script src="{{ asset('adm/js/jquery.min.js') }}"></script>
    <script src="{{ asset('adm/js/jquery.ui.custom.js') }}"></script>
    <script src="{{ asset('adm/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('adm/js/jquery.uniform.js') }}"></script>
    <script src="{{ asset('adm/js/select2.min.js') }}"></script>
    <script src="{{ asset('adm/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adm/js/maruti.js') }}"></script>
    <script src="{{ asset('adm/js/maruti.tables.js') }}"></script>
    <script src="{{ asset('adm/js/maruti.form_common.js') }}"></script>

    <script src="{{ asset('adm/js/jquery.min.js') }}"></script>
    <script src="{{ asset('adm/js/jquery.ui.custom.js') }}"></script>
    <script src="{{ asset('adm/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('adm/js/bootstrap-colorpicker.js') }}"></script>
    <script src="{{ asset('adm/js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('adm/js/jquery.uniform.js') }}"></script>
    <script src="{{ asset('adm/js/select2.min.js') }}"></script>
    <script src="{{ asset('adm/js/maruti.js') }}"></script>
    <script src="{{ asset('adm/js/maruti.form_common.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('btnLogout').addEventListener('click', function(e) {
            e.preventDefault(); // biar nggak loncat ke #

            Swal.fire({
                title: 'Yakin ingin logout?',
                text: "Kamu akan keluar dari sistem.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logoutForm').submit();
                }
            });
        });
    </script>

    @if (session('welcome'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Selamat Datang!',
                text: @json(session('success') . ' ' . Auth::user()->nama_lengkap),
                timer: 2500,
                showConfirmButton: false
            });
        </script>
    @endif



    <script type="text/javascript">
        // This function is called from the pop-up menus to transfer to
        // a different page. Ignore if the value returned is a null string:
        function goPage(newURL) {

            // if url is empty, skip the menu dividers and reset the menu selection to default
            if (newURL != "") {

                // if url is "-", it is this page -- reset the menu:
                if (newURL == "-") {
                    resetMenu();
                }
                // else, send page to designated URL            
                else {
                    document.location.href = newURL;
                }
            }
        }

        // resets the menu selection upon entry to this page:
        function resetMenu() {
            document.gomenu.selector.selectedIndex = 2;
        }
    </script>
</body>

</html>
