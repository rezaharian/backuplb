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
            <div class="alert alert-info text-center">
                <p>{{ Session::get('success') }}</p>
            </div>
        @endif

        <div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-2">
                            <a href="/umum/dashboard/absen/create/{{ $bln }}/{{ $thn }}">
                                <button class="btn btn-md btn-primary m-0 mb-2 rounded-0"><i class="fas fa-add"></i></button>
                            </a>
                        </div>
                        <div class="col-md-6 mt-1">
                            <form class="" action="/umum/dashboard/absen/list">
                                <select class="nama_asli form-control form-control-sm font-weight-bolder rounded-0" name="cari" type="text"
                                    id="no_payroll">
                                </select>
                        </div>
                        <input hidden type="text" value="{{ $thn }}" name="tahun">
                        <input hidden type="text" value="{{ $bln }}" name="bulan">
                        <div class="col-md-2">

                            <button type="submit"
                                class="btn btn-primary p-0  m-0 form-control form-control-sm fw-bold rounded-0">Cari ..</i></button>
                        </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-6">
                    <form class="" action="/umum/dashboard/absen/list">
                        <div class="row">
                            <div class="col-md-5">
                                <select class=" form-control form-control-sm font-weight-bolder rounded-0" name="tahun"
                                    type="text" id="tahun">
                                    <option selected value="{{ $thn }}">{{ $thn }}</option>
                                    @foreach ($tahun as $item)
                                        <option value="{{ $item }}">{{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-5">
                                <select class=" form-control form-control-sm font-weight-bolder rounded-0" name="bulan"
                                    type="text" id="bulan">
                                    <option selected value="{{ $bln }}">{{ $bln }}</option>
                                    @foreach ($bulan as $item)
                                        <option value="{{ $item }}">{{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <button type="submit"
                                        class="btn btn-primary rounded-0 btn-sm form-control">submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card border-primary rounded-0">
            <div class="row ">
                <div class="col-md-12 ">
                    <div style="height:450px;overflow:auto;">
                        <table class="table  align-items-center mb-0 table-bordered border-secondary border-light ">
                            <thead
                                class=" bg-primary text-center text-uppercase  text-light shadow font-weight-bolder sticky-top  ">

                                <tr class="text-xs opacity-10">
                                    <td>No Payroll</td>
                                    <td>Nama Karyawan</td>
                                    <td>Bagian</td>
                                    <td>Action</td>
                                </tr>
                            </thead>
                            <tbody class="text-secondary">
                                @foreach ($data as $item)
                                    <tr>
                                        <td>{{ $item->no_payroll }}</td>
                                        <td>{{ $item->nama_asli }}</td>
                                        <td>{{ $item->bagian }}</td>
                                        <td class="item-center text-center">
                                            <a
                                                href="{{ route('umum.absen.detail', ['bln' => $bln, 'thn' => $thn, 'id' => $item->no_payroll]) }}"><i
                                                    class="fas fa-eye"></i> </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
        <link rel="stylesheet" type="text/css"
            href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>


        <script type="text/javascript">
            $('.nama_asli').select2({
                placeholder: 'SEMUA',
                ajax: {
                    url: '/autocompleted_umum',
                    dataType: 'json',
                    delay: 5,
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: [item.nama_asli, item.no_payroll],
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
