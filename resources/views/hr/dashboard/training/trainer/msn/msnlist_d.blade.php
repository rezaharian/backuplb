@extends('hr.dashboard.training.trainer.layout.layout')

@section('content')

    <div class="container text-xs  px-4">


        <form action="{{ url('/qc/dashboard/problemmsn/store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (Session::has('success'))
                    <div class="alert alert-success text-center">
                        <p>{{ Session::get('success') }}</p>
                    </div>
                @endif
                <div class="col-md-5">

                    <table class="table">

                        <tr>
                            <td><label for="example-text-input" class="form-control-label">NO Doc*</label></td>
                            <td><input disabled class="form-control" type="text" name="prob_cod" value="{{ $view->prob_cod }}">
                            </td>
                        </tr>
                        <tr>
                            <td><label for="example-text-input" class="form-control-label">Tanggal </label></td>
                            <td><input disabled class="form-control" type="text" name="tgl_input" value="{{ $view->tgl_input }}"
                                    required></td>
                        </tr>
                        <tr>
                            <td><label for="example-text-input" class="form-control-label">Masalah </label></td>
                            <td>
                                <textarea disabled class="form-control" type="text" name="masalah" value="{{ $view->masalah }}" required>{{ $view->masalah }}</textarea>
                            </td>

                        </tr>
                        <tr>
                            <td><label for="example-text-input" class="form-control-label">Penyebab </label></td>
                            <td>
                                <textarea disabled class="form-control" type="text" name="penyebab" value="{{ $view_d->penyebab }}" required>{{ $view_d->penyebab }}</textarea>
                            </td>
                        </tr>

                        <tr>
                            <td><label for="example-text-input" class="form-control-label">Tgl Perbaikan</label></td>
                            <td><input disabled class="form-control" type="text" name="tgl_rpr" value="{{ $view_d->tgl_rpr }}">
                            </td>
                        </tr>
                        <tr>
                            <td><label for="example-text-input" class="form-control-label">perbaikan </label></td>
                            <td>
                                <textarea disabled class="form-control" type="text" name="perbaikan" value="{{ $view_d->perbaikan }}" required>{{ $view_d->penyebab }}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="example-text-input" class="form-control-label">Tgl Pencegahan</label></td>
                            <td><input disabled class="form-control" type="text" name="tgl_pre" value="{{ $view_d->tgl_pre }}">
                            </td>
                        </tr>
                        <tr>
                            <td><label for="example-text-input" class="form-control-label">Pencegahan </label></td>
                            <td>
                                <textarea disabled class="form-control" type="text" name="pencegahan" value="{{ $view_d->pencegahan }}" required>{{ $view_d->penyebab }}</textarea>
                            </td>
                        </tr>
                    </table>
                </div>
              
          
            

                <div class="col-md-7">
                    <table class="table">
                        <a href="/trainer/msn/print_d/{{ $view_d->id }}" target="_blank">
                            <button type="button" class="btn btn-sm btn-danger" id="btn-tambah">Print </button>
                        </a>
                        <tr>
                            
                            <td><label for="example-text-input" class="form-control-label">Line </label></td>
                            <td>
                                <select disabled class="form-select" name="line" id="package"
                                    aria-label="Default select example">
                                    <option selected value="Training">{{ $view->line }}</option>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td><label for="example-text-input" class="form-control-label">Unit Mesin </label></td>
                            <td>
                                <select disabled class="form-select" name="unitmesin" aria-label="Default select example">
                                    <option selected>{{ $view->unitmesin }}</option>
                                </select>
                            </td>
                        </tr>

                </div>
            </div>

        </div>
        </table>

                <div class="modal-body">
                    <div style="height:450px;overflow:auto;">
                        <div class="row">
                            <div class="col-md-6">
                                <a href="/image/{{ $view->img_pro01 }}"  width="100%" target="new">
                                <img src="/image/{{ $view->img_pro01 }}" alt="/image/{{ $view->img_pro01 }}" width="100%" ></a>
                            </div>
                            <div class="col-md-6">
                                <a href="/image/{{ $view->img_pro02 }}"  width="100%" target="new">
                                <img src="/image/{{ $view->img_pro02 }}" alt="/image/{{ $view->img_pro02 }}" width="100%" ></a>
                            </div>
                            <div class="col-md-6">
                                <a href="/image/{{ $view->img_pro03 }}"  width="100%" target="new">
                                <img src="/image/{{ $view->img_pro03 }}" alt="/image/{{ $view->img_pro03 }}" width="100%" ></a>
                            </div>
                            <div class="col-md-6">
                                <a href="/image/{{ $view->img_pro04 }}"  width="100%" target="new">
                                <img src="/image/{{ $view->img_pro04 }}" alt="/image/{{ $view->img_pro03 }}" width="100%" ></a>
                            </div>
                       
                        </div>
                    </div>
           
    </div>



@endsection
