@extends('hr.dashboard.layout.layout')

@section('content')
    <div class="container">
        <div class="section">
            <form action="{{ url('/hr/dashboard/reportabsen/list') }}">
                <div class="card rounded-0 border-primary p-3">
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label for="tgl_awal">Tanggal Awal:</label>
                            <input class="form-control form-control-sm rounded-0" type="date" id="tgl_awal" name="tgl_awal">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="tgl_akhir">Tanggal Akhir:</label>
                            <input class="form-control form-control-sm rounded-0" type="date" id="tgl_akhir" name="tgl_akhir">
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="no_payroll">No. Payroll:</label>
                            <input class="form-control form-control-sm rounded-0" type="text" id="no_payroll" name="no_payroll">
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="report_type">Report Type:</label>
                            <select class="form-control form-control-sm rounded-0 selectpicker" id="report_type" name="report_type" data-style="btn-white">
                                <option value="uraian_absen" selected  class="text-primary">Uraian Absen</option>
                                <option value="gaji_excel"  class="text-success">Gaji Excel </option>
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
