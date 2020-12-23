@extends('layouts.admin_layout')
@section('title')
    Kelola Mahasiswa
@endsection

@push('css')
    <!-- DataTables -->
  <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">

  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css" rel="stylesheet"/>
  <!-- Tempusdominus Bootstrap 4 -->
@endpush

@section('content')
<section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Data Mahasiswa</h3>
              <button type="button" class="btn btn-success btn-rounded float-right" @click="createModal()">
                <i class="fas fa-plus-circle"> Tambah Data</i>
              </button>
              <button type="button" class="btn btn-primary btn-rounded float-right mr-1" @click="importModal()">
                <i class="fas fa-upload"> Upload Data</i>
              </button>
              <button type="button" class="btn btn-secondary btn-rounded float-right mr-1" @click="getData()">
                <i class="fas fa-sync-alt"> Refresh</i>
              </button>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>No.</th>
                  <th>NIM</th>
                  <th>Nama</th>
                  <th>Angkatan</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(item,index) in mainData">
                  <td>@{{ index+1 }}</td>
                  <td>@{{ item.nim }}</td>
                  <td>@{{ item.name }}</td>
                  <td>@{{ item.angkatan }}</td>
                  <td>
                    <span v-if="item.status==0" class="badge badge-danger">
                      Belum memilih
                    </span>
                    <span v-if="item.status==1" class="badge badge-warning">
                      Sedang login
                    </span>
                    <span v-if="item.status==2" class="badge badge-success">
                      Sudah memilih
                    </span>
                  </td>
                  <td>
                    <a href="javascript:void(0)" @click="resetModal(item.id)" class="text-success"
                      data-toggle="tooltip" data-placement="top" title="Reset Akun / Password">
                      <i class="fas fa-redo"></i>
                    </a>
                    <a href="javascript:void(0)" @click="sendAccount(item.id)" class="text-primary"
                      data-toggle="tooltip" data-placement="top" title="Email Akun">
                      <i class="fas fa-paper-plane"></i>
                    </a>
                    <a v-if="item.status!=1" href="javascript:void(0)" @click="editModal(item)" class="text-warning"
                      data-toggle="tooltip" data-placement="top" title="Edit">
                      <i class="fas fa-edit"></i>
                    </a>
                    <a v-if="item.status!=1" href="javascript:void(0)" @click="deleteModal(item.id)" class="text-danger"
                      data-toggle="tooltip" data-placement="top" title="Hapus">
                      <i class="fas fa-trash"></i>
                    </a>
                    <a href="javascript:void(0)" @click="detailModal(item)" class="text-secondary"
                      data-toggle="tooltip" data-placement="top" title="Info">
                      <i class="fas fa-info-circle"></i>
                    </a>
                  </td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <th>No.</th>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Angkatan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- /.content -->
  <!-- MODAL -->
<div id="modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" id="modal">
      <div class="modal-content">
          <div class="modal-header ">
              <h4 class="modal-title" v-show="mode=='create'" id="myLargeModalLabel">Tambah Data</h4>
              <h4 class="modal-title" v-show="mode=='edit'" id="myLargeModalLabel">Edit Data</h4>
              <h4 class="modal-title" v-show="mode=='info'" id="myLargeModalLabel">Info Data</h4>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          </div>

          <form @submit.prevent="mode=='edit' ? updateData() : (mode=='create' ? storeData() : '')" @keydown="form.onKeydown($event)" id="form">
              <div class="modal-body mx-4">
                  <div class="form-row">
                      <label class="col-lg-2" for="nim">NIM</label>
                      <div class="form-group col-md-8">
                          <input v-model="form.nim" id="nim" type="text" min=0 placeholder="Masukkan NIM"
                              class="form-control" :class="{ 'is-invalid': form.errors.has('nim') }" :readonly="mode=='info'">
                          <has-error :form="form" field="nim"></has-error>
                      </div>
                  </div>
                  <div class="form-row">
                      <label class="col-lg-2" for="name">Nama</label>
                      <div class="form-group col-md-8">
                          <input v-model="form.name" id="name" type="text" min=0 placeholder="Masukkan Nama"
                              class="form-control" :class="{ 'is-invalid': form.errors.has('name') }" :readonly="mode=='info'">
                          <has-error :form="form" field="name"></has-error>
                      </div>
                  </div>
                  <div class="form-row">
                    <label class="col-lg-2" for="angkatan">Angkatan</label>
                    <div class="form-group col-md-8">
                        <input class="form-control" v-model="form.angkatan" @change="setAngkatan()" type="text" id="angkatan" placeholder="Masukkan Angkatan" :class="{ 'is-invalid': form.errors.has('angkatan') }" data-target="#datetimepicker6" :readonly="mode=='info'"/>
                        <has-error :form="form" field="angkatan"></has-error>
                    </div>
                  </div>
                  <div class="form-row">
                      <label class="col-lg-2" for="email">Email</label>
                      <div class="form-group col-md-8">
                          <input v-model="form.email" id="email" type="text" placeholder="Masukkan Email"
                              class="form-control" :class="{ 'is-invalid': form.errors.has('email') }" :readonly="mode=='info'">
                          <has-error :form="form" field="email"></has-error>
                      </div>
                  </div>
                  <div class="form-row" v-if="mode=='info'">
                      <label class="col-lg-2" for="hint_password">Password</label>
                      <div class="form-group col-md-8">
                          <input v-model="form.hint_password" id="hint_password" type="teks"
                              placeholder="Masukkan Password" class="form-control"
                              :class="{ 'is-invalid': form.errors.has('hint_password') }" readonly>
                          <has-error :form="form" field="hint_password"></has-error>
                      </div>
                  </div>
                  <div class="form-row">
                    <label class="col-lg-2" for="start_session">Mulai Sesi</label>
                    <div class="form-group col-md-8">
                        <div class="input-group date" id="datetimepicker7" data-target-input="nearest">
                            <input v-model="form.start_session" @change="setStartSession()" id="start_session" type="text" placeholder="Masukkan Mulai Sesi"
                                class="form-control datetimepicker-input" :class="{ 'is-invalid': form.errors.has('start_session') }" data-target="#datetimepicker7" :readonly="mode=='info'">
                            <div class="input-group-append" data-target="#datetimepicker7" data-toggle="datetimepicker">
                                <div class="input-group-text">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                            </div>
                            <has-error :form="form" field="start_session"></has-error>
                        </div>
                    </div>
                  </div>
                  <div class="form-row">
                    <label class="col-lg-2" for="end_session">Selesai Sesi</label>
                    <div class="form-group col-md-8">
                        <div class="input-group date" id="datetimepicker8" data-target-input="nearest">
                            <input v-model="form.end_session" @change="setEndSession()" id="end_session" type="text" placeholder="Masukkan Selesai Sesi"
                                class="form-control datetimepicker-input" :class="{ 'is-invalid': form.errors.has('end_session') }" data-target="#datetimepicker8" :readonly="mode=='info'">
                            <div class="input-group-append" data-target="#datetimepicker8" data-toggle="datetimepicker">
                                <div class="input-group-text">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                            </div>
                            <has-error :form="form" field="end_session"></has-error>
                        </div>
                    </div>
                  </div>
                  <div class="form-row" v-if="mode=='info'">
                    <label class="col-lg-2" for="status">Terakhir Login</label>
                    <div class="form-group col-md-8">
                        <input v-model="form.last_login" id="last_login" type="year" min=0 placeholder="Masukkan Terakhir Login"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('last_login') }" readonly>
                        <has-error :form="form" field="last_login"></has-error>
                    </div>
                  </div>
                  <div class="form-row" v-if="mode=='info'">
                    <label class="col-lg-2" for="status">Status</label>
                    <div class="form-group col-md-8">
                        <input v-model="form.status" id="status" type="year" min=0 placeholder="Masukkan Status"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('status') }" readonly>
                        <has-error :form="form" field="status"></has-error>
                    </div>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                  <button v-show="mode=='create'" type="submit" class="btn btn-primary">Simpan</button>
                  <button v-show="mode=='edit'" type="submit" class="btn btn-success">Ubah</button>
              </div>

          </form>

      </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
  <!-- MODAL -->
<div id="modalImport" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" id="modalImport">
      <div class="modal-content">
          <div class="modal-header ">
              <h4 class="modal-title" id="myLargeModalLabel">Import Mahasiswa</h4>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          </div>

          <form @submit.prevent="importData()" @keydown="form.onKeydown($event)" id="form">
              <div class="modal-body mx-4">
                  <div class="form-row">
                      <label class="col-lg-2" for="file">File</label>
                      <div class="form-group col-md-8">
                          <input id="file_import" type="file" placeholder="Masukkan File"
                              class="form-control" :class="{ 'is-invalid': form.errors.has('file') }">
                          <has-error :form="form" field="file"></has-error>
                      </div>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                  <button type="submit" class="btn btn-primary">Import</button>
              </div>

          </form>

      </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endsection
@push('script')
    <!-- DataTables -->
    <script src="{{asset('assets/admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{asset('assets/admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{asset('assets/admin/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{asset('assets/admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    {{-- <script src="{{asset('assets/admin/plugins/datepicker/datepicker.js')}}"></script> --}}
    <script src="{{asset('assets/admin/plugins/datepicker/tempusdominus-bootstrap-4.js')}}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js"></script>
    <script>
      jQuery(document).ready(function($) {
        'use strict';
        $('#datetimepicker6').datetimepicker({
            format:'YYYY',
            viewMode: "years",
            icons: {
                time: "far fa-clock",
                date: "fa fa-calendar-alt",
                up: "fa fa-arrow-up",
                down: "fa fa-arrow-down"
            }
        });
        $("#angkatan").datepicker({
            format: "yyyy",
            viewMode: "years", 
            minViewMode: "years"
        });
        $("#angkatan").on('changeDate',function(){
          app.setAngkatan();
        });
        if ($("#datetimepicker7").length) {
            $(function() {
                $('#datetimepicker7').datetimepicker({
                    format:'YYYY-MM-DD HH:mm',
                    icons: {
                        time: "far fa-clock",
                        date: "fa fa-calendar-alt",
                        up: "fa fa-arrow-up",
                        down: "fa fa-arrow-down"
                    }
                });
                $('#datetimepicker8').datetimepicker({
                    format:'YYYY-MM-DD HH:mm',
                    useCurrent: false,
                    icons: {
                        time: "far fa-clock",
                        date: "fa fa-calendar-alt",
                        up: "fa fa-arrow-up",
                        down: "fa fa-arrow-down"
                    }
                });
                $("#datetimepicker6").on("change.datetimepicker", function(e) {
                    app.setAngkatan();
                });
                $("#datetimepicker7").on("change.datetimepicker", function(e) {
                    $('#datetimepicker8').datetimepicker('minDate', e.date);
                    app.setStartSession();
                });
                $("#datetimepicker8").on("change.datetimepicker", function(e) {
                    // $('#datetimepicker7').datetimepicker('maxDate', e.date);
                    app.setEndSession();
                });
            });
        }
    });
        let app = new Vue({
        el: '#app',
        vuetify: new Vuetify(),
        data: {
            mainData: '',
            form : new Form({
                id: '',
                nim: '',
                name: '',
                angkatan: '',
                email: '',
                hint_password: '',
                start_session: '',
                end_session: '',
                last_login: '',
                status: '',
            }),
            formImport : new Form({
              file: ''
            }),
            loading : false,
            btnLoading: false,
            mode: '',
        },
        mounted() {
            this.getData();
            $("#angkatan").keypress(function(event){ event.preventDefault(); });
            $("#start_session").keypress(function(event){ event.preventDefault(); });
            $("#end_session").keypress(function(event){ event.preventDefault(); });
        },
        methods: {
          importModal(){
            $('#modalImport').modal('show')
          },
          importData(){
            const file = document.querySelector('#file_import');
                if(file){
                    // this.imgURL = URL.createObjectURL(file);
                    // this.formImport.file = file;

                    let fd= new FormData()
                    
                    fd.append('file', file.files[0]);
                    Swal.fire({
                        title: 'Mengimport Data Anda...',
                        allowEscapeKey: false,
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    axios.post("{{ route('admin.mahasiswa.import') }}", fd, {
                      headers : {
                        'Content-Type' : 'multipart/form-data'
                      }
                    })
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
                                ).then((result) => {
                                  $('#modalImport').modal('hide');
                                  this.getData()
                                })
                            }
                        })
                }
          },
          setAngkatan(){
            this.form.angkatan = $("#angkatan").val();
          },
          setStartSession(){
            this.form.start_session = $("#start_session").val();
            this.form.end_session = '';
          },
          setEndSession(){
            this.form.end_session = $("#end_session").val();
          },
          createModal() {
                this.mode = 'create';
                this.form.reset();
                this.form.clear();
                $('#modal').modal('show');
            },
          detailModal(data) {
                this.mode = 'info';
                this.form.reset();
                this.form.clear();
                this.form.fill(data);
                if(this.form.status==0){
                    this.form.status='Belum memilih';
                }else if(this.form.status==1){
                    this.form.status='Sedang login';
                }else{
                    this.form.status='Sudah memilih'
                }
                $('#modal').modal('show');
            },
            editModal(data) {
                this.mode = 'edit';
                this.form.reset();
                this.form.clear();
                this.form.fill(data);
                $('#modal').modal('show');
            },
            storeData() {
              Swal.fire({
                  title: 'Please Wait...',
                  allowEscapeKey: false,
                  allowOutsideClick: false,
                  didOpen: () => {
                      Swal.showLoading();
                  }
              });
                this.form.post("{{ route('admin.mahasiswa.store') }}")
                    .then(response => {
                      if(response.data.status=='failed'){
                          Swal.fire(
                            response.data.status,
                              response.data.message,
                              'error'
                          )
                        }else{
                          Swal.fire(
                              response.data.status,
                              response.data.message,
                              'success'
                          ).then((result) => {
                            $('#modal').modal('hide');
                            this.getData()
                          })
                        }
                      })
                      .catch(e => {
                        Swal.close();
                          e.status != 422 ? console.log(e) : '';
                      })
            },
            changeImage() {
                this.form.profil = $("#profil").val()
            },
            updateData() {
              Swal.fire({
                  title: 'Please Wait...',
                  allowEscapeKey: false,
                  allowOutsideClick: false,
                  didOpen: () => {
                      Swal.showLoading();
                  }
              });
                url = "{{ route('admin.mahasiswa.update', ':id') }}".replace(':id', this.form.id)
                this.form.put(url)
                    .then(response => {
                      if(response.data.status=='failed'){
                          Swal.fire(
                            response.data.status,
                              response.data.message,
                              'error'
                          )
                        }else{
                          Swal.fire(
                              response.data.status,
                              response.data.message,
                              'success'
                          ).then((result) => {
                            $('#modal').modal('hide');
                            this.getData()
                          })
                        }
                      })
                      .catch(e => {
                        Swal.close();
                          e.status != 422 ? console.log(e) : '';
                      })
            },

            deleteModal(id) {
                Swal.fire({
                    title: 'Hapus Mahasiswa Ini?',
                    text: "Aksi Ini Tidak Bisa Dikembalikan",
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
                        url = "{{ route('admin.mahasiswa.destroy', ':id') }}".replace(':id', id)
                        this.form.delete(url)
                            .then(response => {
                              if(response.data.status=='failed'){
                                Swal.fire(
                                  response.data.status,
                                    response.data.message,
                                    'error'
                                )
                              }else{
                                Swal.fire(
                                    response.data.status,
                                    response.data.message,
                                    'success'
                                ).then((result) => {
                                  this.getData()
                                })
                              }
                            })
                            .catch(e => {
                              Swal.close();
                                e.status != 422 ? console.log(e) : '';
                            })
                    }
                })
            },
            resetModal(id) {
                Swal.fire({
                    title: 'Reset Akun Mahasiswa Ini?',
                    text: "Password Dan Status Pemilihan Akun Di Setel Ulang.",
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
                        url = "{{ route('admin.mahasiswa.reset', ':id') }}".replace(':id', id)
                        console.log(url)
                        this.form.put(url)
                            .then(response => {
                              if(response.data.status=='failed'){
                                Swal.fire(
                                  response.data.status,
                                    response.data.message,
                                    'error'
                                )
                              }else{
                                Swal.fire(
                                    response.data.status,
                                    response.data.message,
                                    'success'
                                ).then((result) => {
                                  this.getData()
                                })
                              }
                            })
                            .catch(e => {
                              Swal.close();
                                e.status != 422 ? console.log(e) : '';
                            })
                    }
                })
            },
            getData(){
              Swal.fire({
                  title: 'Please Wait...',
                  allowEscapeKey: false,
                  allowOutsideClick: false,
                  didOpen: () => {
                      Swal.showLoading();
                  }
              });
                axios.get("{{ route('admin.mahasiswa.get_data') }}")
                    .then(response => {
                        $("table").DataTable().destroy();
                        this.mainData = response.data.data;
                        this.$nextTick(function(){
                          $("table").DataTable();
                        });
                        Swal.close();
                    })
                    .catch(e => {
                        Swal.fire(
                            'Terjadi Kesalahan',
                            e.response.message,
                            'error'
                        )
                    })
            },
            sendAccount(id){
              Swal.fire({
                    title: 'Kirim Akun Mahasiswa Ini?',
                    text: "Akun akan dikirimkan ke email mahasiswa.",
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
                      var formData = new FormData();
                      formData.append('id', id);
                      axios.post("{{ route('admin.mahasiswa.send_account') }}",formData)
                          .then(response => {
                            if(response.data.status=='failed'){
                                Swal.fire(
                                  response.data.status,
                                    response.data.message,
                                    'error'
                                )
                              }else{
                                Swal.fire(
                                    response.data.status,
                                    response.data.message,
                                    'success'
                                ).then((result) => {
                                    Swal.close();
                                })
                              }
                          })
                          .catch(e => {
                              Swal.fire(
                                  'Terjadi Kesalahan',
                                  e.response.message,
                                  'error'
                              )
                          })
                    }
                });
            },
        }
    });
    </script>
@endpush