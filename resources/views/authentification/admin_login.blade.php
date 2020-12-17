<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PEMIRA HMTP 2021 | Log in</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('assets/admin/plugins/fontawesome-free/css/all.min.css')}}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{asset('assets/admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('assets/admin/dist/css/adminlte.min.css')}}">
  <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body class="hold-transition login-page">
    <div id="app">
<div class="login-box">
  <div class="login-logo">
    <a href="{{route('home')}}"><b>PEMIRA</b> UNDIP 2021</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Sign in to start your session</p>

      <form v-on:submit.prevent="login">
        {{csrf_field()}}
        <div class="input-group mb-3">
          <input v-model="form.username" type="text" class="form-control" placeholder="Username">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input v-model="form.password" type="password" class="form-control" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <!-- /.col -->
          <div class="col-4 mx-auto">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->
</div>
<!-- jQuery -->
<script src="{{asset('assets/admin/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('assets/admin/dist/js/adminlte.js')}}"></script>
<script src="{{ asset('js/app.js') }}"></script>

<script>
    let app = new Vue({
        el: '#app',
        vuetify: new Vuetify(),
        data: {
            mainData: '',
            form : new Form({
                username: '',
                password: ''
            }),
            loadingStatus: 0,
        },
        mounted() {
            
        },
        methods: {
            login(){
                Swal.fire({
                    title: 'Please Wait...',
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                this.form.post("{{ route('admin.login') }}")
                    .then(response => {
                        if(response.data.status=='failed'){
                            Swal.fire(
                                'Gagal',
                                response.data.message,
                                'error'
                            )
                        }else if(response.data.status=='success'){
                            Swal.fire(
                                'Berhasil',
                                response.data.message,
                                'success'
                            ).then((value) => {
                                window.location = "{{ route('admin.dashboard') }}";
                            })
                        }
                    })
                    .catch(e => {
                        console.log(e.response.data.message);
                        Swal.fire(
                            'Gagal',
                            e.response.data.message,
                            'error'
                        )
                    })
                this.form.clear()
            }
        }
    });
</script>
</body>
</html>
