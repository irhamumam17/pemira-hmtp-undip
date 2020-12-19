@extends('layouts.voting_layout')
@section('title')
    Quickcount
@endsection
@push('css')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
@endpush
@section('content')
    <div class="body-isi">
        <div class="container-card-chart">
            <div class="container-card-chart-title">
                <p class="txt-title-chart">Perolehan Suara</p>    
            </div>
            <div class="container-chart">
                <div id="piechart" class="pie-chart-size"></div>
            </div>
            <p>Suara masuk <label id="nmasuk">@{{ suara_masuk }}</label> dari <label id="totalmhs">@{{ total_mhs }}</label> (<label id="persen">@{{ percent }}</label> %)</p>
        </div>
    </div>
@endsection
@push('script')
<script type="text/javascript">
    //get controller variable
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(function(){
        callData();
        setInterval(callData,15000)
    });
    function callData(){
        $.ajax({
            url: "{{ route('quickcount.get_data') }}",
            type: 'POST',
            data: {_token: CSRF_TOKEN},
            dataType: 'JSON',
            success: function (data) {
                app.total_mhs = data.data.total_mhs;
                app.suara_masuk = data.data.suara_masuk;
                app.percent = Number(((app.suara_masuk * 100)/app.total_mhs).toFixed(2));
                let chart_title = ['Pasangan', 'Jumlah Suara'];
                let chart_body = [];
                data.data.suara.forEach(function(d){
                    chart_body.push([d.nomor_urut+'. '+d.ketua.name+' Dan '+d.wakil.name,d.pemilihan_count]);
                });
                var options = {
                    chartArea: {
                    height: 300,
                    width: 500
                    },
                    legend: {
                    textStyle: {
                        fontSize: 13
                    }
                    }
                };
                data=google.visualization.arrayToDataTable([chart_title,...chart_body]);
                chart = new google.visualization.PieChart(document.getElementById('piechart'));
                chart.draw(data, options);
            }
        });
    }
    let app = new Vue({
        el: '#app',
        vuetify: new Vuetify(),
        data: {
            mainData: '',
            total_mhs :'',
            suara_masuk : '',
            percent : ''
        },
    });
</script>
@endpush