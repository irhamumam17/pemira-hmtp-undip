@extends('layouts.voting_layout')
@section('title')
    Pemilihan
@endsection
@push('css')
    <!-- summernote -->
    <link rel="stylesheet" href="{{asset('assets/admin/plugins/summernote/summernote-bs4.min.css') }}">
    <style>
        .swal-modal .swal-text {
            text-align: left;
        }
    </style>
@endpush
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
        @include('mahasiswa.includes.errors')
    </div>
    <!-- End User Session -->
    <div class="body-isi animate__animated animate__backInLeft">
        <!-- Box/Container Presma -->
            <!--<div v-if="mainData !== ''" v-for="(item,index) in mainData" class="container-candidate-presma">-->
            @foreach($paslon as $p)
            <div class="container-candidate-presma">
                <div class="container-number-candidate-presma">
                    <!--<p class="txt-number-candidate-presma">@{{ mainData=='' ? '0' : item.nomor_urut  }}</p>-->
                    <p class="txt-number-candidate-presma">{{ $p->nomor_urut }}</p>
                </div>
                <div class="box-presma">
                    <div class="container-presma-img">
                        <!--<img class="img-candidate-presma" :src="item.foto" alt="">-->
                        <img class="img-candidate-presma" src="{{ $p->foto }}" alt="">
                    </div>
                    <div class="container-presma-name">
                        <div class="candidate-presma">
                            <div class="container-name-candidate-presma">
                                <!--<p class="txt-name-candidate-presma">@{{ mainData=='' ? 'Ketua' : item.ketua.name }}</p>-->
                                <p class="txt-name-candidate-presma">{{ $p->ketua->name }}</p>
                            </div>
                        </div>
                        <div class="candidate-presma">
                            <div class="container-name-candidate-presma">
                                <!--<p class="txt-name-candidate-presma">@{{ mainData=='' ? 'Wakil' : item.wakil.name }}</p>-->
                                <p class="txt-name-candidate-presma">{{ $p->wakil->name }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="container-visi-candidate-presma">
                        <button class="txt-visi-candidate-presma mdc-button mdc-button--outlined" onclick="displayVM({{$p->nomor_urut}})">Visi dan Misi</button>
                        <input type="hidden" id="id_calon{{$p->nomor_urut}}" value="{{$p->id}}"/>
                        <input type="hidden" value="{{$p->visi}}" id="visi{{$p->nomor_urut}}"/>
                        <input type="hidden" value="{{$p->misi}}" id="misi{{$p->nomor_urut}}"/>
                        <!--<template>-->
                        <!--    <div class="text-center">-->
                        <!--      <v-dialog-->
                        <!--        v-model="dialogVM"-->
                        <!--        scrollable-->
                        <!--        width="500"-->
                        <!--      >-->
                        <!--        <template v-slot:activator="{ on, attrs }">-->
                        <!--          <v-btn-->
                        <!--            color="red lighten-2"-->
                        <!--            dark-->
                        <!--            v-bind="attrs"-->
                        <!--            v-on="on"-->
                        <!--            @click="showModal(item)"-->
                        <!--            width=100%-->
                        <!--          >-->
                        <!--            Visi Dan Misi-->
                        <!--          </v-btn>-->
                        <!--        </template>-->
                          
                        <!--        <v-card>-->
                        <!--          <v-card-title class="headline green lighten-1">-->
                        <!--            <div style="color: white">Visi Dan Misi - Paslon @{{ formModal.nomor_urut }}</div>-->
                        <!--          </v-card-title>-->
                          
                        <!--          <v-card-text class="box-visi-misi">-->
                        <!--            <div class="modal-visi">-->
                        <!--                <div class="modal-visi-title">Visi :</div>-->
                        <!--                <p v-html="formModal.visi"></p>-->
                        <!--            </div>-->
                        <!--            <div class="modal-misi">-->
                        <!--                <div class="modal-misi-title">Misi :</div>-->
                        <!--                <p v-html="formModal.misi"></p>-->
                        <!--            </div>-->
                        <!--          </v-card-text>-->
                          
                        <!--          <v-divider></v-divider>-->
                          
                        <!--          <v-card-actions>-->
                        <!--            <v-spacer></v-spacer>-->
                        <!--            <v-btn-->
                        <!--              color="primary"-->
                        <!--              text-->
                        <!--              @click="dialogVM = false"-->
                        <!--            >-->
                        <!--              OK-->
                        <!--            </v-btn>-->
                        <!--          </v-card-actions>-->
                        <!--        </v-card>-->
                        <!--      </v-dialog>-->
                        <!--    </div>-->
                        <!--  </template>-->
                    </div>
                </div>
                <div class="container-button-presma">
                    <button id="pilih" class=" mdc-button mdc-button--raised btn-login"
                    type="button" onclick="submitPilihan({{ Auth::guard('mahasiswa')->user()->nim }},{{$p->id}},{{$p->nomor_urut}})">pilih</button>
                    <!--<template>-->
                    <!--    <v-row justify="center">-->
                    <!--      <v-dialog-->
                    <!--        v-model="dialogConfirm"-->
                    <!--        persistent-->
                    <!--        max-width="290"-->
                    <!--      >-->
                    <!--        <template v-slot:activator="{ on, attrs }">-->
                    <!--          <v-btn-->
                    <!--            color="#6200ee"-->
                    <!--            dark-->
                    <!--            v-bind="attrs"-->
                    <!--            v-on="on"-->
                    <!--            @click="showConfirm(item)"-->
                    <!--          >-->
                    <!--            Pilih-->
                    <!--          </v-btn>-->
                    <!--        </template>-->
                    <!--        <v-card>-->
                    <!--          <v-card-title class="headline">-->
                    <!--            Lanjutkan Pemilihan?-->
                    <!--          </v-card-title>-->
                    <!--          <v-card-text>Pastikan Anda Memilih Paslon Sesuai Hati Nurani Anda.</v-card-text>-->
                    <!--          <v-card-actions>-->
                    <!--            <v-spacer></v-spacer>-->
                    <!--            <v-btn-->
                    <!--              color="green darken-1"-->
                    <!--              text-->
                    <!--              @click="dialogConfirm = false"-->
                    <!--            >-->
                    <!--              Batal-->
                    <!--            </v-btn>-->
                    <!--            <v-btn-->
                    <!--              color="green darken-1"-->
                    <!--              :loading="loading"-->
                    <!--              :disabled="loading"-->
                    <!--              text-->
                    <!--              {{-- persistent --}}-->
                    <!--              @click="submitPilihan"-->
                    <!--            >-->
                    <!--              Ok-->
                    <!--            </v-btn>-->
                    <!--          </v-card-actions>-->
                    <!--        </v-card>-->
                    <!--      </v-dialog>-->
                    <!--    </v-row>-->
                    <!--  </template>-->
                </div>
            </div>
        <!-- End Box/Container Presma -->
        @endforeach
    </div>
</div>
<!--<template>-->
<!--    <div class="text-center">-->
<!--      <v-dialog-->
<!--        v-model="dialogError"-->
<!--        scrollable-->
<!--        width="500"-->
<!--      >-->
<!--        <v-card>-->
<!--          <v-card-title class="headline green lighten-1">-->
<!--            <div style="color: white">Terjadi Kesalahan..</div>-->
<!--          </v-card-title>-->
  
<!--          <v-card-text class="box-visi-misi">-->
<!--            <div class="modal-visi">-->
<!--                @{{ errorMessage }}-->
<!--            </div>-->
<!--          </v-card-text>-->
  
<!--          <v-divider></v-divider>-->
  
<!--          <v-card-actions>-->
<!--            <v-spacer></v-spacer>-->
<!--            <v-btn-->
<!--              color="primary"-->
<!--              text-->
<!--              @click="dialogError = false"-->
<!--            >-->
<!--              OK-->
<!--            </v-btn>-->
<!--          </v-card-actions>-->
<!--        </v-card>-->
<!--      </v-dialog>-->
<!--    </div>-->
<!--  </template>-->
<!-- MODAL -->
<form id="formSubmit" action="{{route('mahasiswa.pemilihan_temporary_post')}}" method="post">
    {{csrf_field()}}
    <input type="hidden" name="nim" id="nim"/>
    <input type="hidden" name="id_calon" id="id_calon"/>
    <input type="hidden" name="nomor_urut" id="nomor_urut"/>
</form>
<div id="modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" id="modal">
      <div class="modal-content">
          <div class="modal-header ">
              <h4 class="modal-title" id="myLargeModalLabel"></h4>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          </div>
              <div class="modal-body mx-4">
                  <div class="form-row">
                      <label class="col-lg-2" for="visi">Visi</label>
                      <div class="form-group col-md-8">
                        <textarea class="form-control" name="visi" id="visi" rows="4"">
                        </textarea>
                      </div>
                  </div>
                  <div class="form-row">
                      <label class="col-lg-2" for="misi">Misi</label>
                      <div class="form-group col-md-8">
                        <textarea  class="form-control" name="misi" id="misi" rows="4">
                        </textarea>
                      </div>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-light" data-dismiss="modal">OK</button>
              </div>

          </form>

      </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endsection
@push('script')
    <script src="{{ asset('assets/admin/plugins/summernote/summernote-bs4.min.js') }}"></script>
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
    function displayVM(nomor_urut){
        let visi = $('#visi'+nomor_urut).val();
        let misi = $('#misi'+nomor_urut).val();
        $("#visi").summernote("code", $('#visi'+nomor_urut).val());
        $("#misi").summernote("code", $('#misi'+nomor_urut).val());
        $("myLargeModalLabel").text("Visi Dan Misi Paslon "+nomor_urut);
        <!--$('#modal').modal('show');-->
        Swal.fire({
          title: '<strong>VISI Dan Misi Paslon '+nomor_urut+'</strong>',
          icon: 'info',
          html:
            '<b>Visi :</b><br> ' +visi+
            '<br><br><b>Misi :</b><br>'+misi
            ,
          showCloseButton: true,
          focusConfirm: false,
          confirmButtonText:
            'OK',
        })
    }
    function submitPilihan(nim,id_calon,nomor_urut){
        $("#formSubmit #nim").val(nim);
        $("#formSubmit #id_calon").val(id_calon);
        $("#formSubmit #nomor_urut").val(nomor_urut);
        Swal.fire({
            title: 'Pilih Pasangan Calon Ini?',
            text: "Yakin lah pada pilihan anda",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes!'
        }).then((result) => {
            if (result.value) {
              Swal.fire({
                  title: 'Please Wait...',
                  allowEscapeKey: false,
                  allowOutsideClick: false,
                  didOpen: () => {
                      Swal.showLoading();
                  }
              });
              $("#formSubmit").submit();
            }
        })
    }
    </script>
@endpush