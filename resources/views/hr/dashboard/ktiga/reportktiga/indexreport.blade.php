@extends('hr.dashboard.layout.layout')

@section('content')


<style>
    @keyframes slideInFromBottom {
      0% {
        opacity: 0;
        transform: translateY(100%);
      }
      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    .card {
      animation: slideInFromBottom 0.5s ease-out;
    }
    </style>
    
    
    <div class="row">
        <div class="col-md-12">
            <div class="card card-plain p-2 border-primary rounded-0">
                <form action="/hr/dashboard/ktiga/reportktigaprint" target="_blank">
                    <div class="row mt-1">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="form-control">Jenis Masalah</label>
                                <select class="form-control form-control-sm"name="jenis_masalah" id="">
                                    <option value="">SEMUA</option>
                                    <option value="K3">K3</option>
                                    <option value="Umum">UMUM</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="form-control">Periode Dari</label>
                                <input type="date" class="form-control form-control-sm" name="periode_awal"
                                    id="exampleFormControlInput1" placeholder="dari tanggal" required>
                            </div>
                        </div>
             
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="form-control">Sampai</label>
                                <input type="date" class="form-control form-control-sm" name="periode_akhir"
                                    id="exampleFormControlInput1" placeholder="sampai tanggal" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="form-control">Export</label>
                                <select class="form-control form-control-sm"name="export" id="" required>
                                    <option class="pdf text-danger" value="PDF">PDF</option>
                                    <option class="excel text-success" value="Excel">Excel</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="form-control"></label>
                                <button type="submit" class="btn btn-primary  btn-sm form-control">submit</button>
                            </div>
                        </div>
                    </div>
                </form>

                
            </div>
 
        </div>
    </div>



@endsection
