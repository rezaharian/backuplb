@extends('hr.dashboard.layout.layout')

@section('content')
    <div class="container">
        <div class="section">
            <form action="{{ url('/hr/dashboard/reportkerajinan/list') }}" method="GET">
                <div class="card rounded-0 border-primary p-3">
                    <div class="row">
                        <div class="col-md-2 form-group">
                            <label for="tgl_awal">Tanggal Awal:</label>
                            <input type="date" name="tgl_awal" class="form-control form-control-sm rounded-0">
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="tgl_akhir">Tanggal Akhir:</label>
                            <input type="date" name="tgl_akhir" class="form-control form-control-sm rounded-0">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="no_payroll">No. Payroll:</label>
                            <select class="form-control form-control-sm rounded-0 select2" id="no_payroll"
                                name="no_payroll">
                                <!-- Pilihan nomor payroll akan dimuat di sini menggunakan server-side script -->
                            </select>
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="report_type">Report Type:</label>
                            <select class="form-control form-control-sm rounded-0 selectpicker" id="report_type" name="report_type" data-style="btn-white">
                                <option value="excel"  class="text-success">Report Excel </option>
                                <option value="uraian" selected class="text-primary">Uraian Report</option>
                            </select>
                        </div>
                        <div class="col-md-2 form-group text-right">
                            <button id="" class="btn btn-primary rounded-0 mt-4" type="submit">Submit</button>
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
                url: '/hrpegawailengkap',
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
