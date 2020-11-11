@extends('layouts.voting_layout')
@section('title')
    Login Mahasiswa
@endsection
@section('content')
{{-- <div class="container-event-login">
    <div class="event-logo-login">
        <img class="logo-login" src="{{ asset('assets/pemira/images/logo/pwk.jpeg') }}" alt="logo">
    </div>
    <div class="line-divider">
        <h2 class="title-login-event">MENJADI PEMIMPIN BERINTEGRITAS DI ERA MILENIAL</h2>
    </div>
</div> --}}
<!-- Form / Container Login Box -->
<div class="container-login">
    <div class="line-divider">
        <hr>
        <h2 class="title-login">LOGIN</h2>
    </div>

    <!-- Input Username -->
    <form v-on:submit.prevent="login">
    {{-- <form> --}}
    {{csrf_field()}}
        <div class="line-divider">
            <div class="mdc-text-field mdc-text-field--outlined TBNoTes">
                <input v-model="form.nim" type="text" name="nim" class="mdc-text-field__input" id="text-field-hero-input" required>
                <div class="mdc-notched-outline">
                    <div class="mdc-notched-outline__leading"></div>
                    <div class="mdc-notched-outline__notch">
                        <label for="text-field-hero-input" class="mdc-floating-label">Username</label>
                    </div>
                    <div class="mdc-notched-outline__trailing"></div>
                </div>
            </div>
        </div>
        <!-- End Input Username -->
        <!-- Input Password -->
        <div class="line-divider">
            <div class="mdc-text-field mdc-text-field--outlined TBEmail ">
                <input v-model="form.password" type="password" name="password" class="mdc-text-field__input" id="text-field-hero-input" required>
                <div class="mdc-notched-outline">
                    <div class="mdc-notched-outline__leading"></div>
                    <div class="mdc-notched-outline__notch">
                        <label for="text-field-hero-input" class="mdc-floating-label">Password</label>
                    </div>
                    <div class="mdc-notched-outline__trailing"></div>
                </div>
            </div>
        </div>
        <!-- End Input Password -->
        <!-- Button -->
        <div class="line-divider">
            <div class="container-button-login">
                <button id="login" class=" mdc-button mdc-button--raised btn-login"
                    type="submit">masuk</button>
            </div>
        </div>
    </form>
</div>
@endsection
@push('script')
<script type="text/javascript">
    // deklarasi material component web
    
    let app = new Vue({
        el: '#app',
        vuetify: new Vuetify(),
        data: {
            mainData: '',
            form : new Form({
                nim: '',
                password: ''
            }),
            loadingStatus: 0,
            status: '',
            message: ''
        },
        mounted() {
            mdc.textField.MDCTextField.attachTo(document.querySelector('.TBNoTes'));
            mdc.textField.MDCTextField.attachTo(document.querySelector('.TBEmail'));
        },
        methods: {
            login(){
                Swal.fire({
                    title: 'Memproses Login Anda...',
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                this.form.post("{{ route('mahasiswa.login') }}")
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
                                window.location = "{{ route('mahasiswa.pemilihan') }}";
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
@endpush