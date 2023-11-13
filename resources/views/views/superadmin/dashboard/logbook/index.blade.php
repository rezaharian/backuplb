@extends('superadmin.dashboard.layout.layout')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card ms-4 ">
                @if (session()->has('message'))
                    <div class="alert alert-success">
                        <h5 class="font-weight-bolder">
                            {{ session('message') }} Shift {{ $dms->shift }}
                        </h5>
                    </div>
                @endif
                <div class=" sticky-top bg-gradient-info ">

                    <div class="font-weight-bolder text-center text-light fs-5 m-2 ">
                        {{-- <b class="  rounded p-1">
                       Line : {{ $arr['jalur_id'] }}
                    </b> --}}
                    </div>
                    <div class="row  mx-1 ">
                        <div class="col-md-1 ">
                            <form class="mt-3 "
                                action="{{ route('superadmin.delete.data', ['jalur_id' => $arr['jalur_id']]) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn shadow btn-outline-light btn text-light "
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                    <i class="fas fa-trash "></i></button>
                            </form>



                        </div>
                        {{-- <div class="col-md-2 "> --}}




                        {{-- atur shift --}}
                        {{-- <span class="font-weight-bolder">Ganti Shift :</span>
                                    <form class="input-group"
                                    action="{{ url('store-input-fields-master') }}"
                                    method="POST">
                                    @csrf
                                    <select class="form-select border-primary rounded"
                                        name="shift">
                                        <option value="{{ $dms->shift }}"> Pilih Shift . .
                                            . . . . </option>
                                        <option value="1">I</option>
                                        <option value="2">II</option selected>
                                        <option value="3">III</option>
                                    </select>
                                    <button class="btn bg-gradient-light btn-sm  border m-1 border-6 border-primary" type="submit">OKE</button>
                                </form> --}}


                        {{-- end atur shift --}}

                        {{-- <div class="modal fade" id="modal-form" tabindex="-1" role="dialog"
                                aria-labelledby="modal-form" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-body p-0">
                                            <div class="card card-plain">
                                                <div class="card-header pb-0 text-left">
                                                    <h4 class="font-weight-bolder text-info text-gradient">Tambah LogBook</h4>
                                                </div>
                                                <div class="card-body">


                                                    <div class="container-fluid py-4">

                                                        <nav
                                                            class="navbar navbar-expand-lg navbar-light bg-gradient-light z-index-3 py-3  text-center">
                                                            <div class="container">
                                                               
                                                                   <h3 class="font-weight-bolder"> Line = {{ $arr['jalur_id'] }} </h3>
                                                                   

                                                        </nav>

                                                            <div class="container">
                                                                <form
                                                                    action="{{ url('/superadmin/dashboard/loogbok/insertfielddata', ['jalur_id' => $arr['jalur_id']]) }}"
                                                                    method="POST">
                                                                    @csrf
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
                                                                    <div class="card-body px-0 pt-0 pb-2">
                                                                        <button type="button" name="add"
                                                                            onclick="kliksekali()" id="dynamic-ar"
                                                                            class="btn btn-outline-primary">create</button>
                                                                        <button type="Button"
                                                                            class="btn btn-outline-warning"
                                                                            onclick="window.location.reload()">Batal</button>
                                                                        <div class="table-responsive p-0 text-center">

                                                                            <table
                                                                                class="table table-bordered align-items-center mb-0 "
                                                                                id="dynamicAddRemove">

                                                                                <thead>
                                                                                    <tr>
                                                                                        <th
                                                                                            class="text-uppercase text-secondary text-xxs font-weight-bolder ">
                                                                                            tanggal</th>
                                                                                        <th
                                                                                            class="text-uppercase text-secondary text-xxs font-weight-bolder ">
                                                                                            shift</th>
                                                                                        <th
                                                                                            class="text-uppercase text-secondary text-xxs font-weight-bolder ">
                                                                                            jam</th>
                                                                                        <th
                                                                                            class="text-uppercase text-secondary text-xxs font-weight-bolder ">
                                                                                            Reset</th>
                                                                                        <th
                                                                                            class="text-uppercase text-secondary text-xxs font-weight-bolder ">
                                                                                            awal</th>
                                                                                        <th
                                                                                            class="text-uppercase text-secondary text-xxs font-weight-bolder ">
                                                                                            akhir</th>
                                                                                        <th
                                                                                            class="text-uppercase text-secondary text-xxs font-weight-bolder ">
                                                                                            klk PCS</th>
                                                                                        <th
                                                                                            class="text-uppercase text-secondary text-xxs font-weight-bolder ">
                                                                                            klk Shift</th>
                                                                                        <th
                                                                                            class="text-uppercase text-secondary text-xxs font-weight-bolder ">
                                                                                            klk Hari</th>
                                                                                        <th
                                                                                            class="text-uppercase text-secondary text-xxs font-weight-bolder ">
                                                                                            Awal </th>
                                                                                        <th
                                                                                            class="text-uppercase text-secondary text-xxs font-weight-bolder ">
                                                                                            Akhir</th>
                                                                                        <th
                                                                                            class="text-uppercase text-secondary text-xxs font-weight-bolder ">
                                                                                            Rntme</th>
                                                                                        <th
                                                                                            class="text-uppercase text-secondary text-xxs font-weight-bolder ">
                                                                                            Rntme Shift</th>
                                                                                        <th
                                                                                            class="text-uppercase text-secondary text-xxs font-weight-bolder ">
                                                                                            Rntme Hari</th>
                                                                                        <th
                                                                                            class="text-uppercase text-secondary text-xxs font-weight-bolder ">
                                                                                            Reset</th>
                                                                                        <th
                                                                                            class="text-uppercase text-secondary text-xxs font-weight-bolder ">
                                                                                            SPK</th>
                                                                                        <th
                                                                                            class="text-uppercase text-secondary text-xxs font-weight-bolder ">
                                                                                            prdk</th>
                                                                                        <th
                                                                                            class="text-uppercase text-secondary text-xxs font-weight-bolder ">
                                                                                            Ket</th>
                                                                                        <th
                                                                                            class="text-uppercase text-secondary text-xxs font-weight-bolder ">
                                                                                            opr</th>
                                                                                        <th
                                                                                            class="text-uppercase text-secondary text-xxs font-weight-bolder ">
                                                                                            Opt</th>
                                                                                    </tr>
                                                                                </thead>

                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                    <button type="submit"
                                                                        class="btn btn-outline-success btn-block">Save</button>
                                                                </form>
                                                            </div>


                                                        <!-- JavaScript -->
                                                        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
                                                        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>

                                                        <script type="text/javascript">
                                                            @if ($dms->shift == 1)
                                                                $abc = ["08:00", "09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00"];
                                                            @elseif ($dms->shift == 2)
                                                                $abc = ["16:00", "17:00", "18:00", "19:00", "20:00", "21:00", "22:00", "23:00"];
                                                            @else
                                                                $abc = ["24:00", "01:00", "02:00", "03:00", "04:00", "05:00", "06:00", "07:00"];
                                                            @endif



                                                            const jams = $abc;

                                                            for (let i = 0; i < jams.length; i++) {
                                                                $("#dynamic-ar").click(function() {
                                                                    $("#dynamicAddRemove").append('<tr><td><input type="text" name="addMoreInputFields[' +
                                                                        i +
                                                                        '][tanggal]" placeholder=".." value="{{ $tgl }}"   class="form-control" /></td><td><input type="text" name="addMoreInputFields[' +
                                                                        i +
                                                                        '][shift]" placeholder=".." value="{{ $dms->shift }}"  class="form-control" /></td><td><input type="text" name="addMoreInputFields[' +
                                                                        i + '][etc]"    value="' + jams[i] +
                                                                        '" class="form-control" /> </td><td><input type="text" name="addMoreInputFields[' +
                                                                        i +
                                                                        '][reset]" value="No" class="form-control" /></td><td><input type="text" name="addMoreInputFields[' +
                                                                        i +
                                                                        '][awal]" id="awal' + i + '" onkeyup="dt(' + i +
                                                                        ');" class="form-control" /></td> <td><input type="text" name="addMoreInputFields[' +
                                                                        i + '][akhir]" value="0" id="akhir' + i + '" onkeyup="dt(' + i +
                                                                        ');" class="form-control"  /> </td><td><input type="text" name="addMoreInputFields[' +
                                                                        i + '][kalkulasi]" id="kalkulasi' + i + '" onkeyup="dt(' + i +
                                                                        ');" class="form-control" /> </td><td><input type="text" name="addMoreInputFields[' +
                                                                        i + '][jumlahpershift]" id="jumlahpershift' + i + '" onkeyup="dt(' + i +
                                                                        ');" class="form-control" /> </td><td><input type="text" name="addMoreInputFields[' +
                                                                        i + '][jumlahperhari]" id="jumlahperhari' + i + '" onkeyup="dt(' + i +
                                                                        ');" class="form-control" /> </td><td><input type="text" name="addMoreInputFields[' +
                                                                        i + '][awaljam]" id="awaljam' + i + '"  onkeyup="dt(' + i +
                                                                        ');" class="form-control" /> <input type="text" name="addMoreInputFields[' +
                                                                        i + '][awalmenit]" id="awalmenit' + i + '"  onkeyup="dt(' + i +
                                                                        ');" class="form-control" /> </td><td><input type="text" name="addMoreInputFields[' +
                                                                        i + '][akhirjam]" id="akhirjam' + i + '"  onkeyup="dt(' + i +
                                                                        ');" class="form-control" /><input type="text" name="addMoreInputFields[' +
                                                                        i + '][akhirmenit]"  id="akhirmenit' + i + '"  onkeyup="dt(' + i +
                                                                        ');" class="form-control" /> </td><td><input type="text" name="addMoreInputFields[' +
                                                                        i + '][runtimemenit]" id="runtimemenit' + i + '" onkeyup="dt(' + i +
                                                                        ');" class="form-control" /> </td><td><input type="text" name="addMoreInputFields[' +
                                                                        i + '][runtimeshift]" id="runtimeshift' + i + '" onkeyup="dt(' + i +
                                                                        ');" class="form-control" /> </td><td><input type="text" name="addMoreInputFields[' +
                                                                        i + '][runtimehari]" id="runtimehari' + i + '" onkeyup="dt(' + i +
                                                                        ');" class="form-control" /> </td><td><input type="text" name="addMoreInputFields[' +
                                                                        i +
                                                                        '][resetw]" value="No" class="form-control" /> </td><td><input type="text" name="addMoreInputFields[' +
                                                                        i + '][spk]" id="spk' + i + '" onkeyup="dt(' + i +
                                                                        ');" class="form-control" /> </td><td><input type="text" name="addMoreInputFields[' +
                                                                        i + '][produk]" id="produk' + i + '" onkeyup="dt(' + i +
                                                                        ');" class="form-control" /> </td><td><input type="text" name="addMoreInputFields[' +
                                                                        i + '][keterangan]" id="keterangan' + i + '" onkeyup="dt(' + i +
                                                                        ');" class="form-control" /> </td><td><input type="text" name="addMoreInputFields[' +
                                                                        i + '][operator]" id="operator' + i + '" onkeyup="dt(' + i +
                                                                        ');" class="form-control" /> </td><td><input type="text" name="addMoreInputFields[' +
                                                                        i + '][jalur_id]" value="{{ $arr['jalur_id'] }} " id="operator' + i + '" onkeyup="dt(' +
                                                                        i + ');" class="form-control" /> </td></tr>'

                                                                    );

                                                                });
                                                                // $(document).on('click', '.remove-input-field', function () {
                                                                //     $(this).parents('tr').remove();
                                                                // });
                                                            }
                                                        </script>
                                                        <script>
                                                            function kliksekali() {
                                                                var X = document.getElementById('dynamic-ar');
                                                                X.disabled = true;
                                                            }


                                                            function dt(i) {

                                                                var klkshift = 0;
                                                                var klkhari = 0;

                                                                // var awal =  {{ $nilaiakhir->akhir }};
                                                                // var aw_jam = {{ $nilaiakhir->akhirjam }} ;
                                                                // var aw_menit = {{ $nilaiakhir->akhirmenit }} ;
                                                                var awal = 0;
                                                                var aw_jam = 0;
                                                                var aw_menit = 0;

                                                                if (i > 0) {
                                                                    awal = document.getElementById('akhir' + (i - 1)).value;
                                                                    klkshift = document.getElementById('jumlahpershift' + (i - 1)).value;
                                                                    klkhari = document.getElementById('jumlahperhari' + (i - 1)).value;
                                                                    aw_jam = document.getElementById('akhirjam' + (i - 1)).value;
                                                                    aw_menit = document.getElementById('akhirmenit' + (i - 1)).value;
                                                                }
                                                                var akhir = document.getElementById('akhir' + i).value;
                                                                var result = parseFloat(awal);
                                                                var kalkulasi = parseFloat(akhir) - parseFloat(result);
                                                                var jumlahpershift = parseFloat(klkshift) + kalkulasi;
                                                                var jumlahperhari = parseFloat(klkhari) + jumlahpershift;
                                                                var awal_jam = parseFloat(aw_jam);
                                                                var awal_menit = parseFloat(aw_menit);


                                                                if (!isNaN(result)) {
                                                                    document.getElementById('awal' + i).value = result;
                                                                    document.getElementById('kalkulasi' + i).value = kalkulasi;
                                                                    document.getElementById('jumlahpershift' + i).value = jumlahpershift;
                                                                    document.getElementById('jumlahperhari' + i).value = jumlahperhari;
                                                                    document.getElementById('awaljam' + i).value = awal_jam;
                                                                    document.getElementById('awalmenit' + i).value = awal_menit;
                                                                }
                                                            }
                                                        </script>


                                                   
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                        {{-- </div> --}}
                        <div class="col-md-6 text-center">
                            {{-- <span class="font-weight-bolder" >Tambah Log :</span><br>
                            <button type="button" class="btn bg-gradient-light btn-sm  border" data-bs-toggle="modal"
                            data-bs-target="#modal-form">Tambah</button>    --}}


                            <div class="col-md-12   ">
                                <form class="form-group success "
                                    action="{{ url('/superadmin/dashboard/loogbok/insertfielddata', ['jalur_id' => $arr['jalur_id']]) }}"
                                    method="POST">
                                    @csrf
                                    <div class="row rounded border pt-2 shadow">
                                        <div class="col-md-6">

                                            <input
                                                class="form-control font-weight-bold text-secondary border-light rounded m-1"
                                                type="date" name="tanggal">
                                        </div>
                                        <div class="col-md-4">
                                            <select
                                                class="form-select mt-1 font-weight-bold text-secondary border-light rounded "
                                                name="shift">
                                                <option value="1"> Shift I</option>
                                                <option value="2"> Shift II</option>
                                                <option value="3"> Shift III</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2 mt-1">
                                            <button type="submit"
                                                class="btn btn-primary btn-outline-light btn-md">Buat</button>
                                        </div>
                                    </div>

                            </div>



                            </form>
                        </div>
                        <div class="col-md-5   ">
                            <div class="row rounded border pt-1 ms-1 shadow  text-center ">
                                <div class="col-md-5 mt-2 ">
                                    <form class="form-group"
                                        action="{{ route('superadmin.index.cari.data', ['jalur_id' => $arr['jalur_id']]) }}"
                                        method="GET">
                                        <input type="date" class="form-control font-weight-bold text-secondary" name="cari" placeholder="Tanggal "
                                        value="{{ old('cari') }}">
                                </div>
                                <div class="col-md-4 mt-2">
                                    <select class="form-select  font-weight-bold text-secondary border-light rounded "
                                        name="carit" placeholder="shift " value="{{ old('carit') }}">
                                        <option value="1"> Shift I</option>
                                        <option value="2"> Shift II</option>
                                        <option value="3"> Shift III</option>
                                        <option value=""> Semua</option>
                                    </select>
                                </div>
                                <div class="col-md-1 mt-2 ">
                                    <input type="submit" class="btn btn-primary border text-light" value="CARI"> 
                                </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0 text-center">
                        <table class="table align-items-center mb-0 ">
                            <thead>
                                <tr class=" ">
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder ">ID</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder ">tanggal</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder ">Jam</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder ">shift</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder ">Reset</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder ">Reset Opt
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder ">awal</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder ">akhir</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder ">klk PCS</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder ">klk Shift
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder ">klk Hari</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder ">Reset Opt
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder ">Reset</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder ">Awal </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder ">Akhir</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder ">Rntme</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder ">Rntme Shift
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder ">Rntme Hari
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder ">SPK</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder ">prdk</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder ">Ket</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder ">opr</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder ">Opt</th>
                                </tr>
                            </thead>
                            <tbody class="table-responsive">
                                @foreach ($datas as $data)
                                    <tr>
                                        <td class="text-xs font-weight-bold mb-0">{{ $data->id }}</td>
                                        <td class="text-xs font-weight-bold mb-0">{{ $data->tanggal }}</td>
                                        <td class="text-xs font-weight-bold mb-0">{{ $data->etc }}</td>
                                        <td class="text-xs font-weight-bold mb-0">{{ $data->shift }}</td>
                                        @if ($data->reset == 'YES')
                                            <td class="text-xs font-weight-bold mb-0 bg-danger">{{ $data->reset }}
                                            </td>
                                        @else
                                            <td class="text-xs font-weight-bold mb-0 bg-light">{{ $data->reset }}</td>
                                        @endif




                                        <td class="text-xs font-weight-bold mb-0">
                                            <a class="btn bg-gradient-danger btn-sm"
                                                href="{{ route('superadmin.reset.data', ['jalur_id' => $data->jalur_id, 'data' => $data->id]) }}">R</a>
                                            <a
                                                class="btn bg-gradient-success btn-sm "href="{{ route('superadmin.batalresetpcs.data', ['jalur_id' => $data->jalur_id, 'data' => $data->id]) }}">B</a>

                                        </td>
                                        <td class="text-xs font-weight-bold mb-0">{{ $data->awal }}</td>
                                        <td class="text-xs font-weight-bold mb-0 bg-gradient-light fs-6 "
                                            data-pk="{{ $data->id }}">
                                            <a href="" class="update" data-name="akhir" data-type="text"
                                                data-pk="{{ $data->id }}"
                                                data-title="Enter name">{{ $data->akhir }}</a>
                                        </td>
                                        <td class="text-xs font-weight-bold mb-0">{{ $data->kalkulasi }}</td>
                                        <td class="text-xs font-weight-bold mb-0">{{ $data->jumlahpershift }}</td>
                                        <td class="text-xs font-weight-bold mb-0">{{ $data->jumlahperhari }}</td>
                                        <td class="text-xs font-weight-bold mb-0">
                                            <a
                                                class="btn bg-gradient-danger btn-sm "href="{{ route('superadmin.resetwaktu.data', ['jalur_id' => $data->jalur_id, 'data' => $data->id]) }}">R</a>
                                            <a
                                                class="btn bg-gradient-success btn-sm "href="{{ route('superadmin.batalresettime.data', ['jalur_id' => $data->jalur_id, 'data' => $data->id]) }}">B</a>

                                        </td>

                                        @if ($data->resetw == 'YES')
                                            <td class="text-xs font-weight-bold mb-0 bg-danger">{{ $data->resetw }}
                                            </td>
                                        @else
                                            <td class="text-xs font-weight-bold mb-0 bg-light">{{ $data->resetw }}
                                            </td>
                                        @endif


                                        <td class="text-xs font-weight-bold mb-0">{{ $data->awaljam }} :
                                            {{ $data->awalmenit }}</td>
                                        <td class="text-xs font-weight-bold mb-0 bg-gradient-light fs-6">
                                            <a href="" class="update" data-name="akhirjam" data-type="text"
                                                data-pk="{{ $data->id }}"
                                                data-title="Enter name">{{ $data->akhirjam }}</a>:
                                            <a href="" class="update" data-name="akhirmenit" data-type="text"
                                                data-pk="{{ $data->id }}"
                                                data-title="Enter name">{{ $data->akhirmenit }}</a>
                                        </td>
                                        <td class="text-xs font-weight-bold mb-0">{{ $data->runtimemenit }}</td>
                                        <td class="text-xs font-weight-bold mb-0">{{ $data->runtimeshift }}</td>
                                        <td class="text-xs font-weight-bold mb-0">{{ $data->runtimehari }}</td>
                                        <td class="text-xs font-weight-bold mb-0">{{ $data->spk }}</td>
                                        <td class="text-xs font-weight-bold mb-0">{{ $data->produk }}</td>
                                        <td class="text-xs font-weight-bold mb-0">{{ $data->keterangan }}</td>
                                        <td class="text-xs font-weight-bold mb-0">{{ $data->operator }}</td>
                                        <td class="text-xs font-weight-bold mb-0" style="width: 5%">
                                            {{-- <a class="btn btn-primary btn-sm"
                                            href="{{ route('data.edit', ['jalur_id' => $data->jalur_id, 'data' => $data->id]) }}">Edit</a> --}}

                                        </td>

                                    </tr>
                                @endforeach



                            </tbody>
                        </table>
                        <div class="my-4 ">
                            {{  $datas->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach ($prev as $item)
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
        <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/jquery-editable/js/jquery-editable-poshytip.min.js">
        </script>


        <script>
            function kali() {
                var awal = document.getElementById('awal').value;
                var akhir = document.getElementById('akhir').value;
                var kalkulasi = document.getElementById('kalkulasi').value;
                var jumlahpershift = document.getElementById('jumlahpershift').value;
                var jumlahperhari = document.getElementById('jumlahperhari').value;
                var awaljam = document.getElementById('awaljam').value;
                var awalmenit = document.getElementById('awalmenit').value;
                var akhirjam = document.getElementById('akhirjam').value;
                var akhirmenit = document.getElementById('akhirmenit').value;
                var runtimemenit = document.getElementById('runtimemenit').value;
                var runtimeshift = document.getElementById('runtimeshift').value;
                var runtimehari = document.getElementById('runtimehari').value;




                var result = parseFloat(akhir) - parseFloat(awal);
                var runtime = ((parseFloat(akhirjam) * 60) + parseFloat(akhirmenit)) - ((parseFloat(awaljam) * 60) + parseFloat(
                    awalmenit));
                var klkshift = ({{ $item->jumlahpershift }} + parseFloat(kalkulasi));
                var klkhr = ({{ $item->jumlahperhari }} + parseFloat(jumlahpershift));
                var rntmshift = ({{ $item->runtimeshift }} + parseFloat(runtimemenit));
                var rntmehri = ({{ $item->runtimehari }} + parseFloat(runtimeshift));


                {{ $item->jumlahpershift }}

                if (!isNaN(result)) {
                    document.getElementById('kalkulasi').value = result;
                    document.getElementById('jumlahpershift').value = klkshift;
                    document.getElementById('runtimemenit').value = runtime;
                    document.getElementById('jumlahperhari').value = klkhr;
                    document.getElementById('runtimeshift').value = rntmshift;
                    document.getElementById('runtimehari').value = rntmehri;




                }
            }
        </script>

        <script type="text/javascript">
            $.fn.editable.defaults.mode = 'inline';

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            $('.update').editable({
                url: "{{ route('superadmin.update.data', ['jalur_id' => $data->jalur_id, 'data' => $data->id]) }}",
                type: 'text',
                pk: 1,
                name: 'name',
                title: 'Enter name',
                success:function(){ 
                document.location.reload(); 
                }
            });
        </script>
    @endforeach
   
@endsection
