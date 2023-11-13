@extends('hr.dashboard.layout.layout')

@section('content')
<div class="contariner">
        <div class="row">
            <div class="card card-plain rounded-0 border-primary">
                <div class="row">
                <div class="col-md-5">
                    <div class="mt-2" >
                        <tr>
                            <td>
                                <a href="{{ route('hr.training.materi.laporan.list_print', ['mat' => $mat]) }} " target="_blank"><i class="fas fa-print"></i></a>                            
                            </td>
                        </tr>
                        <br><br>
                        <tr>
                            <td>
                                Materi 
                            </td>
                            <td>:</td>
                            <td >
                             <strong>
                                 {{ $mat }}    
                            </strong>
                            </td>
                        </tr>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="form-group  mt-4">
                        <div class="row">
                            <div class="col-md-4">
                                <select  id="country-dropdown" class="form-control form-control-sm" name="bagian">
                                    <option value="">-- Select Bagian --</option>
                                    @foreach ($bagian as $item)
                                    <option value="{{$item->bagian}}">
                                        {{$item->bagian}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <form class=" " action="/hr/dashboard/training/materi/laporan/list">
                                    <div class="form-group mb-3">
                                        <select name="materi" id="state-dropdown" class="form-control form-control-sm">
                                        </select>
                                    </div>
                                  
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-sm btn-primary"> sumbit</button>
                            </form>
                            </div>
                        </div>
                       
                    </div>
                   
            </div>
         

                <table class="table text-sm">
                    <thead class="bg-primary text-light ">
                        <tr>
                            <th scope="col">Tanggal</th>
                            <th scope="col">NIK</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Bagian</th>
                            <th scope="col">Jabatan</th>
                            <th scope="col">Trainer</th>
                            <th scope="col">Nilai PreT</th>
                            <th scope="col">Nilai PostT</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($data as $item)
                            <tr>
                                <td>{{ $item->train_date }}</td>
                                <td>{{ $item->no_payroll }}</td>
                                <td>{{ $item->nama_asli }}</td>
                                <td>{{ $item->bagian }}</td>
                                <td>{{ $item->jabatan }}</td>
                                <td>{{ $item->pemateri }}</td>
                                <td>{{ $item->nilai_pre }}</td>
                                <td>{{ $item->nilai }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>


    
    {{--  --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
  
            /*------------------------------------------
            --------------------------------------------
            Country Dropdown Change Event
            --------------------------------------------
            --------------------------------------------*/
            $('#country-dropdown').on('change', function () {
                var bagian = this.value;
                $("#state-dropdown").html('');
                $.ajax({
                    url: "{{url('id/find_bag')}}",
                    type: "POST",
                    data: {
                        bagian: bagian,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (result) {
                        $('#state-dropdown').html('<option value="">-- Select Materi --</option>');
                        $.each(result.materi, function (key, value) {
                            $("#state-dropdown").append('<option value="' + value
                                .materi + '">' + value.materi + '</option>');
                        });
                        $('#city-dropdown').html('<option value="">-- Select City --</option>');
                    }
                });
            });
  
        });
    </script>
    
    <style>
        .form-control{
            border-radius: 0;
        }
       
        .card-plain {
        opacity: 0;
        animation-name: fade-in;
        animation-duration: 0.3s;
        animation-fill-mode: forwards;
        animation-timing-function: ease-in-out;
        animation-delay: 0.3s;
    }

    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    </style>

@endsection
