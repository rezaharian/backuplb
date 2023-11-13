@extends('hr.dashboard.training.trainer.layout.layout')

@section('content')
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
        <div class="alert alert-info text-center">
            <p>{{ Session::get('success') }}</p>
        </div>
    @endif

    <div class="col-md-1 mt-2">

        <div class="row">
            <div class="row mb-2  justify-content-center">
                <div>
                    {{-- @if ($view->approve == 'YES')
                    
                    <div class="col-md-1 m-1 " @disabled(true)>
                        <a href="{{ route('trainer.delete', $view->id) }}"
                            onclick="return confirm('Apakah Anda Yakin ?');" class="btn btn-md btn-danger m-0 disabled"><i
                                class="fas fa-trash" ></i></a>
                    </div>
                    @else
                    <div class="col-md-1 m-1 " @disabled(false)>
                        <a href="{{ route('trainer.delete', $view->id) }}"
                            onclick="return confirm('Apakah Anda Yakin ?');" class="btn btn-md btn-danger m-0"><i
                                class="fas fa-trash"></i></a>
                    </div>
                    @endif --}}
                    <div class="col-md-1 m-1">
                        <a href="/trainer/print/{{ $view->id }}" target="_blank">
                            <button type="button" class="btn btn-md btn-primary m-0" id="btn-"><i
                                    class="fas fa-print"></i> </button>
                        </a>
                    </div>
                    {{-- @if ($view->approve == 'YES')
                    <div class="col-md-1 m-1 ">
                        <a href="{{ route('trainer.edit', $view->id) }}" class="btn btn-md btn-primary m-0 disabled">
                            <i class="fas fa-pen"></i></a>
                    </div>
                    @else
                    <div class="col-md-1 m-1 ">
                        <a href="{{ route('trainer.edit', $view->id) }}" class="btn btn-md btn-primary m-0 ">
                            <i class="fas fa-pen"></i></a>
                    </div>
                    @endif --}}
                    <div class="col-md-1 m-1 ">
                        <a href="/files/{{ $view->file }}" class="btn btn-md btn-primary m-0" target="_blank">
                            <i class="fas fa-folder"></i>                                </a>

                    </div>
                </div>
            </div>

        </div>

    </div>

    <div class="col-md-5">
        <table class="table ">


            <tr>
                <td><label for="example-text-input" class="form-control-label text-secondary text-secondary">NO
                        Pelatihan</label></td>
                <td><input disabled class="form-control form-control-sm" type="text" name="train_cod"
                        value="{{ $view->train_cod }}">
                </td>
            </tr>
            <tr>
                <td><label for="example-text-input" class="form-control-label text-secondary">Tempat</label></td>
                <td><input disabled class="form-control form-control-sm" type="text" name="tempat"
                        value="{{ $view->tempat }}">
                </td>
            </tr>
            <tr>
                <td><label for="example-text-input disabled"
                        class="form-control-label text-secondary">Pemateri</label></td>
                <td><input disabled class="form-control form-control-sm" type="text" name="pemateri"
                        value="{{ $view->pemateri }}"></td>
            </tr>
            <tr>
                <td><label for="example-text-input disabled"
                        class="form-control-label text-secondary">Pelatihan</label></td>
                <td>
                    <textarea disabled class="form-control form-control-sm" type="text" name="pltran_nam"
                        value="{{ $view->pltran_nam }}">{{ $view->pltran_nam }} </textarea>
                </td>

            </tr>
        </table>
    </div>
    <div class="col-md-5">

        <table class="table ">



            <tr>
                <td><label for="example-text-input disabled" class="form-control-label text-secondary">Tipe</label>
                </td>
                <td>
                    <select disabled class="form-select form-select-sm" name="tipe" id="package"
                        aria-label="Default select example">
                        <option selected value="Pelatihan">Pelatihan</option>
                        <option value="Sosialisasi">Sosialisasi</option>
                    </select>
                </td>
            </tr>

            <tr hidden>
                <td><label for="example-text-input disabled"
                        class="form-control-label text-secondary">Kompetensi</label></td>
                <td>
                    <select disabled class="form-select form-select-sm" name="kompetensi"
                        aria-label="Default select example">
                        <option selected>{{ $view->kompetensi }}</option>

                    </select>
                </td>
            </tr>


            <tr>
                <td><label for="example-text-input disabled"
                        class="form-control-label text-secondary">tanggal</label></td>
                <td><input disabled class="form-control form-control-sm" type="date" name="train_dat"
                        value="{{ $view->train_dat }}"></td>
            </tr>
            <tr>
                <td><label for="example-text-input disabled" class="form-control-label text-secondary">Jam</label>
                </td>
                <td>
                    <div class="row">
                        <div class="col-md-5">
                            <input disabled class="form-control form-control-sm md-2" type="time" name="jam"
                                value="{{ $view->jam }}">
                        </div>s/d
                        <div class="col-md-5">
                            <input disabled class="form-control form-control-sm" type="time" name="sdjam"
                                value="{{ $view->sdjam }}">
                </td>
    </div>

</div>


</tr>
<tr>
    <td><label for="example-text-input disabled" class="form-control-label text-secondary">Materi Pelatihan</label>
    </td>
    <td>
        <textarea disabled class="form-control form-control-sm" type="text" name="train_tema"
            value="{{ $view->train_tema }}">{{ $view->train_tema }} </textarea>
    </td>

</tr>
</table>

</div>

<div class="row">
    <div class="col-lg-12">
        <table class="table">
            <thead class="bg-primary text-center text-light shadow font-weight-bolder">
                <th>No.</th>
                <th>NIK</th>
                <th>Nama Karyawan</th>
                <th>Nilai Pretest</th>
                <th>Nilai</th>
                <th>Keterangan</th>
                <th>Cek HR</th>
            </thead>
            <tbody id="tbl-barang-body">
                @forelse ($view_d as $item)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $item->no_payroll }}</td>
                        <td>{{ $item->nama_asli }}</td>
                        <td>{{ $item->nilai_pre }}</td>
                        <td>{{ $item->nilai }}</td>
                        <td>{{ $item->keterangan }}</td>
                        <td>{{ $item->approve }}</td>
                    </tr>
                @empty
                    <td class="text-center">
                        <h3>Tidak Ada Data . . . </h3>
                    </td>
                @endforelse

            </tbody>
        </table>
    </div>
</div>

<div class="row btnSave" style="display:none;">
    <div class="col-lg-12">
        <button type="submit" class="btn btn-primary">SIMPAN </button>
    </div>
</div>

</div>
<div class="row px-4">
    <div class="col-md-10">

    </div>
    <div class="col-md-2">
        <label for="example-text-input" class="form-control-label">Ferisikasi HR :</label>
        <select disabled class="form-select form-select-sm" name="approve_h" aria-label="Default select example">
            <option selected>{{ $view->approve }}</option>
        </select>
    </div>
</div>
</form>
@endsection

