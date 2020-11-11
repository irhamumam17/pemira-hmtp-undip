@extends('layouts.voting_layout')
@section('title')
    Verifikasi
@endsection
@section('content')
<div class="container-confirmation p-5">
    <h4 class="title-confirmation">HALAMAN VERIFIKASI</h4>
    <div class="divider"></div>

    {{-- <h5 class="text-center">Terima kasih telah menggunakan hak suara anda</h5> --}}
    <ul class="verified">
        <li>
            Selanjutnya, Silahkan upload foto KTP/KTM atau Identitas lainnya sebagai bukti bahwa anda telah melakukan pemilihan
        </li>
        <li>
            Jika anda tidak mengupload KTP/KTM atau Identitas lainnya, maka suara anda tidak akan dihitung
        </li>
        <li>Ukuran file maksimal 4 MB dengan extensi .jpg , .jpeg , atau .png</li>
    </ul>

    <form id="form-upload-ktm" enctype="multipart/form-data">
        {{csrf_field()}}
        {{-- Preview KTP/KTM Sebelum Upload. Jika dibutuhkan dipakai, jika tidak tidak papa --}}
        {{-- Atau bisa dialihfungsikan sebagai contoh foto yang harus diupload --}}
        <div class="container-img-preview">
            <img class="img-preview" id="preview" :src="imgURL">
        </div>
        <hr>
        <!-- Input KTP/KTM -->
        <label class="mdc-text-field mdc-text-field--fullwidth mdc-text-field--textarea mdc-text-field--no-label">
            <input type="file" class="mdc-text-field__input" name="foto" accept="image/*" id="photo" required autofocus @change="readURL">
            <span class="mdc-notched-outline">
                <span class="mdc-notched-outline__leading"></span>
                <span class="mdc-notched-outline__trailing"></span>
            </span>
        </label>
        <!-- End Input KTP/KTM -->
        {{-- @include('pemira.includes.errors') --}}
        <div class="divider"></div>
        <h6>Apakah anda sudah yakin dengan pilihan anda dan bersedia untuk melanjutkan proses selanjutnya ?
            {{-- Preview KTP/KTM Sebelum Upload. Jika dibutuhkan dipakai, jika tidak tidak papa
    Atau bisa dialihfungsikan sebagai contoh foto yang harus diupload
    <div class="container-button-confirmation">
        <a href="#" id="dialog-upload" class="mx-auto mdc-button mdc-button--raised btn-margin-10">Upload</a>
    </div>

    <div class="divider"></div>
    </h6> --}}
    <div class="container-button-confirmation">
        <button type="button" @click="upload" class="mdc-button mdc-button--raised btn-margin-10 w-full">Review Pilihan</button>
    </div>
    </form>
</div>
@endsection
@push('script')
    <script>

        let app = new Vue({
        el: '#app',
        vuetify: new Vuetify(),
        data: {
            mainData: '',
            form : new Form({
                foto: '',
            }),
            imgURL: "{{ asset('assets/pemira/images/default/no-photo-available.png') }}",
        },
        mounted() {
            
        },
        methods: {
            readURL(e) {
                const file = e.target.files[0];
                if(file){
                    this.imgURL = URL.createObjectURL(file);
                    this.form.foto = file;

                    let fd= new FormData()
                    
                    fd.append('foto', file)
                    Swal.fire({
                        title: 'Mengupload Foto Identitas Anda...',
                        allowEscapeKey: false,
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    axios.post("{{ route('mahasiswa.identification_upload') }}", fd)
                        .then(response => {
                            if(response.data.status=='failed'){
                                Swal.fire(
                                    'Gagal',
                                    response.data.message,
                                    'error'
                                )
                            }else{
                                this.form.foto = response.data.data
                                Swal.fire(
                                    'Berhasil',
                                    response.data.message,
                                    'success',
                                    2000
                                )
                            }
                        })
                }
            },
            upload(){
                Swal.fire({
                    title: 'Loading...',
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                this.form.post("{{ route('mahasiswa.identification_post') }}")
                    .then(response => {
                        console.log(response.data);
                        if(response.data.status=='failed'){
                            Swal.fire(
                                'Gagal',
                                response.data.message,
                                'error'
                            )
                        }else{
                            window.location = "{{ route('mahasiswa.review') }}";
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
            }
        }
    });
    </script>
@endpush