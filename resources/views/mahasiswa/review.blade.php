@extends('layouts.voting_layout')
@section('title')
    Verifikasi
@endsection
@section('content')
<div class="container-confirmation">
    <h4 class="title-confirmation">HALAMAN KONFIRMASI</h4>
    <h5>Pasangan Calon Yang Anda Pilih Dan Kartu Identitas Yang Anda Unggah :</h5>
    <!-- START CONTENT -->
    <div class="container-detail-confirmation">
        <!-- Candidate Presma -->
        <div class="container-candidate-confirmation">
            <h6 class="txt-title-confirmation">Pasangan Calon :</h6>
            <div class="container-candidate-detail">
                <p class="txt-number-confirmation">@{{ mainData.paslon.nomor_urut }}</p>
                <div class="container-candidate-profile">
                    <div class="container-candidate-confirmation-image">
                        <img src="{{ asset('assets/pemira/images/calon/presma.png') }}" alt="" class="img-candidate-confirmation-presma">
                    </div>
                    <div class="container-candidate-name-confirmation">
                        <div class="container-candidate-confirmation-detail">
                            <p class="txt-profile-name-candidate">@{{ mainData.paslon.ketua.name }}</p>
                        </div>
                        <div class="container-candidate-confirmation-detail">
                            <p class="txt-profile-name-candidate">@{{ mainData.paslon.wakil.name }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Candidate Presma -->
        <!-- Candidate BPM -->
        <div class="container-candidate-confirmation">
            <h6 class="txt-title-confirmation">Foto Identitas :</h6>
            <div class="container-img-preview">
                <img class="img-preview" id="preview" :src="mainData.foto">
            </div>
        </div>
        <!-- End Candidate BPM -->
    <!-- END CONTENT -->
    <div class="divider"></div>
    <h6 class="text-justify">Apakah anda sudah yakin dengan pilihan anda ?
    </h6>
    <div class="container-button-confirmation">
        <button class=" mdc-button mdc-button--raised btn-margin-10" @click="dialogRevote=true">Tidak</button>
        <button type="button" class=" mdc-button mdc-button--raised btn-margin-10" @click="dialogConfirm=true">Ya</button>
        <template>
            <v-row justify="center">
              <v-dialog
                v-model="dialogConfirm"
                persistent
                max-width="290"
              >
                <v-card>
                  <v-card-title class="headline">
                    Selesai Pemilihan?
                  </v-card-title>
                  <v-card-text>Pastikan Paslon Sesuai Pilihan Anda Dan Foto Identitas Sesuai Dengan Foto Yang Anda Upload .</v-card-text>
                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                      color="green darken-1"
                      text
                      @click="dialogConfirm = false"
                    >
                      Batal
                    </v-btn>
                    <v-btn
                      color="green darken-1"
                      :loading="loadingConfirm"
                      :disabled="loadingConfirm"
                      text
                      @click="submitPilihan"
                    >
                      Ok
                    </v-btn>
                  </v-card-actions>
                </v-card>
              </v-dialog>
            </v-row>
          </template>
        <template>
            <v-row justify="center">
              <v-dialog
                v-model="dialogRevote"
                persistent
                max-width="290"
              >
                <v-card>
                  <v-card-title class="headline">
                    Ulangi Pemilihan?
                  </v-card-title>
                  <v-card-text>Anda akan mengulangi pemilihan dari awal.</v-card-text>
                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                      color="green darken-1"
                      text
                      @click="dialogRevote = false"
                    >
                      Batal
                    </v-btn>
                    <v-btn
                      color="green darken-1"
                      :loading="loading"
                      :disabled="loading"
                      text
                      @click="submitRevote"
                    >
                      Ok
                    </v-btn>
                  </v-card-actions>
                </v-card>
              </v-dialog>
            </v-row>
          </template>
    </div>
</div>
</div>
@endsection
@push('script')
    <script>
        let data = @json($tempPemilihan);
        let app = new Vue({
            el: '#app',
            vuetify: new Vuetify(),
            data: {
                mainData: data,
                dialogConfirm: false,
                dialogRevote: false,
                loading: false,
                loadingConfirm: false,
            },

            mounted() {
                
            },
            methods: {
                submitPilihan(){
                    this.loadingConfirm=true
                    axios.get("{{ route('mahasiswa.pemilihan_post') }}")
                        .then(response => {
                            this.dialogConfirm= false
                            if(response.data.status=='failed'){
                                Swal.fire(
                                    'Gagal',
                                    response.data.message,
                                    'error'
                                )
                            }else{
                                Swal.fire(
                                    'Berhasil',
                                    response.data.message,
                                    'success',
                                ).then((result)=>{
                                    window.location = "{{route('home')}}"
                                })
                            }
                        })
                },
                submitRevote(){
                    this.dialogRevote=false
                    Swal.fire({
                        title: 'Loading...',
                        allowEscapeKey: false,
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    axios.get("{{ route('mahasiswa.revote') }}")
                        .then(response => {
                            this.dialogConfirm= false
                            if(response.data.status=='failed'){
                                Swal.fire(
                                    'Gagal',
                                    response.data.message,
                                    'error'
                                )
                            }else{
                                window.location = "{{route('mahasiswa.pemilihan')}}"
                            }
                        })
                }
            }
        })
    </script>
@endpush