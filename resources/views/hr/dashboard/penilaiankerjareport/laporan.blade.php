@extends('hr.dashboard.layout.layout')

@section('content')
    <div class="container">
        <div class="section">
            <form action="{{ url('/hr/dashboard/penilaiankerjareport/listLaporan') }}" target="_blank">
                <div class="card rounded-0 border-primary p-3">
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label for="tgl_awal">Periode:</label>
                            <select name="periode" id="" class="form-control form-control-sm">
                                @foreach ($periodes as $item)
                                    <option value="{{ $item }}">{{ $item }}</option>
                                @endforeach
                                </select>
                        </div>
                     
                        <div class="col-md-3 form-group">
                            <label for="report_type">Report Type:</label>
                            <select class="form-control form-control-sm rounded-0 selectpicker" id="report_type"
                                name="report_type" data-style="btn-white">
                                <option value="pdforprint" class="text-primary">PDF atau Print</option>
                                <option value="laporan_excel" selected class="text-success"> Excel </option>
                            </select>
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="report_type">Karyawan :</label>
                            <select class="form-control form-control-sm rounded-0 selectpicker" id="Karyawan"
                                name="karyawan" data-style="btn-white">
                                <option value="semua" class="">Semua</option>
                                <option value="tetap"  class=""> Tetap </option>
                                <option value="kontrak"  class=""> Kontrak </option>
                            </select>
                        </div>
                        <div class="col-md-2 form-group text-right">
                            <button class="btn btn-primary rounded-0 mt-4"  type="submit">Submit</button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
@endsection
