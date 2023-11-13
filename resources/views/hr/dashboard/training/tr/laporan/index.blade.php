@extends('hr.dashboard.layout.layout')

@section('content')
    <div class="container  px-3">
        <div class="row">

            <div class="col-md-12">
                <div class="card card-plain p-2 border-primary rounded-0" style="opacity: 0; transition: opacity 0.8s ease;">

                    <form class="mx-2 mt-3" action="/hr/dashboard/training/rek_tra/">
                        <div class="row">
                            <div class="col-md-3">
                                <select class="nama_asli form-control font-weight-bolder" name="no_payroll" type="text"
                                    id="no_payroll">
                                </select>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select class="form-control form-control-sm"name="jns_tr" id="">
                                        <option value="Pelatihan">PELATIHAN</option>
                                        <option value="Sosialisasi">SOSIALISASI</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input type="date" class="form-control form-control-sm" name="train_dat_awal"
                                        id="exampleFormControlInput1" placeholder="dari tanggal" required>
                                </div>
                            </div>s/d
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input type="date" class="form-control form-control-sm" name="train_dat_akhir"
                                        id="exampleFormControlInput1" placeholder="sampai tanggal" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary  btn-sm form-control">submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <div class="row mt-3 mb-0">

            <div class="col-md-8">

            </div>
            <div class="col-md-4 text-center ">

            </div>
        </div>
        <div class="row px-0  mt-3 text">
            <form action="/hr/dashboard/training/rek_tra_print" target="_blank">
                <div class="row">
                    <div class="col-md-2 "></div>
                    <div class="col-md-8  text-center">
                        <h6>
                            Data {{ $jenis }} dari {{ $dtv1 }} sampai {{ $dtv2 }}
                        </h6>
                    </div>

 


                    <div class="col-md-2  text ">
                        <input type="date" class="form-control form-control-sm" value="{{ $aw }}"
                            name="train_dat_awal" id="exampleFormControlInput1" placeholder="dari tanggal" hidden>
                        <input type="date" class="form-control form-control-sm" value="{{ $ak }}"
                            name="train_dat_akhir" id="exampleFormControlInput1" placeholder="sampai tanggal" hidden>
                        <div class="form-group">
                            <input type="text" class="form-control form-control-sm" value="{{ $jenis }}"
                                name="jenis" id="exampleFormControlInput1" placeholder="sampai tanggal" hidden>
                            <div class="form-group text-right">
                                <input type="text" class="form-control form-control-sm" value="{{ $nm }}"
                                    name="nm" id="exampleFormControlInput1" placeholder="dari tanggal" hidden>
                                <button type="submit" class="btn  btn-md btn-primary  mb-0 "><i
                                        class="fas fa-print"></i></button>
                            </div>
                        </div>
                    </div>
            </form>
        </div>
        @if ($jenis == 'Sosialisasi')
            <table class="table" style="font-size: 7pt;">
                <thead class="bg-primary text-light ">
                    <tr>
                        <th scope="col">NIK</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Bagian</th>
                        <th scope="col">Jabatan</th>


                        <th scope="col">Total Jam</th>

                        <th scope="col">Detail</th>
                    </tr>
                </thead>
                <tbody style="font-size: 7;">

                    @foreach ($data as $item)
                        <tr>
                            <td>{{ $item->no_payroll }}</td>
                            <td>{{ $item->nama_asli }}</td>
                            <td>{{ $item->bagian }}</td>
                            <td>{{ $item->jabatan }}</td>


                            <td>{{ $item->totaljam }}</td>

                            <td><a
                                    href="{{ route('hr.training.tra_kar_detail', ['id' => $item->no_payroll, 'dtv1' => $dtv1, 'dtv2' => $dtv2, 'jenis' => $jenis]) }}"><i
                                        class="fas fa-eye"></i>
                                </a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <table class="table" style="font-size: 7pt;">
                <thead class="bg-primary text-light ">
                    <tr>
                        <th scope="col">NIK</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Bagian</th>
                        <th scope="col">Jabatan</th>
                        <th scope="col">Rata2 PreT</th>
                        <th scope="col">Rata2 PostT</th>
                        <th scope="col">Target</th>
                        <th scope="col">Total Jam</th>
                        <th scope="col">Kurang Jam</th>
                        <th scope="col">Detail</th>
                    </tr>
                </thead>
                <tbody style="font-size: 7;">

                    @foreach ($data as $item)
                        <tr>
                            <td>{{ $item->no_payroll }}</td>
                            <td>{{ $item->nama_asli }}</td>
                            <td>{{ $item->bagian }}</td>
                            <td>{{ $item->jabatan }}</td>
                            <td>{{ $item->totalnilaipre }}</td>
                            <td>{{ $item->totalnilai }}</td>
                            <td>{{ $item->jumlah_jam }}</td>
                            <td>{{ $item->totaljam }}</td>
                            <td>{{ $item->kurangjam }}</td>
                            <td><a
                                    href="{{ route('hr.training.tra_kar_detail', ['id' => $item->no_payroll, 'dtv1' => $dtv1, 'dtv2' => $dtv2, 'jenis' => $jenis]) }}"><i
                                        class="fas fa-eye"></i>
                                </a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

    </div>

    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>


    <script type="text/javascript">
        $('.nama_asli').select2({
            placeholder: 'SEMUA KARYAWAN',
            ajax: {
                url: '/autocompleted',
                dataType: 'json',
                delay: 5,
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: [item.nama_asli, item.no_payroll],
                                id: item.no_payroll,
                                // id: item.id,

                            }
                        })
                    };
                },
                cache: false
            }
        });



        document.addEventListener("DOMContentLoaded", function() {
            var formCard = document.querySelector(".card-plain");
            formCard.style.opacity = "1";
        });
    </script>


    </div>
@endsection
