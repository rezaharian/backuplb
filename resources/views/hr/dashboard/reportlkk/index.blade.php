@extends('hr.dashboard.layout.layout')

@section('content')
    <div class="container">
        <div class="section">

            <form action="{{ url('/hr/dashboard/reportlkk/list') }}">
                <div class="card rounded-0 border-primary p-3">
                    <div class="row">
                
                        <div class="col-md-3 form-group">
                            <label for="tanggal">Tanggal:</label>
                            <input class="form-control form-control-sm rounded-0" type="date" id="tanggal" name="tanggal" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        
                        <div class="col-md-3 form-group">
                            <label for="shift">Shift:</label>
                            <select class="form-control form-control-sm rounded-0" type="text" required
                                id="shift"name="shift">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select>
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="jenis">Jenis :</label>
                            <select class="form-control form-control-sm rounded-0" type="text" required
                                id="jenis"name="jenis">
                                <option value="rekap">rekap</option>
                                <option value="detail">detail</option>
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
