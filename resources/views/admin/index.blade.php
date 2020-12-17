@extends('layouts.admin_layout')
@section('title')
    Dashboard
@endsection
@section('content')
    <!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
          <div class="inner">
            <h3>@{{ mainData.mhs_total }}</h3>

            <p>Total Mahasiswa</p>
          </div>
          <div class="icon">
            <i class="ion ion-bag"></i>
          </div>
          <a href="#" class="small-box-footer"></a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-success">
          <div class="inner">
            <h3>@{{ mainData.mhs_belum }}<sup style="font-size: 20px"> (@{{ belumPercent }}%)</sup></h3>

            <p>Belum Memilih</p>
          </div>
          <div class="icon">
            <i class="ion ion-stats-bars"></i>
          </div>
          <a href="#" class="small-box-footer"></a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-warning">
          <div class="inner">
            <h3>@{{ mainData.mhs_sedang }}<sup style="font-size: 20px"> (@{{ sedangPercent }}%)</sup></h3>
            <p>Sedang Memilih</p>
          </div>
          <div class="icon">
            <i class="ion ion-person-add"></i>
          </div>
          <a href="#" class="small-box-footer"></a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-danger">
          <div class="inner">
            <h3>@{{ mainData.mhs_sudah }}<sup style="font-size: 20px"> (@{{ sudahPercent }}%)</sup></h3>

            <p>Sudah Memilih</p>
          </div>
          <div class="icon">
            <i class="ion ion-pie-graph"></i>
          </div>
          <a href="#" class="small-box-footer"></a>
        </div>
      </div>
      <!-- ./col -->
    </div>
    <!-- /.row -->
    <!-- Main row -->
    {{-- <div class="row">
      <!-- Left col -->
      <section class="col-lg-12 connectedSortable">
        <!-- Custom tabs (Charts with tabs)-->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-chart-pie mr-1"></i>
              Grafik Pemilihan Mahasiswa
            </h3>
          </div><!-- /.card-header -->
          <div class="card-body">
            <!--Div that will hold the pie chart-->
            <div id="chart_div"></div>
          </div><!-- /.card-body -->
        </div>
      </section>
      <!-- right col -->
    </div> --}}
    <!-- /.row (main row) -->
  </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection
@push('script')
    <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">

      // Load the Visualization API and the corechart package.
      google.charts.load('current', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.charts.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Slices');
        data.addRows([
          ['Mushrooms', 3],
          ['Onions', 1],
          ['Olives', 1],
          ['Zucchini', 1],
          ['Pepperoni', 2]
        ]);

        // Set chart options
        var options = {'title':'How Much Pizza I Ate Last Night',
                       'width':400,
                       'height':300};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
    <script>
      let app = new Vue({
      el: '#app',
      vuetify: new Vuetify(),
      data: {
          mainData: '',
          belumPercent: '',
          sedangPercent: '',
          sudahPercent: '',
          loading : false,
          btnLoading: false,
          editMode: false,
      },
      mounted() {
          this.getData();
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
              axios.get("{{ route('admin.dashboard.get_data') }}")
                  .then(response => {
                      this.mainData = response.data.data;
                      this.belumPercent = Number(((response.data.data.mhs_belum * 100)/response.data.data.mhs_total).toFixed(2));
                      this.sedangPercent = Number(((response.data.data.mhs_sedang * 100)/response.data.data.mhs_total).toFixed(2));
                      this.sudahPercent = Number(((response.data.data.mhs_sudah * 100)/response.data.data.mhs_total).toFixed(2));
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