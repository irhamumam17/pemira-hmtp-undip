@extends('layouts.admin_layout')
@section('title')
    Data Pemilihan Suara
@endsection

@push('css')
    <!-- DataTables -->
  <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <!-- Ekko Lightbox -->
    <link rel="stylesheet" href="{{asset('assets/admin/plugins/ekko-lightbox/ekko-lightbox.css')}}">
@endpush

@section('content')
<section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Data Pemilihan Suara</h3>
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
                  <th>Foto</th>
                  <th>Mahasiswa</th>
                  <th>Angkatan</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(item,index) in mainData">
                  <td>@{{ index+1 }}</td>
                  <td>
                    <a :href="item.foto" data-toggle="lightbox" :data-title="'Mahasisa'">
                        <img :src="item.foto" class="img-fluid mb-2 img-size-50 mr-3" alt="white sample"/>
                      </a>
                  </td>
                  <td>@{{ item.mahasiswa.name }}</td>
                  <td>@{{ item.mahasiswa.angkatan }}</td>
                  <td>
                    <span v-if="item.status==0" class="badge badge-success">
                      Valid
                    </span>
                    <span v-if="item.status==1" class="badge badge-danger">
                      Tidak Valid
                    </span>
                  </td>
                  <td>
                    <a v-if="item.status==1" href="javascript:void(0)" @click="validVote(item.id)" class="text-success"
                      data-toggle="tooltip" data-placement="top" data-original-title="Valid">
                      <i class="fa fa-edit"></i>
                    </a>
                    <a v-if="item.status==0" href="javascript:void(0)" @click="invalidVote(item.id)" class="text-danger"
                      data-toggle="tooltip" data-placement="top" data-original-title="Invalid">
                      <i class="fa fa-edit"></i>
                    </a>
                  </td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <th>No.</th>
                    <th>Foto</th>
                    <th>Mahasiswa</th>
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
@endsection
@push('script')
    <!-- DataTables -->
    <script src="{{asset('assets/admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{asset('assets/admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{asset('assets/admin/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{asset('assets/admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <!-- Ekko Lightbox -->
    <script src="{{ asset('assets/admin/plugins/ekko-lightbox/ekko-lightbox.min.js')  }}"></script>
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
            validVote(id) {
                Swal.fire({
                    title: 'Valid Pemilihan?',
                    text: "Suara mahasiswa tersebut akan dihitung sah",
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
                        url = "{{ route('admin.data_pemilihan.valid', ':id') }}".replace(':id', id)
                        this.form.put(url)
                            .then(response => {
                              if(response.data.status=='success'){
                                Swal.fire(
                                    'Berhasil',
                                    response.data.message,
                                    'success'
                                ).then((result) => {
                                  this.getData()
                                })
                              }else{
                                Swal.fire(
                                    'Gagal',
                                    response.data.message,
                                    'error'
                                )
                              }
                            })
                            .catch(e => {
                              Swal.close();
                                e.status != 422 ? console.log(e) : '';
                            })
                    }
                })
            },
            invalidVote(id) {
                Swal.fire({
                    title: 'Invalid Pemilihan?',
                    text: "Suara mahasiswa tersebut akan dihitung tidak sah",
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
                        url = "{{ route('admin.data_pemilihan.invalid', ':id') }}".replace(':id', id)
                        this.form.put(url)
                            .then(response => {
                              if(response.data.status=='success'){
                                Swal.fire(
                                    'Berhasil',
                                    response.data.message,
                                    'success'
                                ).then((result) => {
                                  this.getData()
                                })
                              }else{
                                Swal.fire(
                                    'Gagal',
                                    response.data.message,
                                    'error'
                                )
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
                axios.get("{{ route('admin.data_pemilihan.get_data') }}")
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
    <script>
        $(function(){
            $(document).on('click', '[data-toggle="lightbox"]', function(event) {
                event.preventDefault();
                $(this).ekkoLightbox({
                alwaysShowClose: true
                });
            });
            $('.btn[data-filter]').on('click', function() {
                $('.btn[data-filter]').removeClass('active');
                $(this).addClass('active');
            });
        });
    </script>
@endpush