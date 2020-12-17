<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PEMIRA HMTP 2021</title>
    @include('mahasiswa.includes.assets.landingpage_css')
	{{-- <link href="{{ mix('css/app.css') }}" rel="stylesheet"> --}}
    <script src="{{ asset('js/app.js') }}"></script>
    {{-- <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css"> --}}
</head>

<body>
    <div class="app">
        <v-app>
            @include('vendor.sweet.alert')
            <div class="circle">
                <div class="content" id="circle-btn">
                    <div class="container-title">
                        <p class="txt-title animate__animated animate__backInDown">PEMIRA<br>HMTP 2021</p>
                        <p class="txt-subtitle animate__animated animate__backInUp">KLIK DISINI</p>
                    </div>
                </div>
            </div>
        </v-app>
    </div>
</body>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script> --}}
@include('mahasiswa.includes.assets.landingpage_js')
<!-- ================================================================================================================================================================================= -->
<!-- Button Function -->
<script type="text/javascript">
    document.getElementById("circle-btn").onclick = function () {
        location.href = "{{route('mahasiswa.login')}}";
    };
</script>
</html>