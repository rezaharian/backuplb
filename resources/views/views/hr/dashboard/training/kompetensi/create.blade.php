@extends('hr.dashboard.layout.layout')

@section('content')

    <form action="{{ url('/hr/dashboard/training/kompetensi/store') }}" method="POST" class="px-4">
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

            <div class="col-md-2">
              
                <div class="form-group">
                    <label for="example-text-input" class="form-control-label">NO DOC</label>
                    <input class="form-control" type="text" name="kompe_cod" value="{{ $nd }}">


                    <label for="example-text-input" class="form-control-label">BAGIAN</label>
                    <select class="form-select" name="bagian" aria-label="Default select example">
                        <option selected></option>
                        @foreach ($bagian as $item)
                        <option value="{{ $item->bagian }}">{{ $item->bagian }}</option>
                        @endforeach
                    </select>

                </div>
            </div>
            <div class="col-md-8">

            </div>
            <div class="row">
                <div class="col-lg-12">
                    <button type="button" class="btn btn-sm btn-primary" id="btn-tambah">TAMBAH </button>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <table class="table" style="width:100%;">
                        <thead class="bg-secondary">
                            <th>No.</th>
                            <th>Kompetensi</th>
                            <th>jenis Kompetensi</th>
                            <th>Action</th>
                        </thead>
                        <tbody id="tbl-barang-body">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row btnSave">
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-success btn-lg">SIMPAN </button>
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
            var count = 0;

            if (count == 0) {
                $('.btnSave').hide();
            }
            $('#btn-tambah').on('click', function() {
                count += 1;
                $('#tbl-barang-body').append(`
            <tr>
                <td>` + count + `</td>
                <td>
                    <input type="text" name="kompetensi[` + (count - 1) + `]" class="form-control" required>
                </td>
                <td>
                    <select class="form-select" name="jenis[` + (count - 1) + `]" aria-label="Default select example">
                        <option selected>Pilih Kompetensi</option>
                        <option value="Soft Conpetency">Soft Conpetency</option>
                        <option value="Core Conpetency">Core Conpetency</option>
                    </select>
                </td>
                <td><button class="btn removeItem btn-sm btn-danger">hapus</button></td>
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




@endsection
