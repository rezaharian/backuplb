@extends('umum.dashboard.layout.layout')

@section('content')
    <div class="container">
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
        <div class="alert alert-danger text-center">
            <strong>{{ Session::get('success') }}</strong>
        </div>
    @endif
        <form class=" px-2  " action="{{ url('/umum/dashboard/absen/update', $data->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <table class="table">
                        <tr>
                            <td><label for="example-text-input" class="form-control-label  text-secondary">NO Payroll </label>
                            </td>
                            <td>
                                <input readonly id="no_payroll" class="form-control form-control-sm" name="no_payroll"
                                    value="{{ $data->no_payroll }}">
                            </td>
                        </tr>
                        <tr>
                            <td><label for="example-text-input" class="form-control-label  text-secondary">Nama </label>
                            </td>
                            <td><input readonly class="form-control form-control-sm" id="nama_asli" type="text"
                                    name="nama_asli" value="{{ $data->nama_asli }}">
                            </td>
                        </tr>
                    </table>

                </div>
                <div class="col-md-6">
                    <table class="table">
                        <tr>
                            <td><label for="example-text-input" class="form-control-label  text-secondary">Masuk Kerja
                                </label>
                            </td>
                            <td><input readonly class="form-control form-control-sm" id="tgl_masuk" type="text"
                                    name="tgl_masuk" value="{{ $data->tgl_masuk }}">
                            </td>
                        </tr>
                        <tr>
                            <td><label for="example-text-input" class="form-control-label  text-secondary">Bagian </label>
                            </td>
                            <td><input readonly class="form-control form-control-sm" id="bagian" type="text"
                                    name="bagian" value="{{ $data->bagian }}">
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <button type="button" class="btn btn-primary btn-sm m-1  mt-3" id="btn-tambah">TAMBAH </button>
                    <table class="table  align-items-center mb-0 table-striped table-hover  " style="width:100%;">
                        <thead class="bg-primary text-center text-light shadow font-weight-bolder">
                            <th hidden>No</th>
                            <th hidden>int_d</th>
                            <th>Tanggal</th>
                            <th>Kelompok Absen</th>
                            <th>Ambil Cuti Thn</th>
                            <th>Keterangan</th>
                            <th>Action</th>
                        </thead>
                        <tbody id="tbl-barang-body">
                            @php $i=0; @endphp
                            @foreach ($data_d as $item)
                                <tr>
                                    <td hidden>{{ $i++ }}</td>

                                    <input class="form-control form-control-sm" type="hidden" name="item_id[]"
                                        value="{{ $item->id }}" id="" readonly>

                                    <td hidden>
                                        <input class="form-control form-control-sm" name="int_absen_d[]"
                                            value="{{ $int_absen_d }}[{{ $i }}]" id="" readonly>
                                    </td>

                                    <td><input type="date" value="{{ $item->tgl_absen }}" name="tgl_absen[]"
                                            class="form-control form-control-sm"> </td>

                                    <td><select type="text" name="dsc_absen[]" class="form-control form-control-sm">
                                            <option value="{{ $item->dsc_absen }}">{{ $item->dsc_absen }}</option>
                                            @foreach ($def as $_a)
                                                <option value="{{ $_a->dsc_absen }}"> {{ $_a->dsc_absen }} </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><select type="text" name="thn_jns[]" class="form-control form-control-sm">
                                            <option selected value="{{ $item->thn_jns }}">{{ $item->thn_jns }}</option>
                                            <option value="2021">2021</option>
                                            <option value="2022">2022</option>
                                            <option value="2023">2023</option>
                                            <option value="2024">2024</option>
                                            <option value="2025">2025</option>
                                            <option value="2026">2026</option>
                                        </select>
                                    </td>

                                    <td><input type="text" name="keterangan[]" value="{{ $item->keterangan }}"
                                            class="form-control form-control-sm"></td>

                                    <td class="text-center">
                                        <a class="btn btn-danger btn-md m-0"
                                            onclick="return confirm('Apakah Anda Yakin  ?');"
                                            href="{{ route('umum.absen.delete_d', $item->id) }}"><i
                                                class="fas fa-trash"></i></a>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
    </div>

    </div>
    <div class=" btnSave  text-center item-center mt-2">
        <button type="submit" class="btn btn-primary btn-sm border-success">Simpan</button>
    </div>
    </form>



    <script src="https://code.jquery.com/jquery-3.6.0.slim.js"
        integrity="sha256-HwWONEZrpuoh951cQD1ov2HUK5zA5DwJ1DNUXaM6FsY=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script type="text/javascript"></script>

    <script>
        $(function() {
            var count = {{ $jmlh_data_d }} + 1;

            // if (count == 0) {
            //     $('.btnSave').hide();
            // }
            $('#btn-tambah').on('click', function() {
                count += 1;
                $('#tbl-barang-body').append(`
<tr>
<td hidden>` + count + `</td>            
<td hidden >
            <input type="text" name="int_absen_d[` + (count - 1) + `]" value="{{ $int_absen_d }}[` + (count - 1) + `]" class="form-control form-control-sm" >
        </td>
<td>
    <input type="date" name="tgl_absen[` + (count - 1) + `]" class="form-control form-control-sm" >
</td>

<td>
    <select type="text" name="dsc_absen[` + (count - 1) + `]" class="form-control form-control-sm" >
        @foreach ($def as $item)
            <option value="{{ $item->dsc_absen }}"> {{ $item->dsc_absen }} </option>
        @endforeach
    </select
</td>
<td>
            <select type="text" name="thn_jns[` + (count - 1) + `]" class="form-control form-control-sm" >
                <option value="2021">2021</option>
                <option  value="2022">2022</option>
                <option selected value="2023">2023</option>
                <option value="2024">2024</option>
                <option value="2025">2025</option>
                <option value="2026">2026</option>
            </select
        </td>
<td>
    <input type="text" name="keterangan[` + (count - 1) + `]" class="form-control form-control-sm" >
</td>

<td class="text-center"><button class="btn removeItem m-0 btn-md btn-danger "><i class="fas fa-trash"></i></button></td>
</tr>
`);

                $('.removeItem').on('click', function() {
                    $(this).closest("tr").remove();
                    count -= 1;

                })
            })

        })



        $(document).ready(function() {
            $('#id').on('input', function() {
                var idPelanggan = $(this).val();
                if (idPelanggan != '') {
                    $.ajax({
                        url: '/find_pegawai_umum/' + idPelanggan,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#no_payroll').val(data.no_payroll);
                            $('#nama_asli').val(data.nama_asli);
                            $('#tgl_masuk').val(data.tgl_masuk);
                            $('#bagian').val(data.bagian);
                        }
                    });
                }
            });
        });
    </script>
@endsection
