<!DOCTYPE html>
<html lang="en">

@include('backend.layouts.head')

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        @include('backend.layouts.sidebar')
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                @include('backend.layouts.header')
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                {{-- <div>
                    @if (session()->has('success'))
                        <div class="alert alert-success">
                            {{ session()->get('message') }}
                        </div>
                    @endif
                    @if (session()->has('error'))
                        <div class="alert alert-danger">
                            {{ session()->get('error') }}
                        </div>
                        <!-- /.container-fluid -->
                    @endif
                </div> --}}
                @yield('main-content')

            </div>
            <!-- End of Main Content -->
            @include('backend.layouts.footer')

</body>

</html>
