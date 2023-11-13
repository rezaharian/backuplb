@extends('superadmin.dashboard.layout.layout')

@section('content')

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">

    <div class="container text-xs ">


        <form action="{{ url('/superadmin/dashboard/problemmsn/store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card p-1 mb-1 rounded-0 border-primary">
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
                        <table class="table table-sm">
                            <tr>
                                <td><label for="example-text-input" class="form-control-label">NO Doc </label></td>
                                <td><input class="form-control form-control-sm rounded-0" type="text " name="prob_cod"
                                        value="{{ $no }}" readonly>
                                </td>
                            </tr>
                            <tr>
                                <td><label for="example-text-input" class="form-control-label">Tanggal </label></td>
                                <td><input class="form-control form-control-sm rounded-0" type="date" name="tgl_input"
                                        value="" required></td>
                            </tr>

                        </table>
                    </div>

                    <div class="col-md-4">
                        <table class="table table-sm">
                            <tr>
                                <td><label for="example-text-input" class="form-control-label">Line </label></td>
                                <td>
                                    <select class="form-select form-select-sm rounded-0" name="line" id="package"
                                        aria-label="Default select example" required>
                                        <option selected value=""></option>
                                        @foreach ($datal as $item)
                                            <option value="{{ $item->line }}">{{ $item->line }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td><label for="example-text-input" class="form-control-label">Unit Mesin </label></td>
                                <td>
                                    <select class="form-select form-select-sm rounded-0" name="unitmesin"
                                        aria-label="Default select example" required>
                                        <option selected value=""></option>
                                        @foreach ($datau as $item)
                                            <option value="{{ $item->unit_nam }}">{{ $item->unit_nam }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            </tr>
                        </table>
                    </div>

                    <div class="col-md-4">
                        <tr>
                            <td><label for="example-text-input" class="form-control-label">Masalah </label></td>
                            <td>
                                <textarea class="form-control form-control-sm rounded-0" type="text" name="masalah" value="" required></textarea>
                            </td>
                        </tr>
                    </div>
                </div>

                <hr class="mb-1 m-0 ">
                <div class="row  mb-2">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Image 1</label>
                            <input class="form-control form-control-sm rounded-0" type="file" name="img_pro01"
                                id="formFile">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Image 2</label>
                            <input class="form-control form-control-sm rounded-0" type="file" name="img_pro02"
                                id="formFile">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Image 3</label>
                            <input class="form-control form-control-sm rounded-0" type="file" name="img_pro03"
                                id="formFile">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Image 4</label>
                            <input class="form-control form-control-sm rounded-0" type="file" name="img_pro04"
                                id="formFile">
                        </div>
                    </div>
                </div>
            </div>



            <div class="row">
                <div class="col-lg-12">
                    <button type="button" class="btn btn-primary rounded-0 mt-2 mb-1 btn-sm" id="btn-tambah">TAMBAH </button>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <table class="table table-sm table-striped table-hover  " style="width:100%;">
                        <thead class="bg-primary text-light">
                            <th hidden>No.</th>
                            <th>Penyebab</th>
                            <th>Perbaikan</th>
                            <th>TGL Perbaikan</th>
                            <th>Pencegahan</th>
                            <th>TGL Pencegahan</th>
                            <th>Action</th>
                        </thead>
                        <tbody id="tbl-barang-body">
                        </tbody>
                    </table>


                </div>
            </div>



    </div>
    <div class="row px-4">
        <div class="col-md-8">

        </div>

    </div>
    <div class="row btnSave m-1 text-center item-center" style="display">
        <div class="col-lg-12">
            <button type="submit" class="btn btn-primary btn-md rounded-0  border-danger">SIMPAN </button>
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
                <td hidden>` + count + `</td>
                
                    <input  type="hidden" value="{{ $nod }}` + (count - 1) + `"  name="id_no[` + (count - 1) + `]" class="form-control form-control-sm rounded-0" required>
                 
                <td>
                   <textarea cols="20" rows="1" type="text" type="text"   name="penyebab[` + (count - 1) + `]" class="form-control form-control-sm rounded-0" required> </textarea>
                    </td>
                <td>
                    <textarea cols="20" rows="1" type="text"   name="perbaikan[` + (count - 1) + `]" class="form-control form-control-sm rounded-0" required>  </textarea>
                    </td>
                <td>
                    <input type="date" name="tgl_rpr[` + (count - 1) + `]" class="form-control form-control-sm rounded-0" >
                </td>
                <td>
                    <textarea cols="20" rows="1" type="text" name="pencegahan[` + (count - 1) + `]" class="form-control form-control-sm rounded-0" > </textarea>
                </td>
                <td>
                    <input type="date" name="tgl_pre[` + (count - 1) + `]" class="form-control form-control-sm rounded-0" >
                </td>
              
                <td><button class="btn removeItem m-0 btn-sm btn-danger rounded-0">hapus</button></td>
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
    </div>

@endsection
