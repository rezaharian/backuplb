@extends('umum.dashboard.layout.layout')

@section('content')
    <div class="container">
        <div class="section">

            <form action="{{ url('/umum/dashboard/report/reporttlak/list') }}">
                <div class="card rounded-0 border-primary p-3">
                    <div class="row">
                
                        <div class="col-md-3 form-group">
                            <label for="tgl_akhir">Mulai:</label>
                            <input class="form-control form-control-sm rounded-0" type="date" id="awal"name="awal">

                        </div>
                        <div class="col-md-3 form-group">
                            <label for="tgl_akhir">Sampai:</label>
                            <input class="form-control form-control-sm rounded-0" type="date" id="akhir"name="akhir">

                        </div>
                        <div class="col-md-2 form-group">
                            <label for="jenis">Jenis :</label>
                            <select class="form-control form-control-sm rounded-0" type="text"
                                id="jenis"name="jenis">
                                <option value="absen kembar">absen kembar</option>
                                <option value="absen tidak lengkap">absen tidak lengkap</option>
                                <option value="tidak masuk harian">tidak masuk harian</option>
                            </select>
                        </div>
                        <div class="col-md-2 form-group text-right">
                            <button class="btn btn-primary rounded-0 mt-4" type="submit">Submit</button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
@endsection
