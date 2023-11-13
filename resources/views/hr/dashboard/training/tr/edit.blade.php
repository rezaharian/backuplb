@extends('hr.dashboard.layout.layout')

@section('content')

    <form class=" px-2  " action="{{ url('/hr/dashboard/training/tr/update', $data->id) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        <div class="card card-plain border-primary rounded-0 pt-2 mb-2">

            <div class="row ">
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (Session::has('success'))
                    <div class="alert alert-info text-center px-5">
                        <p>{{ Session::get('success') }}</p>
                    </div>
                @endif

                <div class="col-md-4">


                    <table class="table table-sm">

                        <tr hidden>
                            <td><label for="example-text-input " class="form-control-label">No Pelatihan</label></td>
                            <td><input class="form-control form-control-sm" type="text" name="train_cod"
                                    value="{{ $data->train_cod }}"></td>
                        </tr>
                        <tr>
                            <td><label for="example-text-input" class="form-control-label">Tempat</label></td>
                            <td><input class="form-control form-control-sm" type="text" name="tempat"
                                    value="{{ $data->tempat }}"></td>
                        </tr>
                        <tr>
                            <td><label for="example-text-input" class="form-control-label">Pemateri</label></td>
                            <td>
                                <select class="form-select form-select-sm" name="pemateri"
                                    aria-label="Default select example">
                                    <option selected value="{{ $data->pemateri }}">{{ $data->pemateri }}</option>
                                    @foreach ($pemateri as $item)
                                        <option value="{{ $item->name }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="example-text-input" class="form-control-label">Pelatihan</label></td>
                            <td>
                                <textarea class="form-control form-control-sm" type="text" name="pltran_nam" value="{{ $data->pltran_nam }}">{{ $data->pltran_nam }}</textarea>
                            </td>

                        </tr>
                    </table>
                </div>
                <div class="col-md-4">
                    <table class="table table-sm">
                        <tr>
                            <td><label for="example-text-input" class="form-control-label">Tipe</label></td>
                            <td>
                                <select class="form-select rounded-0 form-select-sm" name="tipe" id="package"
                                    aria-label="Default select example">
                                    <option selected value="{{ $data->tipe }}">{{ $data->tipe }}</option>
                                    <option value="Sosialisasi">Sosialisasi</option>
                                </select>
                            </td>
                        </tr>

                        <tr hidden>
                            <td><label for="example-text-input" class="form-control-label">Kompetensi</label></td>
                            <td>
                                <select class="form-select form-select-sm" name="kompetensi"
                                    aria-label="Default select example">
                                    <option selected value="{{ $data->kompetensi }}">{{ $data->kompetensi }}</option>
                                    @foreach ($data_d as $item)
                                        <option value="{{ $item->kompetensi }}">{{ $item->kompetensi }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>


                        <tr>
                            <td><label for="example-text-input" class="form-control-label">tanggal</label></td>
                            <td><input class="form-control form-control-sm" type="date" name="train_dat"
                                    value="{{ $data->train_dat }}">
                            </td>
                        </tr>
                        <tr>
                            <td><label for="example-text-input" class="form-control-label">Jam</label></td>
                            <td>
                                <div class="row">
                                    <div class="col-md-5">
                                        <input class="form-control form-control-sm md-2" type="time" name="jam"
                                            value="{{ $data->jam }}">
                                    </div>s/d
                                    <div class="col-md-5">
                                        <input class="form-control form-control-sm" type="time" name="sdjam"
                                            value="{{ $data->sdjam }}">
                            </td>
                        </tr>

                    </table>
                </div>



                <div class="col-md-4 ">
                    {{-- <div class="input-group mt-2 ">
                    <div>
                        <textarea  class="form-control form-control-sm" type="text" value="">{{ $data->file }}
                        </textarea>
                    </div>
                  <div>

                      <input type="file" name="file" class="form-control form-control-sm" id="inputGroupFile01">
                    </div>
                  </div> --}}

                    <table class="table table-sm">
                        <tr>
                            <td><label for="example-text-input" class="form-control-label text-secondary">Materi Bagian
                                </label>
                            </td>
                            <td>
                                <select id="country-dropdown" class="form-control form-control-sm">
                                    <option value="">-- Select Bagian --</option>
                                    @foreach ($bagian as $item)
                                        <option value="{{ $item->bagian }}">
                                            {{ $item->bagian }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            {{-- {{ $data->train_tema }} --}}

                            <td><label for="example-text-input" class="form-control-label text-secondary">Materi Pelatihan
                                </label></td>
                            <td>
                                <select class="form-control form-control-sm" type="text" id="state-dropdown"
                                    name="train_tema" required>
                                    @foreach ($materi as $item)
                                        <option value="{{ $item->materi }}"
                                            @if ($item->materi == $data->train_tema) selected @endif>{{ $item->materi }}</option>
                                    @endforeach
                                </select>

                            </td>
                        </tr>
                        <tr>

                            <td><label for="example-text-input" class="form-control-label text-secondary">File Absen
                                </label></td>
                            <td>
                                <textarea class="form-control form-control-sm" type="text" value="">{{ $data->file_absen }}
                            </textarea>
                                <input type="file" name="file_absen" class="form-control form-control-sm"
                                    id="inputGroupFile01">

                            </td>
                        </tr>

                    </table>


                </div>

            </div>
        </div>

        <link rel="stylesheet" type="text/css"
            href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
        <script type="text/javascript">
            function getcustomerid(element) {
                var no_payroll = element.options[element.selectedIndex].value; // get selected option customer ID value
                var nama_asli = element.options[element.selectedIndex].text; // get Customer email   
                var nik = element.options[element.selectedIndex].value; // get Customer email   
                document.getElementById('no_payroll').value = no_payroll;
                document.getElementById('nama_asli').value = nama_asli;
                document.getElementById('nik').value = nik;


            }
        </script>
        
        <div class="card card-plain border-primary rounded-0 pt-2 mb-2">
        <div class="row m-0  p-1">


            <input class="no_payroll form-control form-control-sm" type="text" id="nama_asli" required="required"
                readonly hidden>
            <div class="col-md-2 ">
                <small class="font-weight-bolder ms-1 text-light" hidden>NIK</small>
                <input class="no_payroll form-control form-control-sm " type="text" id="nik"
                    required="required" placeholder="NIK" readonly>
            </div>
            <div class="col-md-4 ">
                <small class="font-weight-bolder ms-1 text-light" hidden>Cari Nama Karyawan</small>
                <select class="nama_asli form-control font-weight-bolder  " type="text" id="no_payroll"
                    onChange="getcustomerid(this);"></select>
            </div>
            <div class="col-md-4 ">
                <button type="button" class="btn btn-sm btn-primary m-0" id="btn-tambah"> Tambah </button>
            </div>
            <div class="col-md-2 text-right ">
                <a href="{{ route('hr.training.train_trash', $data->id) }}" class=" text-lg m-0"><i
                        class="fas fa-trash-restore-alt"></i></a>
                <a href="/hr/dashboard/training/tr/train_trash/restore_all/{{ $data->train_cod }}"
                    class=" text-lg m-0"><i class="fa-solid fa-recycle"></i></a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <table class="table table-sm kepala-tabel" style="width:100%; ">
                    <thead class="bg-primary text-light">
                        <th hidden>No.</th>
                        <th>NIK</th>
                        <th>Nama Karyawan</th>
                        <th>Nilai Pretest</th>
                        <th>Nilai Post Test</th>
                        <th>Keterangan</th>
                        <th>Cek HR</th>
                        <th>Action</th>
                    </thead>
                    <tbody id="tbl-barang-body">
                        @php $i=0; @endphp
                        @forelse ($data_d as $item)
                            <tr class="badan-tabel">
                                <td hidden>{{ $i++ }}</td>
                                <input class="form-control form-control-sm" type="hidden" name="item_id[]"
                                    value="{{ $item->id }}" id="" readonly>
                                <td><input class="form-control form-control-sm" type="text" name="no_payroll[]"
                                        value="{{ $item->no_payroll }}" id="" readonly></td>
                                <td><input class="form-control form-control-sm" type="text" name="nama_asli[]"
                                        value="{{ $item->nama_asli }}" id="" readonly></td>
                                <td><input class="form-control form-control-sm" type="text" name="nilai_pre[]"
                                        value="{{ $item->nilai_pre }}" id=""></td>
                                <td><input class="form-control form-control-sm" type="text" name="nilai[]"
                                        value="{{ $item->nilai }}" id=""></td>
                                <td><input class="form-control form-control-sm" type="text" name="keterangan[]"
                                        value="{{ $item->keterangan }}" id=""></td>
                                <td>

                                    {{-- <div class="form-check">
                                        <input class="form-check-input"  name="approved[{{ ($i)-1 }}]" value="Y" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                                        <label class="form-check-label" for="flexRadioDefault1">
                                         OK
                                        </label>
                                      </div>
                                      <div class="form-check" hidden>
                                        <input class="form-check-input"  name="approved[{{ ($i)-1 }}]" value="" type="radio" name="flexRadioDefault" id="flexRadioDefault2" checked >
                                        <label class="form-check-label" for="flexRadioDefault2">
                                         NG
                                        </label>
                                      </div> --}}

                                    <select class="form-select form-select-sm" name="approved[]"
                                        aria-label="Default select example">
                                        <option selected value="{{ $item->approve }}">{{ $item->approve }}</option>
                                        <option value="Y">Y</option>
                                        <option value="">N</option>
                                    </select>
                                </td>
                                <td class="text-center">
                                    <a class="btn btn-danger btn-sm m-0"
                                        href="{{ route('hr.training.delete_d', $item->id) }}">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>

                            </tr>


                        @empty
                            <p class="font-weight-bolder text-danger">Belum ada data..</p>
                        @endforelse
                    </tbody>
                </table>


            </div>
        </div>



        <div class="row px-4">
            <div class="col-md-7">
            </div>
            <div class="col-md-2 text-right">

                <label for="example-text-input" class="form-control-label">Ferisikasi HR :</label>
            </div>
            <div class="col-md-2">
                <select class="form-select form-select-sm border-primary" name="approve"
                    aria-label="Default select example">
                    <option selected value="{{ $data->approve }}">{{ $data->approve }}</option>
                    <option value="NO">NO</option>
                    <option value="YES">YES</option>
                </select>

            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary btn-md"><i class="fa-solid fa-save"></i> </button>

            </div>
        </div>
    </form>
</div>

    <script src="https://code.jquery.com/jquery-3.6.0.slim.js"
        integrity="sha256-HwWONEZrpuoh951cQD1ov2HUK5zA5DwJ1DNUXaM6FsY=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous">
    </script>

    <script>
        $(function() {
            var count = {{ $jmlh_d }};

            if (count == 0) {
                $('.btnSave').hide();
            }
            $('#btn-tambah').on('click', function() {
                var r = document.getElementById('nama_asli').value;
                var s = document.getElementById('no_payroll').value;
                document.getElementById('nama_asli').value = '';
                document.getElementById('no_payroll').value = '';

                count += 1;

                $('#tbl-barang-body').append(`
            <tr >
                <td hidden>` + count + `</td>
                <td  >
                    <input type="text" class="form-control form-control-sm" value="` + s + `" id="no_payroll` + (
                        count - 1) +
                    `" name="no_payroll[` + (count - 1) + `]" value="" required="required" readonly>
                </div>              
                <td>
                    <input class="form-control form-control-sm" value="` + r + `" id="nama_asli` + (count - 1) +
                    `" name="nama_asli[` +
                    (count - 1) + `]"  
                    required="required" readonly>
               
                </td>
                <td>
                    <input type="text" name="nilai_pre[` + (count - 1) + `]" class="form-control form-control-sm" >
                </td>
                <td>
                    <input type="text" name="nilai[` + (count - 1) + `]" class="form-control form-control-sm" >
                </td>
                <td>
                    <input type="text" name="keterangan[` + (count - 1) + `]" class="form-control form-control-sm" >
                </td>
                <td>
                    <select class="form-select form-select-sm" name="approved[` + (count - 1) + `]" aria-label="Default select example">
                        <option value=""></option>
                        <option value="Y">Y</option>
                    </select>
                </td>
                <td  class="text-center item-center" ><button class="btn removeItem btn-sm btn-danger"><i class="fas fa-trash"></i></button></td>
            </tr>
        `);

                if (count > 0) {
                    $('.btnSave').show();
                }

                $('.removeItem').on('click', function() {
                    $(this).closest("tr").remove();
                    count -= 1;
                    if (count == 0) {
                        $('.btnSave').hide();
                    }
                })
            })
        })
    </script>
    <script>
        $('#package').on('change', function() {
            const selectedPackage =
                $('#package').val();
            $('#selected').text(selectedPackage);
        });
    </script>


    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>


    <script type="text/javascript">
        $('.nama_asli').select2({
            placeholder: 'Cari Nama Karyawan',
            ajax: {
                url: '/autocompleted',
                dataType: 'json',
                delay: 50,
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: item.nama_asli,
                                nik: item.no_payroll,
                                id: item.no_payroll,
                                // id: item.id,

                            }
                        })
                    };
                },
                cache: false
            }
        });
    </script>
    {{--  --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {

            /*------------------------------------------
            --------------------------------------------
            Country Dropdown Change Event
            --------------------------------------------
            --------------------------------------------*/
            $('#country-dropdown').on('change', function() {
                var bagian = this.value;
                $("#state-dropdown").html('');
                $.ajax({
                    url: "{{ url('id/find_bag') }}",
                    type: "POST",
                    data: {
                        bagian: bagian,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(result) {
                        $('#state-dropdown').html(
                            '<option value="">-- Select Materi --</option>');
                        $.each(result.materi, function(key, value) {
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
        .kepala-tabel {
            font-size: 12px;
        }

        .badan-tabel {
            font-size: 10px;
        }

        tr {
            padding-top: 0;
            padding-bottom: 0;
        }

        td {
            padding-top: 0;
            padding-bottom: 0;
        }


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
