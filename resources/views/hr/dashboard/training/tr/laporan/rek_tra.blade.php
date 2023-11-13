{{-- @extends('hr.dashboard.layout.layout')

@section('content')
    <div class="container  px-3">
        <div class="row">
            <div class="col-md-2">
            </div>
            <div class="col-md-8">
                <div class="card p-2">
                    <form class="mx-6 mt-4" action="/hr/dashboard/training/rek_tra/">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <select name="jns_tr" id="">
                                        <option value="TRAINING">TRAINING</option>
                                        <option value="SOSIALISASI">SOSIALISASI</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="date" class="form-control form-control-sm" name="train_dat_awal"
                                        id="exampleFormControlInput1" placeholder="dari tanggal" required>
                                </div>
                            </div>s/d
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="date" class="form-control form-control-sm" name="train_dat_akhir"
                                        id="exampleFormControlInput1" placeholder="sampai tanggal" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <button type="submit"
                                        class="btn btn-primary btn-rounded btn-sm form-control">submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-2"></div>
            </div>
        </div>

     
            <div class="row mt-3 mb-0">

                <div class="col-md-8">

                </div>
                <div class="col-md-4 text-center ">

                </div>
            </div>
            <div class="row px-4  mt-3 text-end">



                <form action="/hr/dashboard/training/rek_tra_print" target="_blank">
                    <div class="row">
                        <div class="col-md-8">

                            <h6>
                                Data dari {{ $dtv1 }} sampai dengan {{ $dtv2 }}
                            </h6>
                        </div>
                        <div class="col-md-2"></div>
                        <div class="col-md-2  text-end">

                            <input type="date" class="form-control form-control-sm" value="{{ $aw }}"
                                name="train_dat_awal" id="exampleFormControlInput1" placeholder="dari tanggal" hidden>

                            <input type="date" class="form-control form-control-sm" value="{{ $ak }}"
                                name="train_dat_akhir" id="exampleFormControlInput1" placeholder="sampai tanggal" hidden>
                            <div class="form-group">

                                <button type="submit" class="btn  btn-md btn-primary  mb-0 "><i
                                        class="fas fa-print"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <table class="table">
                <thead class="bg-primary text-light ">
                    <tr>
                        <th scope="col">NIK</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Bagian</th>
                        <th scope="col">Jabatan</th>
                        <th scope="col">Rata2 PreT</th>
                        <th scope="col">Rata2 PostT</th>
                        <th scope="col">Total Jam</th>
                        <th scope="col">Detail</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($data as $item)
                        <tr>
                            <td>{{ $item->no_payroll }}</td>
                            <td>{{ $item->nama_asli }}</td>
                            <td>{{ $item->bagian }}</td>
                            <td>{{ $item->jabatan }}</td>
                            <td>{{ $item->totalnilaipre }}</td>
                            <td>{{ $item->totalnilai }}</td>
                            <td>{{ $item->totaljam }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
    </div>

    </div>
@endsection --}}
