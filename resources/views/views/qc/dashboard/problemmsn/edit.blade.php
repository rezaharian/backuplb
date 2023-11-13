@extends('qc.dashboard.layout.layout')

@section('content')

    <div class="container text-xs px-0">


        <form action="{{ url('/qc/dashboard/problemmsn/update', $data->id) }}" method="POST"
            enctype="multipart/form-data">
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
                            <td><label for="example-text-input" class="form-control-label">NO Doc </label></td>
                            <td><input class="form-control" type="text" name="prob_cod" value="{{ $data->prob_cod }}">
                            </td>
                        </tr>
                        <tr>
                            <td><label for="example-text-input" class="form-control-label">Tanggal </label></td>
                            <td><input class="form-control" type="text" name="tgl_input" value="{{ $data->tgl_input }}"
                                    required></td>
                        </tr>
                        <tr>
                            <td><label for="example-text-input" class="form-control-label">Masalah </label></td>
                            <td>
                                <textarea class="form-control" type="text" name="masalah" value="{{ $data->masalah }}" required>{{ $data->masalah }}</textarea>
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
                                    aria-label="Default select example">
                                    <option selected value="{{ $data->line }}">{{ $data->line }}</option>
                                    @foreach ($datal as $item)
                                    <option value="{{ $item->line }}">{{ $item->line }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td><label for="example-text-input" class="form-control-label">Unit Mesin  </label></td>
                            <td>
                                <select class="form-select" name="unitmesin" aria-label="Default select example">
                                    <option selected value="{{ $data->unitmesin }}">{{ $data->unitmesin }}</option>
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
    <div class="row">
        <div class="col">
            <span>Gambar 1</span>
            <img src="/image/{{ $data->img_pro01 }}" class="img-thumbnail" alt="...">
            <input class="form-control" type="file" name="img_pro01" id="formFile">
        </div>
        <div class="col">
            <span>Gambar 2</span>
            <img src="/image/{{ $data->img_pro02 }}" class="img-thumbnail" alt="...">
            <input class="form-control" type="file" name="img_pro02" id="formFile">
        </div>
        <div class="col">
            <span>Gambar 3</span>
            <img src="/image/{{ $data->img_pro03 }}" class="img-thumbnail" alt="...">
            <input class="form-control" type="file" name="img_pro03" id="formFile">
        </div>
        <div class="col">
            <span>Gambar 4</span>
            <img src="/image/{{ $data->img_pro04 }}" class="img-thumbnail" alt="...">
            <input class="form-control" type="file" name="img_pro04" id="formFile">
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-6">
            <button type="button" class="btn btn-sm btn-primary" id="btn-tambah">TAMBAH </button>
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
                    <th>Aksi</th>
                </thead>
                <tbody id="tbl-barang-body">
                    @php $i=1; @endphp
                    @forelse ($data_d as $item)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <input class="form-control" type="hidden" name="item_id[]" value="{{ $item->id }}" id="">
                            <input class="form-control" type="hidden" name="id_no[]" value="{{ $item->id }}">
                            <td> <textarea   cols="19" rows="5"  name="penyebab[]" value="{{ $item->penyebab }}">{{ $item->penyebab }} </textarea></td>
                            <td> <textarea   cols="19" rows="5" type="text" name="perbaikan[]"
                                    value="{{ $item->perbaikan }}" >{{ $item->perbaikan }}</textarea></td>
                            <td><input class="form-control" type="date" name="tgl_rpr[]"
                                    value="{{ $item->tgl_rpr }}" ></td>
                            <td> <textarea   cols="19" rows="5" type="text" name="pencegahan[]"
                                    value="{{ $item->pencegahan }}" >{{ $item->pencegahan }}</textarea></td>
                            <td><input class="form-control" type="date" name="tgl_pre[]"
                                    value="{{ $item->tgl_pre }}" ></td>

                            <td class="text-center">
                                <form onsubmit="return confirm('Apakah Anda Yakin ?');"
                                    action="{{ route('problemmsn.delete_d', $item->id) }}" method="POST">
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
    </div>
    <div class="row btnSave">
        <div class="col-lg-12">
            <button type="submit" class="btn btn-primary">SIMPAN </button>
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
            count += 1;
            $('#tbl-barang-body').append(`
        <tr>
            <td>` + count + `</td>
           
                <input  type="hidden" value="{{ $nod }}` + (count - 1) + `` + (count - 1) + `"  name="id_no[` + (count - 1) + `]" class="form-control" required>
              
            <td>
                <textarea  cols="20" rows="5" type="text"   name="penyebab[` + (count - 1) + `]" class="form-control" required> </textarea>
                </td>
            <td>
                <textarea  cols="20" rows="5" type="text"   name="perbaikan[` + (count - 1) + `]" class="form-control" required> 
            </textarea>
                </td>
            <td>
                <input type="date" name="tgl_rpr[` + (count - 1) + `]" class="form-control" >
            </td>
            <td>
                <textarea  cols="20" rows="5" type="text" name="pencegahan[` + (count - 1) + `]" class="form-control" > </textarea>
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