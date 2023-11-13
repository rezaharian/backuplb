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
                    <div class="row mt-2 mx-1">
                        <div class="col-md-2 ">
                            <h3 class="font-weight-bolder  rounded">
                                            Line: {{ $arr['jalur_id'] }}</td>
                            </h3>
                        </div>
                        <div class="col-md-6">
                            <div class="row rounded border pt-1 ms-1 shadow  text-center ">
                                <div class="col-md-5 mt-2 ">
                                    <form class="form-group"
                                        action="{{ route('superadmin.index.cari.data', ['jalur_id' => $arr['jalur_id']]) }}"
                                        method="GET">
                                        <input type="date" class="form-control font-weight-bold text-secondary"
                                            name="cari" placeholder="Tanggal " value="{{ old('cari') }}">
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
                        <div class="col-md-3">
                            <h6 class="font-weight-bolder text-light">
                                <table>

                                    <tr>
                                        <td> Halaman </td>
                                        <td> : {{ $caridata->currentPage() }} </td>
                                    </tr>
                                    <tr>
                                        <td>Jumlah Data</td>
                                        <td> : {{ $caridata->total() }}</td>
                                    </tr>
                                    <tr>
                                        <td> Data Per Halaman</td>
                                        <td>: {{ $caridata->perPage() }} </td>
                                    </tr>
                            </h6>
                            </table>
                        </div>
                        <div class="col-md-1 ">
                            <a href="{{ route('superadmin.index.data', ['jalur_id' => $arr['jalur_id']]) }}">
                                <i class="ni ni-bold-right text-light mt-3 shadow border rounded fs-3"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 ">
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
                                    @foreach ($caridata as $data)
                                        <tr class="bg-light">
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
                            {{ $caridata->links() }}

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
