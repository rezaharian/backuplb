@extends('qc.dashboard.layout.layout')

@section('content')

    <form class=" px-4 " action="{{ url('/qc/training/qtrupdate', $data->id) }}" method="POST">
        @csrf
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
            @if (Session::has('success'))
                <div class="alert alert-success text-center">
                    <p>{{ Session::get('success') }}</p>
                </div>
            @endif
            <div class="col-md-4">


                <table class="table">

                    <tr>
                        <td><label for="example-text-input" class="form-control-label">NO Pelatihan</label></td>
                        <td><input class="form-control" type="text" name="train_cod" value="{{ $data->train_cod }}"></td>
                    </tr>
                    <tr>
                        <td><label for="example-text-input" class="form-control-label">Tempat</label></td>
                        <td><input class="form-control" type="text" name="tempat" value="{{ $data->tempat }}"></td>
                    </tr>
                    <tr>
                        <td><label for="example-text-input" class="form-control-label">Pemateri</label></td>
                        <td><input class="form-control" type="text" name="pemateri" value="{{ $data->pemateri }}"></td>
                    </tr>
                    <tr>
                        <td><label for="example-text-input" class="form-control-label">Pelatihan</label></td>
                        <td>
                            <textarea class="form-control" type="text" name="pltran_nam" value="{{ $data->pltran_nam }}">{{ $data->pltran_nam }}</textarea>
                        </td>

                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table">
                    <tr>
                        <td><label for="example-text-input" class="form-control-label">Tipe</label></td>
                        <td>
                            <select class="form-select" name="tipe" id="package" aria-label="Default select example">
                                <option selected></option>
                                <option selected value="{{ $data->tipe }}">{{ $data->tipe }}</option>
                                <option value="Sosialisasi">Sosialisasi</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td><label for="example-text-input" class="form-control-label">Kompetensi</label></td>
                        <td>
                            <select class="form-select" name="kompetensi" aria-label="Default select example">
                                <option selected value="{{ $data->kompetensi }}">{{ $data->kompetensi }}</option>
                                @foreach ($data_d as $item)
                                    <option value="{{ $item->kompetensi }}">{{ $item->kompetensi }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>


                    <tr>
                        <td><label for="example-text-input" class="form-control-label">tanggal</label></td>
                        <td><input class="form-control" type="date" name="train_dat" value="{{ $data->train_dat }}">
                        </td>
                    </tr>
                    <tr>
                        <td><label for="example-text-input" class="form-control-label">Jam</label></td>
                        <td>
                            <div class="row">
                                <div class="col-md-4">
                                    <input class="form-control md-2" type="time" name="jam"
                                        value="{{ $data->jam }}">
                                </div>s/d
                                <div class="col-md-4">
                                    <input class="form-control" type="time" name="sdjam" value="{{ $data->sdjam }}">
                        </td>
            </div>
        </div>


        </tr>
        <tr>
            <td><label for="example-text-input" class="form-control-label">Materi Pelatihan</label></td>
            <td>
                <textarea class="form-control" type="text" name="train_tema" value="{{ $data->train_tema }}">{{ $data->train_tema }}</textarea>
            </td>

        </tr>
        </table>

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
        <div class="row">

            <div class="col-md-4 mt-4">
                <a href="{{ route('training.qtrtrain_trash', $data->id) }}" class="btn btn-sm btn-info "> trash </a>
                <a href="/qc/training/qtrrestore_all/{{ $data->train_cod }}"
                    class="btn btn-success btn-sm">Restore</a>
            </div>
            <input class="no_payroll form-control" type="text" id="nama_asli" required="required" readonly hidden>
            <div class="col-md-2 mb-2">
                <small class="font-weight-bolder ms-1 text-secondary">NIK</small>
                <input class="no_payroll form-control form-control-sm  " type="text" id="nik" required="required" readonly>
            </div>
            <div class="col-md-4 mb-2">
                <small class="font-weight-bolder ms-1 text-secondary">Cari Nama Karyawan</small>
                <select class="nama_asli form-control font-weight-bolder  " type="text" id="no_payroll" onChange="getcustomerid(this);"></select>
            </div>
            <div class="col-md-1 mt-4 ">
                <button type="button" class="btn btn-sm btn-primary" id="btn-tambah">TAMBAH </button>
            </div>
            <div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <table class="table" style="width:100%; ">
                    <thead class="bg-secondary text-light">
                        <th hidden>No.</th>
                        <th>NIK</th>
                        <th>Nama Karyawan</th>
                        <th>Nilai</th>
                        <th>Keterangan</th>
                        <th hidden>Cek HR</th>
                        <th>Action</th>
                    </thead>
                    <tbody id="tbl-barang-body">
                        @php $i=1; @endphp
                        @forelse ($data_d as $item)
                            <tr>
                                <td hidden>{{ $i++ }}</td>
                                <input class="form-control" type="hidden" name="item_id[]" value="{{ $item->id }}"
                                    id="" readonly>
                                <td><input class="form-control" type="text" name="no_payroll[]"
                                        value="{{ $item->no_payroll }}" id=" " readonly ></td>
                                <td><input class="form-control" type="text" name="nama_asli[]"
                                        value="{{ $item->nama_asli }}" id="" readonly></td>
                                <td><input class="form-control" type="text" name="nilai[]"
                                        value="{{ $item->nilai }}" id=""></td>
                                <td><input class="form-control" type="text" name="keterangan[]"
                                        value="{{ $item->keterangan }}" id=""></td>
                                <td hidden>
                                
                                    {{-- <div class="form-check">
                                        <input class="form-check-input"  name="approved[]" value="okk" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                                        <label class="form-check-label" for="flexRadioDefault1">
                                          Default radio
                                        </label>
                                      </div>
                                      <div class="form-check">
                                        <input class="form-check-input"  name="approved[]" type="radio" name="flexRadioDefault" id="flexRadioDefault2" checked>
                                        <label class="form-check-label" for="flexRadioDefault2">
                                          Default checked radio
                                        </label>
                                      </div> --}}
                                    {{-- <input @if ($item->approve == null)
                                    @checked(false)
                                        @else
                                        @checked(true)
                                    @endif 
                                    class="form-check-input"  name="approved[]" type="checkbox"  value="Y" id="flexCheckDefault">
                                 --}}
                                 <select class="form-select" name="approved[]" aria-label="Default select example">
                                    <option selected value="{{ $item->approve }}">{{ $item->approve }}</option>
                                    <option value="Y">Y</option>
                                    <option value="">N</option>
                                </select>
                                </td>
                                <td class="text-center">
                                    <form onsubmit="return confirm('Apakah Anda Yakin ?');"
                                        action="{{ route('training.qtrdelete_d', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">HAPUS</button>
                                    </form>
                                    @method('POST')
                                </td>
                            </tr>

                            @empty
                            <p class="font-weight-bolder text-danger">Belum ada data..</p>
                        @endforelse
                    </tbody>
                </table>


            </div>
        </div>

       
        </div>
        <div class="row px-4">
            <div class="col-md-8">
            </div>
            <div class="col-md-4">
                <label for="example-text-input" hidden class="form-control-label">Ferisikasi HR :</label>
                <select class="form-select " hidden name="approve" aria-label="Default select example">
                    <option selected value="{{ $data->approve }}">{{ $data->approve }}</option>
                    <option value="NO">NO</option>
                    <option value="YES">YES</option>
                </select>
                <div class="row btnSave">
                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-success btn-lg">SIMPAN </button>
                    </div>
                </div>
        
            </div>
        </div>
    </form>
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
            <tr>
                <td hidden>` + count + `</td>
                <td  >
                    <input type="text" class="form-control" value="` + s + `" id="no_payroll` + (count - 1) +
                    `" name="no_payroll[` + (count - 1) + `]" value="" required="required" readonly>
                </div>              
                <td>
                    <input class="form-control" value="` + r + `" id="nama_asli` + (count - 1) + `" name="nama_asli[` +
                    (count - 1) + `]"  
                    required="required" readonly>
               
                </td>
                <td>
                    <input type="text" name="nilai[` + (count - 1) + `]" class="form-control" >
                </td>
                <td>
                    <input type="text" name="keterangan[` + (count - 1) + `]" class="form-control" >
                </td>
                <td hidden>
                    <select  class="form-select" name="approved[` + (count - 1) + `]" aria-label="Default select example">
                        <option value=""></option>
                        <option value="Y">Y</option>
                    </select>
                </td>
                <td class="text-center"><button class="btn removeItem btn-sm btn-danger">hapus</button></td>
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
          url: '/qtrautocomplete',
          dataType: 'json',
          delay: 50,
          processResults: function (data) {
              return {
                  results: $.map(data, function (item) {
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
  </div>


@endsection
