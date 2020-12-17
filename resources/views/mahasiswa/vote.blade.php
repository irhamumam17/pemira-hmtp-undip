@extends('layouts.voting_layout')
@section('title')
    Pemilihan
@endsection
@section('content')
<div class="container-content">
    {{-- <div class="container-title-major-candidate-presma">
        <img class="img-title-major-capresma" src="{{ asset('assets/pemira_dependency/images/presma.png') }}" alt="">
    </div> --}}
    <!-- User Session -->
    <div class="container-session animate__animated animate__backInUp">
        <p class="txt-session-name">
            Halo, {{ Auth::guard('mahasiswa')->user()->name }}
            {{-- <a href="{{ route('mahasiswa.logout') }}"> Logout</a> --}}
        </p>
    </div>
    <!-- End User Session -->
    <div class="body-isi animate__animated animate__backInLeft">
        <!-- Box/Container Presma -->
            <div v-if="mainData !== ''" v-for="(item,index) in mainData" class="container-candidate-presma">
                <div class="container-number-candidate-presma">
                    <p class="txt-number-candidate-presma">@{{ mainData=='' ? '0' : item.nomor_urut  }}</p>
                </div>
                <div class="box-presma">
                    <div class="container-presma-img">
                        <img class="img-candidate-presma" :src="item.foto" alt="">
                    </div>
                    <div class="container-presma-name">
                        <div class="candidate-presma">
                            <div class="container-name-candidate-presma">
                                <p class="txt-name-candidate-presma">@{{ mainData=='' ? 'Ketua' : item.ketua.name }}</p>
                            </div>
                        </div>
                        <div class="candidate-presma">
                            <div class="container-name-candidate-presma">
                                <p class="txt-name-candidate-presma">@{{ mainData=='' ? 'Wakil' : item.wakil.name }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="container-visi-candidate-presma">
                        {{-- <button class="txt-visi-candidate-presma mdc-button mdc-button--outlined">Visi dan Misi</button> --}}
                        <template>
                            <div class="text-center">
                              <v-dialog
                                v-model="dialogVM"
                                scrollable
                                width="500"
                              >
                                <template v-slot:activator="{ on, attrs }">
                                  <v-btn
                                    color="red lighten-2"
                                    dark
                                    v-bind="attrs"
                                    v-on="on"
                                    @click="showModal(item)"
                                    width=100%
                                  >
                                    Visi Dan Misi
                                  </v-btn>
                                </template>
                          
                                <v-card>
                                  <v-card-title class="headline green lighten-1">
                                    <div style="color: white">Visi Dan Misi - Paslon @{{ formModal.nomor_urut }}</div>
                                  </v-card-title>
                          
                                  <v-card-text class="box-visi-misi">
                                    <div class="modal-visi">
                                        <div class="modal-visi-title">Visi :</div>
                                        <p v-html="formModal.visi"></p>
                                    </div>
                                    <div class="modal-misi">
                                        <div class="modal-misi-title">Misi :</div>
                                        <p v-html="formModal.misi"></p>
                                    </div>
                                  </v-card-text>
                          
                                  <v-divider></v-divider>
                          
                                  <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn
                                      color="primary"
                                      text
                                      @click="dialogVM = false"
                                    >
                                      OK
                                    </v-btn>
                                  </v-card-actions>
                                </v-card>
                              </v-dialog>
                            </div>
                          </template>
                    </div>
                </div>
                <div class="container-button-presma">
                    <template>
                        <v-row justify="center">
                          <v-dialog
                            v-model="dialogConfirm"
                            persistent
                            max-width="290"
                          >
                            <template v-slot:activator="{ on, attrs }">
                              <v-btn
                                color="#6200ee"
                                dark
                                v-bind="attrs"
                                v-on="on"
                                @click="showConfirm(item)"
                              >
                                Pilih
                              </v-btn>
                            </template>
                            <v-card>
                              <v-card-title class="headline">
                                Lanjutkan Pemilihan?
                              </v-card-title>
                              <v-card-text>Pastikan Anda Memilih Paslon Sesuai Hati Nurani Anda.</v-card-text>
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
                                  :loading="loading"
                                  :disabled="loading"
                                  text
                                  {{-- persistent --}}
                                  @click="submitPilihan"
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
        <!-- End Box/Container Presma -->
    </div>
</div>
<template>
    <div class="text-center">
      <v-dialog
        v-model="dialogError"
        scrollable
        width="500"
      >
        <v-card>
          <v-card-title class="headline green lighten-1">
            <div style="color: white">Terjadi Kesalahan..</div>
          </v-card-title>
  
          <v-card-text class="box-visi-misi">
            <div class="modal-visi">
                @{{ errorMessage }}
            </div>
          </v-card-text>
  
          <v-divider></v-divider>
  
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
              color="primary"
              text
              @click="dialogError = false"
            >
              OK
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </div>
  </template>
@endsection
@push('script')
    <script>
        let $user = "{{ Auth::guard('mahasiswa')->user()->nim }}";
        let app = new Vue({
        el: '#app',
        vuetify: new Vuetify(),
        data: {
            mainData: '',
            form : new Form({
                nim: $user,
                id_calon: '',
                nomor_urut: '',
            }),

            dialogVM: false,
            dialogConfirm: false,
            dialogError: false,

            formModal: new Form({
                nomor_urut: '',
                visi: '',
                misi: '',
            }),
            loading : false,
            loadingPage: false,
            errorMessage: '',
        },
        mounted() {
            this.getData();
        },
        methods: {
            getData(){
                axios.get("{{ route('mahasiswa.get_calon') }}")
                    .then(response => {
                        this.mainData = response.data.data
                    })
                    .catch(e => {
                        Swal.fire(
                            'Terjadi Kesalahan',
                            e.response.data.message,
                            'error'
                        )
                    })
            },
            showModal(data){
                // data.misi = JSON.parse(data.misi);
                this.formModal.fill(data);
            },
            showConfirm(data){
                this.form.id_calon = data.id;
                this.form.nomor_urut = data.nomor_urut;
                // dialogConfirm=true;
            },
            submitPilihan(){
                this.loading = true;
                this.form.post("{{ route('mahasiswa.pemilihan_temporary_post') }}")
                    .then(response => {
                        this.loading = false;
                        this.dialogConfirm = false;
                        if(response.data.status=='failed'){
                            this.errorMessage = response.data.message
                            this.dialogError = true
                        }else{
                            window.location = "{{ route('mahasiswa.identification_view') }}";
                        }
                    })
                    .catch(e => {
                        this.errorMessage = e.response.data.message
                        this.dialogError = true
                    })
            }          
        }
    });
    </script>
@endpush