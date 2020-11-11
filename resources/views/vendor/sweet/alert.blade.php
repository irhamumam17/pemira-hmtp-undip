@if (Session::has('sweet_alert.alert'))
    <script>
        @if (Session::has('sweet_alert.content'))
            var config = {!! Session::pull('sweet_alert.alert') !!}
            var content = document.createElement('div');
            content.insertAdjacentHTML('afterbegin', config.content);
            config.content = content;
            Swal(config);
        @else
            Swal({!! Session::pull('sweet_alert.alert') !!});
        @endif
    </script>
@endif
