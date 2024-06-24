@extends('layouts.main')
@section('content')
    <style>
        .bg-alarm {
            background-image: url('{{url("sites/img/alarm.gif")}}')
        }
        .bg-ack{
            background-image: url('{{url("sites/img/ack.png")}}')
        }
        .bg-normal{
            background-image: url('{{url("sites/img/normal.png")}}')
        }
    </style>
    
    <div id="content" class="app-content">
        <div class="bg-dark bg-opacity-70 text-white mx-0">
            <div class="d-print-none">
                <div class="row">
                    <div class="col-6">
                        <ul class="nav justify-content-start">
                        <li class="nav-item">
                            <a class="navbar-brand" href="#">
                                <img src="{{asset('public/sites/img/logo-1.png')}}" width="30" height="30" alt="" class="d-inline-block align-middle">
                                
                            </a>                  
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">GANDUMAS KENCANA - DAS CONTROL VALVE STORAGE SYSTEM</a>
                        </li>
                        </ul>
                    </div>
                    <div class="col-6">
                        <ul class="nav justify-content-end">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="{{url('dashboard')}}">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{url('graphic.realtime')}}">Line Monitor</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{url('alarm.view')}}">Alarm Log</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{url('graphic.report')}}">Reporting</a>
                        </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row pt-2">
            <div class="col-12 mb-2">
                <div class="card">
                    <div class="card-body">
                        <div class="row pt-0">
                        <?php

                        for ($i = 1; $i <= 36; $i++) {
                            // Your code to be executed each iteration goes here
                            echo '<div class="col-6 col-lg-1 col-md-4 col-sm-6 box p-1">
                                    <div class="card" >
                                    <div class="card-body text-center align-middle bg-opacity-55" id="lamp_'.$i.'">
                                        <table style="height: 100px;">
                                            <tbody>
                                                <tr> 
                                                    <td class="align-middle text-center" style="text-align: center!important" align="center" id="text_'.$i.'">                                            
                                                   </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="card-arrow">
                                        <div class="card-arrow-top-left"></div>
                                        <div class="card-arrow-top-right"></div>
                                        <div class="card-arrow-bottom-left"></div>
                                        <div class="card-arrow-bottom-right"></div>
                                    </div>

                                    </div>
                                </div>';
                        }

                        ?>                     
                            
                        </div>
                        
                    </div>
                    <div class="card-arrow">
                        <div class="card-arrow-top-left"></div>
                        <div class="card-arrow-top-right"></div>
                        <div class="card-arrow-bottom-left"></div>
                        <div class="card-arrow-bottom-right"></div>
                    </div>
                </div>
            </div>
            <div class="col-12 pe-0  col-lg-6 col-md-12 col-sm-12 mb-2">
                <div class="card">
                    <div class="card-body p-0">
                        <div id="chart"></div>
                    </div>
                    <div class="card-arrow">
                        <div class="card-arrow-top-left"></div>
                        <div class="card-arrow-top-right"></div>
                        <div class="card-arrow-bottom-left"></div>
                        <div class="card-arrow-bottom-right"></div>
                    </div>
                </div>
            </div>
            <div class="col-12 ps-0 col-lg-6 col-md-12 col-sm-12 mb-2">
                <div class="card p-0">
                    <div class="card-body p-0">
                        <div id="chart1"></div>
                    </div>
                    <div class="card-arrow">
                        <div class="card-arrow-top-left"></div>
                        <div class="card-arrow-top-right"></div>
                        <div class="card-arrow-bottom-left"></div>
                        <div class="card-arrow-bottom-right"></div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
@endsection
@section('script')
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="{{url('sites/js/mqtt.js')}}"></script>
<script>
    var layer = @json($layer);
    console.log(layer)
    for (let l = 0; l < layer.length; l++) {
        var i1 = l+1
        document.getElementById("text_"+i1+"").textContent=""+layer[l].event_desc+"";
    }
</script>
<script>
    var options = {
          series: [],
          chart: {
            toolbar: {
                show: false,
            },
          height: 400,
          type: 'rangeBar'
        },
        plotOptions: {
          bar: {
            horizontal: true,
            barHeight: '100%',
            rangeBarGroupRows: true
          }
        },       
        colors: ['#D53939','#2DC24D','#EFB61F'],
        fill: {
          type: 'solid'
        },
        xaxis: {
          type: 'datetime',
          labels: {
                style:{
                    colors: '#ffffff'
                }
            }
          
        },
        yaxis: {
          labels: {
                style:{
                    colors: '#ffffff'
                }
            }
        },
        legend: {
            show: false,
            position: 'right'
        },
    };

    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();

    var chart1 = new ApexCharts(document.querySelector("#chart1"), options);
    chart1.render();
   
        var web_id = "webmuffler"+Math.random() * 100000+"";
        clientu = new Paho.MQTT.Client('portal-iot.com', Number('9001'), ""+web_id+"");
        clientu.onConnectionLost = onConnectionLostu ;
        clientu.onMessageArrived = onMessageArrivedu ;
        clientu.connect({onSuccess :onConnectu,useSSL: false});    
        function onConnectu () {
            clientu.subscribe("ckp/demo/1/heartbeat");                              
        }
                                       
        function onConnectionLostu(responseObject) {
            if (responseObject.errorCode !== 0) {
                        
            }
        }
        function onMessageArrivedu(message) {
            var a = JSON.parse(message.payloadString);              
            for (let i = 1; i <= 31; i++) {
                const elementId = `#lamp_${i}`;
                const lampState = a.d[`E${i}`][0];

                if (lampState === 0) {
                    $(elementId).removeClass('bg-alarm').removeClass('bg-ack').addClass('bg-normal');
                } else if (lampState === 2) {
                    $(elementId).removeClass('bg-normal').removeClass('bg-ack').addClass('bg-alarm');
                } else {
                    $(elementId).removeClass('bg-normal').removeClass('bg-alarm').addClass('bg-ack');
                }
            }
        }

        var web_id = "webmuffler"+Math.random() * 100000+"";
        clientGanti = new Paho.MQTT.Client('portal-iot.com', Number('9001'), ""+web_id+"");
        clientGanti.onConnectionLost = onConnectionLostGanti ;
        clientGanti.onMessageArrived = onMessageArrivedGanti ;
        clientGanti.connect({onSuccess :onConnectGanti,useSSL: false});    
        function onConnectGanti () {
            clientGanti.subscribe("ckp/demo/1/ganttchart");                              
        }
                                       
        function onConnectionLostGanti(responseObject) {
            if (responseObject.errorCode !== 0) {
                 
            }
        }
        function onMessageArrivedGanti(message) {
            var a = JSON.parse(message.payloadString);     
            ga1 = [] ;
            gb1 = [];
            gc1 = [];
            ga2 = [] ;
            gb2 = [];
            gc2 = [];   
            for (let i = 0; i < a.length; i++) {    
              var d = {  
                "x": ''+a[i].event_code+'.'+a[i].event_desc+'',
                "y": [
                    new Date(a[i].log_start).getTime() + (3600*12*1000),
                    new Date(a[i].log_end).getTime() + (3600*12*1000)
                ]}
                if(a[i].event_code<17){
                   if(a[i].log_status == 0){
                        ga1.push(d)
                   }else if(a[i].log_status == 1){
                        gb1.push(d)
                   }else if(a[i].log_status == 2){
                        gc1.push(d)
                   }
                }else{
                    if(a[i].log_status == 0){
                        ga2.push(d)
                   }else if(a[i].log_status == 1){
                        gb2.push(d)
                   }else if(a[i].log_status == 2){
                        gc2.push(d)
                   }
                }
                
            }
            
            chart.updateSeries([
                        {
                            name: 'alarm unknown',
                            data: gc1
                        },
                        {
                            name: 'no alarm',
                            data: ga1
                        },{
                            name: 'alarm acknownledge',
                            data: gb1
                        }
                ]);  
                chart1.updateSeries([
                        {
                            name: 'alarm unknown',
                            data: gc2
                        },
                        {
                            name: 'no alarm',
                            data: ga2
                        },{
                            name: 'alarm acknownledge',
                            data: gb2
                        }
                ]);          
        }
</script>
@endsection