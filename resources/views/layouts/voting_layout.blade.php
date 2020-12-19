<!DOCTYPE html>
<html lang="en">

<head>
    <title>PEMIRA HMTP 2021 | @yield('title')</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('mahasiswa.includes.assets.css')
    <style>
        /* Start by setting display:none to make this hidden.
        Then we position it in relation to the viewport window
        with position:fixed. Width, height, top and left speak
        for themselves. Background we set to 80% white with
        our animation centered, and no-repeating */
        .modal {
            display:    none;
            position:   fixed;
            z-index:    1000;
            top:        0;
            left:       0;
            height:     100%;
            width:      100%;
            background: rgba( 255, 255, 255, .8 ) 
                        url('../assets/pemira/images/Preloader_2.gif') 
                        50% 50% 
                        no-repeat;
        }

        /* When the body has the loading class, we turn
        the scrollbar off with overflow:hidden */
        body.loading .modal {
            overflow: hidden;   
        }

        /* Anytime the body has the loading class, our
        modal element will be visible */
        body.loading .modal {
            display: block;
        }
    </style>
    @stack('css')
</head>

<body>
    <div id="app">
        <v-app style="background-color: transparent">
        {{-- <div class="modal"><!-- Place at bottom of page --></div> --}}
        <div class="container-body">
            @include('mahasiswa.includes.header')
            @yield('content')
        </div>
        </v-app>
    </div>
@include('mahasiswa.includes.assets.js')
<script>
    // let $body = $("body");
    // $(document).on({
    //     ajaxStart: function() { $body.addClass("loading");    },
    //     ajaxStop: function() { $body.removeClass("loading"); }    
    // });
</script>
@stack('script')
</body>
</html>