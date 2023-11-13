@extends('umum.dashboard.layout.layout')

@section('content')
    <div class="container">
        <div class="section">
            <form action="{{ url('/umum/dashboard/report/reportabsen/list') }}">
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
                            <select class="form-control form-control-sm rounded-0 select2" type="text" id="no_payroll" name="no_payroll">

                            </select>
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="report_type">Report Type:</label>
                            <select class="form-control form-control-sm rounded-0 selectpicker" id="report_type" name="report_type" data-style="btn-white">
                                <option value="uraian_absen" selected class="text-primary">Uraian Absen</option>
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

    <script>
        // Inisialisasi Select2 pada elemen dengan class "select2"
        $(document).ready(function() {
            // Inisialisasi Select2 pada elemen dengan class "select2"
            $('.select2').select2({
                ajax: {
                    url: '/pegawailengkap',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    id: item.no_payroll,
                                    text: item.no_payroll + ' - ' + item.nama_asli // Gabungkan no_payroll dan nama_asli
                                };
                            })
                        };
                    },
                    cache: true
                },
                minimumInputLength: 1 // Jumlah karakter minimum sebelum pencarian dimulai
            });
        });
    </script>
@endsection
