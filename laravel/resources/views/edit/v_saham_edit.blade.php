@extends('layouts.landing')
@section('content')
<style>
    .body {
        font-size:14px!important;
        min-height: 90vh;
        background-color: rgb(241, 241, 241)!important;
    }
</style>
<div class="body">
    <nav class="navbar navbar-expand-lg navbar-light bg-white mx-0 py-1">
        <div class="container px-0">
            <a class="navbar-brand" href="#">
                <img src="https://modi.esdm.go.id/images/logo-modi2.png" width="120" alt="">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active px-4">
                <a class="nav-link btn btn-light py-3" href="#" style="font-size: 14px"> <i class="fa fa-reply" aria-hidden="true"></i> <b> MODI V1</b><span class="sr-only">(current)</span></a>
                </li>
            </ul>
            <span class="navbar-text">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link text-dark btn pb-0" href="{{route('main')}}" style="font-size: 14px"><b>DATA PERUSAHAAN</b> </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark btn pb-0" href="#" style="font-size: 14px"><b>SELF SERVICE</b> </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark btn pb-0" href="{{route('logout-action')}}" style="font-size: 14px"><b>LOGOUT</b> </a>
                    </li>
                </ul>
                
            </span>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="row py-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        Form {{$judul}}
                    </div>
                    <div class="card-body">
                        <form action="{{$route}}" method="POST">
                            @csrf
                            @foreach ($form as $f)
                                @if($type==0)
                                @php
                                
                                    $value = $main->{$f['name']};
                                    if (is_array($value)) {
                                        // Convert the array to a JSON string or handle as needed
                                        $value = json_encode($value);
                                    }
                                @endphp
                                @endif
                                <div class="form-group">
                                    <label @if($f['type']=='hidden') class="d-none" @endif>{{$f['name']}}</label>
                                    <input type="{{$f['type']}}" min="-2000" step="any"  @if($type==0) value="{{$value}}" @endif name="{{$f['name']}}" class="form-control">
                                </div>
                                
                            @endforeach
                            @if($type==1)
                              <input type="hidden" value="{{$data_id}}" name="data_id">
                            @endif
                            <button type="submit" class="btn {{$button}}">{{$button_text}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div style="background-color: black">
    <div class="container py-3 px-0">
        <div class="row">
            <div class="col-6">
                <div class="" style="color:  #f0b616;"> <b>Hak Cipta ©️ 2020 Kementerian Energi dan Sumber Daya Mineral</b></div>
                <div class="text-white">Kementerian Energi dan Sumber Daya Mineral <br>
                    Jl. Medan Merdeka Selatan No. 18 <br>
                    Jakarta Pusat 10110 <br>
                    Telp. 021 3804242 Fax. 021 3507210</div>
            </div>
            <div class="col-4"></div>
            <div class="col-2 text-white">
                Kontak Kami <br>
                contactcenter136@esdm.go.id <br>
                Kontak Kami <br>
                136
            </div>
        </div>
        
    </div>
</div>    
@endsection
@section('script')
    <script>
       $(document).ready(function(){
            var table = $('#table-product').DataTable({
                "processing": true,
                "serverSide": true,
                "pageLength": 20,
                "searching" : false,
                "lengthChange": false,
                "columnDefs": [
                    { "targets": [0, 1, 2, 3, 4], "orderable": false }
                ],
                "ajax": {
                    "url": "{{ route('server-side') }}",
                    "type": "GET",  // Use GET or POST depending on your server-side requirements
                    "data": function (d) {
                        d.searchNama = $('#search-nama').val();  // Send the search value with the request
                        d.searchAkta = $('#search-akta').val();
                    }
                },
                "columns": [
                    { "data": "data_id" },
                    { "data": "action" },
                    { "data": "nomor_akte" },
                    { "data": "tanggal_akte" },
                    { "data": "jenis_perizinan" }
                ]
            });

            // Listen for changes in the search input and reload the table data
            $('#search-nama').on('change', function () {
                table.ajax.reload();
                console.log("hai")
            });
            $('#search-akta').on('change', function () {
                table.ajax.reload();
            });
        });
    </script>
@endsection