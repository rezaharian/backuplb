@extends('superadmin.dashboard.layout.layout')

@section('content')

    <div class="container text-xs px-4">


        <form action="{{ url('/superadmin/dashboard/problemmsn/store') }}" method="POST" enctype="multipart/form-data">
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
                            <td><label  for="example-text-input" class="form-control-label">NO Doc  </label></td>
                            <td><input class="form-control "  type="text " name="prob_cod" value="{{ $no }}">
                            </td>
                        </tr>
                        <tr>
                            <td><label for="example-text-input" class="form-control-label">Tanggal  </label></td>
                            <td><input class="form-control" type="date" name="tgl_input" value="" required ></td>
                        </tr>
                        <tr>
                            <td><label for="example-text-input" class="form-control-label">Masalah  </label></td>
                            <td>
                                <textarea class="form-control" type="text" name="masalah" value="" required ></textarea>
                            </td>

                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table">
                        <tr>
                            <td><label for="example-text-input" class="form-control-label">Line  </label></td>
                            <td>
                                <select class="form-select" name="line" id="package"
                                    aria-label="Default select example" required>
                                    <option selected value=""></option>
                                    @foreach ($datal as $item)
                                    <option value="{{ $item->line }}">{{ $item->line }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td><label for="example-text-input" class="form-control-label">Unit Mesin  </label></td>
                            <td>
                                <select class="form-select" name="unitmesin" aria-label="Default select example" required>
                                    <option selected value=""></option>
                                    @foreach ($datau as $item)
                                    <option value="{{ $item->unit_nam }}">{{ $item->unit_nam }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                </div>
            </div>
            </tr>   
            </table>   
    </div>

<div class="row px-3 mb-2">
    <div class="col-md-3">
        <div class="mb-3">
            <label for="formFile" class="form-label">Image 1</label>
            <input class="form-control" type="file" name="img_pro01" id="formFile">
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label for="formFile" class="form-label">Image 2</label>
            <input class="form-control" type="file" name="img_pro02" id="formFile">
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label for="formFile" class="form-label">Image 3</label>
            <input class="form-control" type="file" name="img_pro03" id="formFile">
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label for="formFile" class="form-label">Image 4</label>
            <input class="form-control" type="file" name="img_pro04" id="formFile">
        </div>
    </div>
</div>




    <div class="row">
        <div class="col-lg-12">
            <button type="button" class="btn btn-primary btn-sm" id="btn-tambah">TAMBAH </button>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <table class="table table-striped table-hover  " style="width:100%;">
                <thead class="bg-secondary text-light">
                    <th>No.</th>
                    <th>Penyebab</th>
                    <th>Perbaikan</th>
                    <th>TGL Perbaikan</th>
                    <th>Pencegahan</th>
                    <th>TGL Pencegahan</th>
                    <th>Action</th>
                </thead>
                <tbody id="tbl-barang-body" >
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
            <button type="submit" class="btn btn-success btn-lg border-danger">SIMPAN </button>
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
                
                    <input  type="hidden" value="{{ $nod }}` + (count - 1) + `"  name="id_no[` + (count - 1) + `]" class="form-control" required>
                 
                <td>
                    <textarea cols="20" rows="1" type="text"   name="penyebab[` + (count - 1) + `]" class="form-control" required> </textarea>
                    </td>
                <td>
                    <textarea cols="20" rows="1" type="text"   name="perbaikan[` + (count - 1) + `]" class="form-control" required> 
                </textarea>
                    </td>
                <td>
                    <input type="date" name="tgl_rpr[` + (count - 1) + `]" class="form-control" >
                </td>
                <td>
                    <textarea cols="20" rows="1" type="text" name="pencegahan[` + (count - 1) + `]" class="form-control" > </textarea>
                </td>
                <td>
                    <input type="date" name="tgl_pre[` + (count - 1) + `]" class="form-control" >
                </td>
              
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
    </div>
    
@endsection