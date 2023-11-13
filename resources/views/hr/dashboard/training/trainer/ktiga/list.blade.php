@extends('hr.dashboard.training.trainer.layout.layout')

@section('content')


    <div class="row">
        @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    @if (session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
    @endif
    
    @if (Session::has('success'))
        <div class="alert alert-info" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif
    <script>
        $('.alert').delay(3000).fadeOut('slow');
    </script>
    
    <div>
        <a href="/trainer/ktiga/create"><button class="btn btn-sm btn-primary">Tambah</button></a>
    </div>

        <div class="">

            <div class="card card-plain card1">
                <div style="height:522px;overflow:auto;">
                    <table class="  align-items-center mb-0 table-bordered border-secondary border-light  ">
                        <thead
                            class=" bg-primary text-center text-uppercase  text-light shadow font-weight-bolder sticky-top  ">
                            <tr style="font-size: 8pt; background-color:rgb(5, 109, 255);" class="text-light">
                                <th style="width:1%;" scope="col">No</th>
                                <th style="width:6%;" scope="col">No Urut </th>
                                <th style="width:7%;" scope="col">Tanggal</th>
                                <th style="width:12%;" scope="col">Pemohon</th>
                                <th style="width:9%;" scope="col">Bagian</th>
                                <th style="width:5%;" scope="col">Jenis</th>
                                <th style="width:14%;" scope="col">Masalah</th>
                                <th style="width:3%;" scope="col">Verif</th>
                                <th style="width:10%;" scope="col">Opt</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 9pt;" class="text-secondary ">
                            @foreach ($ktiga as $item)
                                <tr style="background-color: {{ $loop->iteration % 2 == 0 ? '#f2f2f2' : '#fff' }};">
                                    <th>{{ $loop->iteration }}</th>
                                    <td style="text-align: center;">{{ $item->no_urut }}</td>
                                    <td>{{ $item->tanggal }}</td>
                                    <td>{{ $item->pemohon }}</td>
                                    <td>{{ $item->bagian }}</td>
                                    <td>{{ $item->jenis_masalah }}</td>
                                    <td>{{ $item->masalah }}</td>
                                    <td class="text-center">
                                        @if ($item->hasil_verifikasi)
                                            <i class="fas fa-check-circle text-primary"></i> <!-- Tanda ceklis biru jika hasil verifikasi ada -->
                                        @else
                                            <i class="fas fa-question-circle text-danger"></i> <!-- Tanda tanya merah jika hasil verifikasi null -->
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div>
                                            <a href="{{ route('hr.trainer.ktiga.delete', $item->id) }}"
                                                onclick="return confirm('Apakah Anda Yakin ?');"
                                                class="btn btn-sm m-0 text-danger"> <i class="fa-solid fa-trash"></i> </a>
                                            <a href="{{ route('hr.trainer.ktiga.edit', $item->id) }}"
                                                class="btn btn-sm m-0 text-primary"> <i class="fa-solid fa-pen"></i> </a>
                                            <a href="{{ route('hr.trainer.ktiga.print', $item->id) }}"
                                                class="btn btn-sm m-0 text-primary" target="_blank"> <i class="fa-solid fa-print"></i> </a>
                                    </td>
                </div>
                </tr>
                @endforeach
                </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.slim.js"
        integrity="sha256-HwWONEZrpuoh951cQD1ov2HUK5zA5DwJ1DNUXaM6FsY=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
    <script type="text/javascript"></script>


    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>


    <script type="text/javascript">
        $('.nama_asli').select2({
            placeholder: '',
            ajax: {
                url: '/autocompleted_tdkabsen',
                dataType: 'json',
                delay: 50,
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: item.no_payroll,
                                nik: item.nama_asli,
                                id: item.nama_asli,
                                nop: item.no_payroll
                                // id: item.id,
                            }
                        })
                    };
                },
                cache: false
            }
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#field1").prop("required", true);
            $("#field2").prop("required", true);
            $("#option").change(function() {
                var selectedOption = $(this).val();

                if (selectedOption == "semua") {
                    $("#field1").removeAttr("disabled").prop("required", true);
                    $("#field2").removeAttr("disabled").prop("required", true);
                } else if (selectedOption == "masuk") {
                    $("#field2").attr("disabled", "disabled").prop("required", false);
                    $("#field1").removeAttr("disabled").prop("required", true);
                    $("#field2").val("");
                } else if (selectedOption == "pulang") {
                    $("#field1").attr("disabled", "disabled").prop("required", false);
                    $("#field2").removeAttr("disabled").prop("required", true);
                    $("#field1").val("");
                } else {
                    $("#field1").attr("disabled", "disabled").prop("required", false);
                    $("#field2").attr("disabled", "disabled").prop("required", false);
                }
            });
        });
    </script>

<style>
.card1 {
  opacity: 0;
  animation-name: fade-in;
  animation-duration: 2s;
  animation-fill-mode: forwards;
}

@keyframes fade-in {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

</style>
@endsection
