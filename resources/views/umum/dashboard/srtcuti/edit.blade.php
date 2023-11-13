@extends('umum.dashboard.layout.layout')

@section('content')

<section>
    <form action="{{ url('/umum/dashboard/srtcuti/update/'.$cuti->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card rounded-0 border-primary p-3">
            <div class="row">
                <small class="fw-bold">No Cuti : <input type="text" name="ct_nom" class="border-0" value="{{ $cuti->ct_nom }}" readonly> </small>
                <hr>
                <div class="col-md-3 form-group">
                    <label for="ct_tgl">Dibuat Tgl :</label>
                    <input class="form-control form-control-sm rounded-0" type="date" id="ct_tgl" name="ct_tgl"
                        value="{{ $cuti->ct_tgl }}" >
                </div>
                <div class="col-md-5 form-group">
                    <label for="ct_reg">Pegawai :</label>
                    <select class="form-control form-control-sm rounded-0 select2" type="date" id="select2"
                        name="ct_reg" required>
                    <option value="{{ $cuti->ct_reg }}">{{ $cuti->ct_reg }} - {{ $cuti->ct_nam }}</option>
                    </select>
                </div>
                <div class="col-md-2 form-group">
                    <label for="ct_jml">Lama Cuti :</label>
                    <input class="form-control form-control-sm rounded-0 " type="number" id="" name="ct_jml" value="{{ $cuti->ct_jml }}" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 form-group">
                    <div class="row">
                        <div class="col">
                            <label for="ct_dr1">Mulai:</label>
                        </div>
                        <div class="col">
                            <label for="ct_sd1">Sampai:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <input class="form-control form-control-sm rounded-0 " type="date" id="ct_dr1"
                                name="ct_dr1" value="{{ $cuti->ct_dr1 }}" required>
                        </div>
                        <div class="col-md-6">
                            <input class="form-control form-control-sm rounded-0 " type="date" id="ct_sd1"
                                name="ct_sd1" value="{{ $cuti->ct_sd1 }}" required>
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-md-6">
                            <input class="form-control form-control-sm rounded-0 " type="date" id="ct_dr2"
                                name="ct_dr2" value="{{ $cuti->ct_dr2 }}">
                        </div>
                        <div class="col-md-6">
                            <input class="form-control form-control-sm rounded-0 " type="date" id="ct_sd2"
                                name="ct_sd2" value="{{ $cuti->ct_sd2 }}">
                        </div>
                    </div>
                </div>
                <div class="col-md-7 form-group">
                    <label for="ct_not">Alasan Cuti :</label>
                    <textarea class="form-control form-control-sm rounded-0 " id="ct_not" name="ct_not" required>{{ $cuti->ct_not }}</textarea>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-3 form-group">
                    <label for="setuju">Menyetujui Manajer Dept. :</label>
                    <input class="form-control form-control-sm rounded-0 " type="text" id="" name="setuju" value="{{ $cuti->setuju }}" > 
                </div>
                <div class="col-md-3 form-group">
                    <label for="ket_atas">Mengetahui Atasan Ybst. :</label>
                    <input class="form-control form-control-sm rounded-0 " type="text" id="" name="ket_atas" value="{{ $cuti->ket_atas }}" > 
                </div>
                <div class="col-md-3 form-group">
                    <label for="ket_pers">Mengetahui Dept. Pers./umum :</label>
                    <input class="form-control form-control-sm rounded-0 " type="text" id="" name="ket_pers" value="{{ $cuti->ket_pers }}" > 
                </div>
                <div class="col-md-1 form-group text-right">
                    <button class="btn btn-sm btn-primary rounded-0 mt-4" type="submit">Submit</button>
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
                                            text: item.no_payroll + ' - ' + item
                                                .nama_asli // Gabungkan no_payroll dan nama_asli
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