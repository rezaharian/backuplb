@extends('hr.dashboard.layout.layout')

@section('content')
    <div class="container">
        <div class="section">
            <form action="{{ url('/hr/dashboard/reportcuti/list') }}" method="GET">
                <div class="card rounded-0 border-primary p-3">
                    <div class="row">
                        <div class="col-md-2 form-group">
                            <label for="bln_awal">Bulan Awal:</label>
                            <select class="form-control form-control-sm rounded-0" id="bln_awal" name="bln_awal">
                               <option value="1">JANUARI</option>
                            </select>
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="bln_akhir">Bulan Akhir:</label>
                            <select class="form-control form-control-sm rounded-0" id="bln_akhir" name="bln_akhir">
                                @foreach($bulan as $key => $namaBulan)
                                    <option value="{{ $key }}">{{ $namaBulan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="thn">Tahun:</label>
                            <select class="form-control form-control-sm rounded-0" id="thn" name="thn">
                                @for($i = $tahunAwal; $i <= $tahunAkhir; $i++)
                                    <option value="{{ $i }}" {{ $i == $tahunSekarang ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="no_payroll">No. Payroll:</label>
                            <input class="form-control form-control-sm rounded-0" type="text" id="no_payroll" name="no_payroll">
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="report_type">Report Type:</label>
                            <select class="form-control form-control-sm rounded-0 selectpicker" id="report_type" name="report_type" data-style="btn-white">
                                <option value="cuti_excel"  class="text-success">Cuti Excel </option>
                                <option value="uraian_cuti" selected class="text-primary">Uraian cuti</option>
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


    <div id="loading-popup" class="modal fade" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <p>"Please wait, the process takes around -+5 minutes." </p>
                    <img src="https://dbtdacfw.gov.in/Images/progress.gif" alt="">
                    
                </div>
            </div>
        </div>
    </div>

    <!-- Script JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Tangkap form submit
        $('form').on('submit', function () {
            // Tampilkan popup "Mohon Tunggu"
            $('#loading-popup').modal('show');
        });
    </script>
@endsection
