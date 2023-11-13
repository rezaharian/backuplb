@extends('superadmin.dashboard.layout.layout')

@section('content')

    <div class="container text-xs ">
        <form action="{{ url('/superadmin/dashboard/problemmsn/store') }}" method="POST" enctype="multipart/form-data">
            @csrf
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

            <div class="card rounded-0 border-primary p-1">
                <div class="row">

                    <div class="text-end">
                        <a href="/superadmin/dashboard/problemmsn/print/{{ $view_d->id }}" target="_blank">
                            <i class="fas fa-print m-2"></i>
                        </a>
                    </div>


                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr>
                                <td><label for="example-text-input" class="form-control-label">NO Doc</label></td>
                                <td><input disabled class="form-control form-control-sm rounded-0" type="text"
                                        name="prob_cod" value="{{ $view->prob_cod }}"></td>
                            </tr>
                            <tr>
                                <td><label for="example-text-input" class="form-control-label">Tanggal </label></td>
                                <td><input disabled class="form-control form-control-sm rounded-0" type="text"
                                        name="tgl_input" value="{{ $view->tgl_input }}" required></td>
                            </tr>
                            <tr>
                                <td><label for="example-text-input" class="form-control-label">Masalah </label></td>
                                <td>
                                    <textarea disabled class="form-control form-control-sm rounded-0" type="text"
                                        name="masalah" value="{{ $view->masalah }}"
                                        required>{{ $view->masalah }}</textarea>
                                </td>
                            </tr>
                            <tr>
                                <td><label for="example-text-input" class="form-control-label">Penyebab </label></td>
                                <td>
                                    <textarea disabled class="form-control form-control-sm rounded-0" type="text"
                                        name="penyebab" value="{{ $view_d->penyebab }}"
                                        required>{{ $view_d->penyebab }}</textarea>
                                </td>
                            </tr>
                            <tr>
                                <td><label for="example-text-input" class="form-control-label">Tgl Perbaikan</label></td>
                                <td><input disabled class="form-control form-control-sm rounded-0" type="text"
                                        name="tgl_rpr" value="{{ $view_d->tgl_rpr }}"></td>
                            </tr>

                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr>
                                <td><label for="example-text-input" class="form-control-label">Line </label></td>
                                <td>
                                    <select disabled class="form-select form-select-sm rounded-0" name="line"
                                        id="package" aria-label="Default select example">
                                        <option selected value="Training">{{ $view->line }}</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><label for="example-text-input" class="form-control-label">Unit Mesin </label></td>
                                <td>
                                    <select disabled class="form-select form-select-sm rounded-0" name="unitmesin"
                                        aria-label="Default select example">
                                        <option selected>{{ $view->unitmesin }}</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><label for="example-text-input" class="form-control-label">perbaikan </label></td>
                                <td>
                                    <textarea disabled class="form-control form-control-sm rounded-0" type="text"
                                        name="perbaikan" value="{{ $view_d->perbaikan }}"
                                        required>{{ $view_d->perbaikan }}</textarea>
                                </td>
                            </tr>
                            <tr>
                                <td><label for="example-text-input" class="form-control-label">Tgl Pencegahan</label></td>
                                <td><input disabled class="form-control form-control-sm rounded-0" type="text"
                                        name="tgl_pre" value="{{ $view_d->tgl_pre }}"></td>
                            </tr>
                            <tr>
                                <td><label for="example-text-input" class="form-control-label">Pencegahan </label></td>
                                <td>
                                    <textarea disabled class="form-control form-control-sm rounded-0" type="text"
                                        name="pencegahan" value="{{ $view_d->pencegahan }}"
                                        required>{{ $view_d->pencegahan }}</textarea>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

    </div>
    </table>
    <div class="modal-body m-3">
        <div style="height: 450px; overflow: auto;">
            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <a href="/image/{{ $view->img_pro01 }}" target="new">
                            <img class="card-img-top" src="/image/{{ $view->img_pro01 }}"
                                alt="/image/{{ $view->img_pro01 }}">
                        </a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <a href="/image/{{ $view->img_pro02 }}" target="new">
                            <img class="card-img-top" src="/image/{{ $view->img_pro02 }}"
                                alt="/image/{{ $view->img_pro02 }}">
                        </a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <a href="/image/{{ $view->img_pro03 }}" target="new">
                            <img class="card-img-top" src="/image/{{ $view->img_pro03 }}"
                                alt="/image/{{ $view->img_pro03 }}">
                        </a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <a href="/image/{{ $view->img_pro04 }}" target="new">
                            <img class="card-img-top" src="/image/{{ $view->img_pro04 }}"
                                alt="/image/{{ $view->img_pro03 }}">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    </form>

@endsection
