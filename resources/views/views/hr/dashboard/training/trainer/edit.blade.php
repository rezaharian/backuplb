@extends('hr.dashboard.training.trainer.layout.layout')

@section('content')

    <form class=" px-4 " action="{{ url('/hr/dashboard/training/trainer/update', $data->id) }}" method="POST">
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
        <div class="row">
            <div class="col-md-6">
                <button type="button" class="btn btn-sm btn-primary" id="btn-tambah">TAMBAH </button>
                <a href="{{ route('hr.training.train_trash', $data->id) }}" class="btn btn-sm btn-info "> trash </a>
                <a href="/hr/dashboard/training/tr/train_trash/restore_all/{{ $data->train_cod }}"
                    class="btn btn-success btn-sm">Restore</a>
            </div>
            <div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <table class="table" style="width:100%; ">
                    <thead class="bg-secondary text-light">
                        <th>No.</th>
                        <th>NIK</th>
                        <th>Nama Karyawan</th>
                        <th>Nilai</th>
                        <th>Keterangan</th>
                        <th>Cek HR</th>
                        <th>Action</th>
                    </thead>
                    <tbody id="tbl-barang-body">
                        @php $i=1; @endphp
                        @forelse ($data_d as $item)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <input class="form-control" type="hidden" name="item_id[]" value="{{ $item->id }}"
                                    id="">
                                <td><input class="form-control" type="text" name="no_payroll[]"
                                        value="{{ $item->no_payroll }}" id=""></td>
                                <td><input class="form-control" type="text" name="nama_asli[]"
                                        value="{{ $item->nama_asli }}" id=""></td>
                                <td><input class="form-control" type="text" name="nilai[]"
                                        value="{{ $item->nilai }}" id=""></td>
                                <td><input class="form-control" type="text" name="keterangan[]"
                                        value="{{ $item->keterangan }}" id=""></td>
                                <td>
                                        <select disabled class="form-select" name="approved[]" aria-label="Default select example">
                                            <option selected value="{{ $item->approve }}">{{ $item->approve }}</option>
                                            <option value=""></option>
                                            <option value="Y">Y</option>
                                        </select>
                                    </td>
                                <td class="text-center">
                                    <form onsubmit="return confirm('Apakah Anda Yakin ?');"
                                        action="{{ route('hr.training.delete_d', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">HAPUS</button>
                                    </form>
                                    @method('POST')
                                </td>
                            </tr>

                        @empty
                            <p>dak ada</p>
                        @endforelse

                    </tbody>
                </table>


            </div>
        </div>

        <div class="row btnSave">
            <div class="col-lg-12">
                <button type="submit" class="btn btn-primary">SIMPAN </button>
            </div>
        </div>

        </div>
        <div class="row px-4">
            <div class="col-md-8">
            </div>
            <div class="col-md-4">
                <label for="example-text-input" class="form-control-label">Ferisikasi HR :</label>
                <select disabled class="form-select" name="approve" aria-label="Default select example">
                    <option selected value="TIDAK">TIDAK</option>
                    <option value="ya">ya</option>
                </select>
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
                count += 1;
                $('#tbl-barang-body').append(`
            <tr>
                <td>` + count + `</td>
                <td>
                    <input type="text" name="no_payroll[` + (count - 1) + `]" class="form-control" required>
                </td>
                <td>
                    <input type="text" name="nama_asli[` + (count - 1) + `]" class="form-control" required>
                </td>
                <td>
                    <input type="text" name="nilai[` + (count - 1) + `]" class="form-control" >
                </td>
                <td>
                    <input type="text" name="keterangan[` + (count - 1) + `]" class="form-control" >
                </td>
                <td>
                    <select disabled class="form-select disabled " name="approved[` + (count - 1) + `]" aria-label="Default select example">
                        <option value=""></option>
                        <option value="Y">Y</option>
                    </select>
                </td class="text-center">
                <td><button class="btn removeItem btn-sm btn-danger ">hapus</button></td>
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




@endsection
