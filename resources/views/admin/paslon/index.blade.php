@extends('layouts.admin_layout')
@section('title')
    Kelola Paslon
@endsection

@push('css')
    <!-- DataTables -->
  <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <!-- Ekko Lightbox -->
  <link rel="stylesheet" href="{{asset('assets/admin/plugins/ekko-lightbox/ekko-lightbox.css')}}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('assets/admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{asset('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{asset('assets/admin/plugins/summernote/summernote-bs4.min.css') }}">
    <style>
      .has-error .select2-selection {
          border: 1px solid #a94442;
          border-radius: 4px;
      }
    </style>
@endpush

@section('content')
<section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Data Pasangan Calon</h3>
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
                  <th>No. Urut</th>
                  <th>Foto</th>
                  <th>Ketua Pasangan</th>
                  <th>Wakil Pasangan</th>
                  <th>Perolehan Suara</th>
                  <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(item,index) in mainData">
                  <td>@{{ index+1 }}</td>
                  <td>@{{ item.nomor_urut }}</td>
                  <td>
                      <a :href="item.foto" data-toggle="lightbox" :data-title="'Pasangan Nomor Urut '+item.nomor_urut">
                        <img :src="item.foto" class="img-fluid mb-2 img-size-50 mr-3" alt="white sample"/>
                      </a>
                    </td>
                  <td>@{{ item.ketua.name }}</td>
                  <td>@{{ item.wakil.name }}</td>
                  <td>@{{ item.jumlah_suara }}</td>
                  <td>
                    <a href="javascript:void(0)" @click="editModal(item)" class="text-warning"
                      data-toggle="tooltip" data-placement="top" data-original-title="Edit">
                      <i class="fas fa-edit"></i>
                    </a>
                    <a href="javascript:void(0)" @click="deleteModal(item.id)" class="text-danger"
                      data-toggle="tooltip" data-placement="top" data-original-title="Edit">
                      <i class="fas fa-trash"></i>
                    </a>
                    <a href="javascript:void(0)" @click="detailModal(item)" class="text-secondary"
                      data-toggle="tooltip" data-placement="top" title="Detail">
                      <i class="fas fa-info-circle"></i>
                    </a>
                  </td>
                </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th>No.</th>
                        <th>No. Urut</th>
                        <th>Foto</th>
                        <th>Ketua Pasangan</th>
                        <th>Wakil Pasangan</th>
                        <th>Perolehan Suara</th>
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
              <h4 class="modal-title" v-show="mode=='info'" id="myLargeModalLabel">Detail Pasangan Calon</h4>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          </div>

          <form @submit.prevent="mode=='edit' ? updateData() : (mode=='create' ? storeData() : '')" @keydown="form.onKeydown($event)" id="form">
              <div class="modal-body mx-4">
                  <div class="form-row">
                      <label class="col-lg-2" for="nomor_urut">Nomor Urut</label>
                      <div class="form-group col-md-8">
                          <input v-model="form.nomor_urut" id="nomor_urut" type="number" min=0 placeholder="Masukkan Nomor Urut"
                              class="form-control" :class="{ 'is-invalid': form.errors.has('nomor_urut') }" :readonly="mode=='info'">
                          <has-error :form="form" field="nomor_urut"></has-error>
                      </div>
                  </div>
                  <div class="form-row">
                      <label class="col-lg-2" for="ketua_mahasiswa_id">Ketua</label>
                      <div class="form-group col-md-8">
                        <select v-show="mode!='info'" v-model="form.ketua_mahasiswa_id" class="select2 select2-hidden-accessible" id="select2-ketua" data-placeholder="Pilih Ketua" onchange="pilihKetua()" style="width: 100%;" :class="{ 'select2-hidden-accessible is-invalid': form.errors.has('ketua_mahasiswa_id') }">
                            <option v-for="(item,index) in calonKetua" :value="item.id">@{{ item.name }}</option>
                          </select>
                          <input v-show="mode=='info'" v-model="ketua.name" id="ketua" type="text" min=0 placeholder="Masukkan Ketua"
                              class="form-control" readonly>
                          <has-error :form="form" field="ketua_mahasiswa_id"></has-error>
                      </div>
                  </div>
                  <div class="form-row">
                      <label class="col-lg-2" for="wakil_mahasiswa_id">Wakil</label>
                      <div class="form-group col-md-8">
                        <select v-show="mode!='info'" v-model="form.wakil_mahasiswa_id" class="select2 select2-hidden-accessible" id="select2-wakil" data-placeholder="Pilih Wakil" onchange="pilihWakil()" style="width: 100%;" :class="{ 'select2-hidden-accessible is-invalid': form.errors.has('wakil_mahasiswa_id') }">
                            <option v-for="(item,index) in calonWakil" :value="item.id">@{{ item.name }}</option>
                          </select>
                          <input v-show="mode=='info'" v-model="wakil.name" id="wakil" type="text" min=0 placeholder="Masukkan Wakil"
                              class="form-control" readonly>
                          <has-error :form="form" field="wakil_mahasiswa_id"></has-error>
                      </div>
                  </div>
                  <div class="form-row">
                      <label class="col-lg-2" for="visi">Visi</label>
                      <div class="form-group col-md-8">
                        <textarea v-model="form.visi" class="form-control" name="visi" id="visi" rows="4" :class="{ 'is-invalid': form.errors.has('visi') }">
                        </textarea>
                        <has-error :form="form" field="visi"></has-error>
                      </div>
                  </div>
                  <div class="form-row">
                      <label class="col-lg-2" for="misi">Misi</label>
                      <div class="form-group col-md-8">
                        <textarea v-model="form.misi" class="form-control" name="misi" id="misi" rows="4" :class="{ 'is-invalid': form.errors.has('misi') }">
                        </textarea>
                        <has-error :form="form" field="misi"></has-error>
                      </div>
                  </div>
                  <div class="form-row" v-if="mode!='info'">
                      <label class="col-lg-2" for="foto">Foto</label>
                      <div class="form-group col-md-8">
                        <div class="input-group">
                          <div class="custom-file">
                            <input type="file" class="custom-file-input" id="exampleInputFile" v-on:change="fileUpload" :class="{ 'is-invalid': form.errors.has('foto') }">
                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                          </div>
                        </div>
                        <has-error :form="form" field="foto"></has-error>
                      </div>
                  </div>
                  <div class="form-row" v-if="urlImage!=''">
                      <label class="col-lg-2" v-if="mode=='info'">Foto</label>
                      <label class="col-lg-2" v-else></label>
                      <div class="form-group col-md-8">
                        <img id="preview" :src="urlImage" alt="foto-paslon" class="img-fluid" width="200px">
                      </div>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                  <button v-show="mode=='create'" type="submit" class="btn btn-primary">Simpan</button>
                  <button v-show="mode=='edit'" type="submit" class="btn btn-success">Ubah</button>
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
    <!-- Ekko Lightbox -->
    <script src="{{ asset('assets/admin/plugins/ekko-lightbox/ekko-lightbox.min.js')  }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('assets/admin/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/admin/plugins/filterizr/jquery.filterizr.min.js') }}"></script>
    <!-- Summernote -->
    <script src="{{ asset('assets/admin/plugins/summernote/summernote-bs4.min.js') }}"></script>
    <script>
        let app = new Vue({
        el: '#app',
        vuetify: new Vuetify(),
        data: {
            mainData: '',
            form : new Form({
                id: '',
                nomor_urut: '',
                ketua_mahasiswa_id: '',
                wakil_mahasiswa_id: '',
                foto: '',
                visi: '',
                misi: ''
            }),
            loading : false,
            btnLoading: false,
            mode: '',
            calonKetua: '',
            calonWakil: '',
            urlImage: '',
            ketua : {
              id : '',
              name : ''
            },
            wakil : {
              id : '',
              name : ''
            },
        },
        mounted() {
            this.getData();
        },
        methods: {
          createModal() {
                this.mode = 'create';
                clear();
                $(".select2-container").show();
                $('#visi').summernote('enable');
                $('#misi').summernote('enable');
                $("#select2-ketua").prop("disabled", false);
                $("#select2-wakil").prop("disabled", true);
                this.form.reset();
                this.form.clear();
                $('#modal').modal('show');
                this.getKetua();
            },
            detailModal(data) {
                this.mode = 'info';
                clear();
                $(".select2-container").hide();
                this.ketua.id = data.ketua.id;
                this.ketua.name = data.ketua.name;
                this.wakil.id = data.wakil.id;
                this.wakil.name = data.wakil.name;
                this.form.reset();
                this.form.clear();
                this.form.fill(data);
                $("#visi").summernote("code", data.visi);
                $("#misi").summernote("code", data.misi);
                $('#visi').summernote('disable');
                $('#misi').summernote('disable');
                this.urlImage = data.foto;
                $('#modal').modal('show');
            },
            editModal(data) {
                this.mode = 'edit';
                clear();
                this.form.reset();
                this.form.clear();
                $(".select2-container").show();
                $("#select2-ketua").prop("disabled", false);
                $('#visi').summernote('enable');
                $('#misi').summernote('enable');
                this.getKetua();
                this.getWakil(data.ketua_mahasiswa_id);
                this.urlImage = data.foto;
                this.form.fill(data);
                this.form.foto = "";
                $("#visi").summernote("code", data.visi);
                $("#misi").summernote("code", data.misi);
                $('#modal').modal('show');
            },
            fileUpload(e){
              const file = e.target.files[0];
              this.urlImage = URL.createObjectURL(file);
              let gambar = e.target.files;
              if(gambar.length){
                this.form.foto = gambar[0];
              }
            },
            getKetua(){
                // this.form.wakil_mahasiswa_id = '';
                Swal.fire({
                  title: 'Memuat Data Calon Ketua...',
                  allowEscapeKey: false,
                  allowOutsideClick: false,
                  didOpen: () => {
                      Swal.showLoading();
                  }
              });
                axios.get("{{ route('admin.paslon.get_calon_ketua') }}")
                    .then(response => {
                        this.calonKetua = response.data.data;
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
            getWakil(id){
                Swal.fire({
                  title: 'Memuat Data Calon Wakil...',
                  allowEscapeKey: false,
                  allowOutsideClick: false,
                  didOpen: () => {
                      Swal.showLoading();
                  }
              });
              let fd= new FormData()      
              fd.append('ketua_mahasiswa_id', id)
                axios.post("{{ route('admin.paslon.get_calon_wakil' ) }}",fd)
                    .then(response => {
                        this.calonWakil = response.data.data;
                        $("#select2-wakil").prop("disabled", false);
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
            storeData() {
              Swal.fire({
                  title: 'Please Wait...',
                  allowEscapeKey: false,
                  allowOutsideClick: false,
                  didOpen: () => {
                      Swal.showLoading();
                  }
              });
              this.form.visi = $('#visi').summernote('code');
              this.form.misi = $('#misi').summernote('code');
              this.form.submit('post',"{{ route('admin.paslon.store') }}",{
                transformRequest: [function(data, headers){
                  return objectToFormData(data)
                }]
              }).then(response => {
                  if(response.data.status=='success'){
                    Swal.fire(
                        'Berhasil',
                        response.data.message,
                        'success',
                    ).then((result) => {
                      $('#modal').modal('hide');
                      this.getData()
                    });
                  }else{
                    Swal.fire(
                        'Gagal',
                        response.data.message,
                        'error'
                    )
                  }
              }).catch(e => {
                  Swal.close();
                  e.status != 422 ? console.log(e) : '';
              })
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
              this.form.visi = $('#visi').summernote('code');
              this.form.misi = $('#misi').summernote('code');
                url = "{{ route('admin.paslon.update2', ':id') }}".replace(':id', this.form.id)
                this.form.submit('post',url,{
                  transformRequest: [function(data, headers){
                    return objectToFormData(data)
                  }]
                }).then(response => {
                      if(response.data.status=='success'){
                        Swal.fire(
                            'Berhasil',
                            response.data.message,
                            'success',
                        ).then((result) => {
                          $('#modal').modal('hide');
                          this.getData()
                        });
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
                        e.response.status != 422 ? console.log(e) : '';
                    })
            },

            deleteModal(id) {
                Swal.fire({
                    title: 'Hapus Pasangan Calon Ini?',
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
                      url = "{{ route('admin.paslon.destroy', ':id') }}".replace(':id', id)
                      this.form.delete(url)
                        .then(response => {
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
                axios.get("{{ route('admin.paslon.get_data') }}")
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
        $(function () {
          $('#modal').on('hidden',function(){
            app.form.nomor_urut = "";
            app.form.ketua_mahasiswa_id = "";
            app.form.wakil_mahasiswa_id = "";
            app.form.foto = "";
            app.form.visi = "";
            app.form.misi = "";
            app.calonKetua = "";
            app.calonWakil = "";
          });
          $('#select2-ketua').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Ketua'
          });
          // $('#select2-ketua').on('select2:select',function(e){
            
          // });
          $('#select2-wakil').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Wakil'
          });
          // $('#select2-wakil').on('select2:select',function(e){
            
          // });
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
          $('#visi').summernote()
          $('#misi').summernote()
        })
        function pilihKetua(){
          let id = $('#select2-ketua').val();
          app.form.ketua_mahasiswa_id = id;
          app.calonWakil= '';
          app.form.wakil_mahasiswa_id = ''
          app.getWakil(id);
        }
        function pilihWakil(){
          app.form.wakil_mahasiswa_id = $('#select2-wakil').val();
        }
        function clear(){
          app.form.id = "";
          app.form.ketua_mahasiswa_id = "";
          app.form.wakil_mahasiswa_id = "";
          app.form.visi = "";
          app.form.misi = "";
          app.form.nomor_urut = "";
          app.form.foto = "";
          app.calonKetua = "";
          app.calonWakil = "";
          app.ketua.id = "";
          app.ketua.name = "";
          app.wakil.id = "";
          app.wakil.name = "";
          app.urlImage = "";
          $("#visi").summernote("code", '');
          $("#misi").summernote("code", '');
        }
      </script>
@endpush