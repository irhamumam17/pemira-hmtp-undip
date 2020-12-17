@extends('layouts.admin_layout')
@section('title')
    Kelola Admin
@endsection

@push('css')
    <!-- DataTables -->
  <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endpush

@section('content')
<section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Data Admin</h3>
              <button type="button" class="btn btn-success btn-rounded float-right" @click="createModal()">
                <i class="fas fa-plus-circle"> Tambah Data</i>
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
                  <th>Username</th>
                  <th>Status</th>
                  <th>Terakhir Login</th>
                  <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(item,index) in mainData">
                  <td>@{{ index+1 }}</td>
                  <td>@{{ item.username }}</td>
                  <td>
                    <span v-if="item.status==0" class="badge badge-danger">
                      Tidak Aktif
                    </span>
                    <span v-if="item.status==1" class="badge badge-success">
                      Sedang Login
                    </span>
                  </td>
                  <td>@{{ item.last_login }}</td>
                  <td>
                    <a href="javascript:void(0)" @click="editModal(item)" class="text-warning"
                      data-toggle="tooltip" data-placement="top" data-original-title="Edit">
                      <i class="fas fa-edit"></i>
                    </a>
                    <a v-if="item.status==0" href="javascript:void(0)" @click="deleteModal(item.id)" class="text-danger"
                      data-toggle="tooltip" data-placement="top" data-original-title="Edit">
                      <i class="fas fa-trash"></i>
                    </a>
                  </td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <th>No.</th>
                    <th>Username</th>
                    <th>Status</th>
                    <th>Terakhir Login</th>
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
              <h4 class="modal-title" v-show="!editMode" id="myLargeModalLabel">Tambah Data</h4>
              <h4 class="modal-title" v-show="editMode" id="myLargeModalLabel">Edit Data</h4>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          </div>

          <form @submit.prevent="editMode ? updateData() : storeData()" @keydown="form.onKeydown($event)" id="form">
              <div class="modal-body mx-4">
                  <div class="form-row">
                      <label class="col-lg-2" for="Username">Username</label>
                      <div class="form-group col-md-8">
                          <input v-model="form.username" id="username" type="text" min=0 placeholder="Masukkan Username"
                              class="form-control" :class="{ 'is-invalid': form.errors.has('username') }">
                          <has-error :form="form" field="username"></has-error>
                      </div>
                  </div>
                  <div class="form-row">
                      <label class="col-lg-2" for="Password">Password</label>
                      <div class="form-group col-md-8">
                          <input v-model="form.password" id="password" type="password"
                              placeholder="Masukkan Password" class="form-control"
                              :class="{ 'is-invalid': form.errors.has('password') }">
                          <has-error :form="form" field="password"></has-error>
                      </div>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                  <button v-show="!editMode" type="submit" class="btn btn-primary">Simpan</button>
                  <button v-show="editMode" type="submit" class="btn btn-success">Ubah</button>
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
    <script>
        let app = new Vue({
        el: '#app',
        vuetify: new Vuetify(),
        data: {
            mainData: '',
            form : new Form({
                id: '',
                username: '',
                password: '',
            }),
            loading : false,
            btnLoading: false,
            editMode: false,
        },
        mounted() {
            this.getData();
        },
        methods: {
          createModal() {
                this.editMode = false;
                this.form.reset();
                this.form.clear();
                $('#modal').modal('show');
            },
            editModal(data) {
                this.editMode = true;
                this.form.reset();
                this.form.clear();
                this.form.username=data.username
                this.form.id = data.id
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
                this.form.post("{{ route('admin.admin.store') }}")
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
                        });
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
                url = "{{ route('admin.admin.update', ':id') }}".replace(':id', this.form.id)
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
                        });
                      }
                    })
                    .catch(e => {
                      Swal.close();
                        e.response.status != 422 ? console.log(e) : '';
                    })
            },

            deleteModal(id) {
                Swal.fire({
                    title: 'Hapus Admin Ini?',
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
                        url = "{{ route('admin.admin.destroy', ':id') }}".replace(':id', id)
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
            getData(){
              Swal.fire({
                  title: 'Please Wait...',
                  allowEscapeKey: false,
                  allowOutsideClick: false,
                  didOpen: () => {
                      Swal.showLoading();
                  }
              });
                axios.get("{{ route('admin.admin.get_data') }}")
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
        }
    });
    </script>
@endpush