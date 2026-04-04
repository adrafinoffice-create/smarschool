<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Smart School</title>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>


</head>

<body class="bg-light">


    <div class="container-fluid">

        <div class="row">

            <div class="col-md-2 sidebar bg-white py-1 py-md-4 px-md-3 px-1">

                @include('components.aside')

            </div>


            <div class="col-md-10">
                <main>
                    <section class="py-5 px-1 px-md-3">
                        @yield('content')
                    </section>
                </main>
            </div>
        </div>
    </div>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js">
        < /sc> <
        script nomodule src = "https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js" >
    </script>

    <script>
        function toggleMenu() {
            var sidebarMenu = document.getElementById('sidebarMenu');

            sidebarMenu.classList.toggle('d-block');
            sidebarMenu.classList.toggle('d-none');
        }
    </script>

    @stack('addon-script')

</body>

</html>
