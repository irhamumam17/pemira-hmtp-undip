@extends('layouts.admin_layout')
@section('title')
    Laporan Pemilihan Suara
@endsection

@push('css')
    <!-- DataTables -->
  <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{asset('assets/admin/plugins/datatables-buttons/css/buttons.bootstrap4.css')}}">
@endpush

@section('content')
<section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Laporan Pemilihan Suara</h3>
              <button type="button" class="btn btn-secondary btn-rounded float-right mr-1" @click="getData()">
                <i class="fas fa-sync-alt"> Refresh</i>
              </button>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="tb_pemilihan" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Total DPT</th>
                  <th>Total Suara Masuk</th>
                  <th>Total Suara Sah</th>
                  <th>Total Suara Tidak Sah</th>
                  <th>Golongan Putih</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                  <td>@{{ mainData.dpt }}</td>
                  <td>@{{ mainData.suara_masuk }}</td>
                  <td>@{{ mainData.suara_sah }}</td>
                  <td>@{{ mainData.suara_tidak_sah }}</td>
                  <td>@{{ mainData.golput }}</td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <th>Total DPT</th>
                    <th>Total Suara Masuk</th>
                    <th>Total Suara Sah</th>
                    <th>Total Suara Tidak Sah</th>
                    <th>Golongan Putih</th>
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
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Laporan Perolehan Suara</h3>
              <button type="button" class="btn btn-secondary btn-rounded float-right mr-1" @click="getPerolehanSuara()">
                <i class="fas fa-sync-alt"> Refresh</i>
              </button>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="tb_perolehan" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>No. Urut</th>
                  <th>Ketua Pasangan Calon</th>
                  <th>Wakil Pasangan Calon</th>
                  <th>Total Perolehan Suara</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(item,index) in mainData2">
                  <td>@{{ item.nomor_urut }}</td>
                  <td>@{{ item.ketua.name }}</td>
                  <td>@{{ item.wakil.name }}</td>
                  <td>@{{ item.jumlah_suara }}</td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <th>No. Urut</th>
                    <th>Ketua Pasangan Calon</th>
                    <th>Wakil Pasangan Calon</th>
                    <th>Total Perolehan Suara</th>
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

    {{-- <script src="{{asset('assets/admin/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{asset('assets/admin/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{asset('assets/admin/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{asset('assets/admin/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script> --}}
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script>
        let app = new Vue({
        el: '#app',
        vuetify: new Vuetify(),
        data: {
            mainData: '',
            mainData2: '',
        },
        mounted() {
            this.getData();
            this.getPerolehanSuara();
        },
        methods: {
            getData(){
              Swal.fire({
                  title: 'Please Wait...',
                  allowEscapeKey: false,
                  allowOutsideClick: false,
                  didOpen: () => {
                      Swal.showLoading();
                  }
              });
                axios.get("{{ route('admin.report.get_data') }}")
                    .then(response => {
                        $("#tb_pemilihan").DataTable().destroy();
                        this.mainData = response.data.data;
                        this.$nextTick(function(){
                          $("#tb_pemilihan").DataTable({
                              dom: 'Bfrtip',
                              buttons: [
                                  'excelHtml5'
                              ]
                          });
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
            getPerolehanSuara(){
              Swal.fire({
                  title: 'Please Wait...',
                  allowEscapeKey: false,
                  allowOutsideClick: false,
                  didOpen: () => {
                      Swal.showLoading();
                  }
              });
                axios.get("{{ route('admin.report.get_perolehan_suara') }}")
                    .then(response => {
                        $("#tb_perolehan").DataTable().destroy();
                        this.mainData2 = response.data.data;
                        this.$nextTick(function(){
                          $("#tb_perolehan").DataTable({
                              dom: 'Bfrtip',
                              buttons: [
                                  'excelHtml5'
                              ]
                          });
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