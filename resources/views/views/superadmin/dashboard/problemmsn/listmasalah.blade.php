@extends('superadmin.dashboard.layout.layout')

@section('content')
    <div class="section">
        <div class="row">
            <div class="row text-center mb-3">
                <h5 class="font-weight-bolder mb-0">Daftar/List Kerusakan/Masalah Mesin</h5>
            </div>
            <div class="col-md-10">
                <div class="row">
                    <div class="col-md-1 ">
                    </div>
                    <div class="col-md-2 ">
                        <form class="form-group"action="{{ route('adm.problemmsn.index') }}" method="GET">
                            <select class="form-select  font-weight-bold text-secondary  rounded border-primary"
                                name="cariline" placeholder="line ">
                                <option selected value="">SEMUA</option>
                                @foreach ($datal as $item)
                                    <option value="{{ $item->line }}">{{ $item->line }}</option>
                                @endforeach
                            </select>
                    </div>
                    <div class="col-md-2 ">
                        <select class="form-select  font-weight-bold text-secondary  rounded border-primary "
                            name="cariunitmsn" placeholder="unit ">
                            <option selected value="">SEMUA</option>
                            @foreach ($datau as $item)
                                <option value="{{ $item->unit_nam }}">{{ $item->unit_nam }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control font-weight-bold text-secondary border-primary"  name="masalah"
                            placeholder="masalah">
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control font-weight-bold text-secondary border-primary" name="penyebab"
                            placeholder="penyebab">
                    </div>
                    <div class="col-md-1 ">
                        <input type="submit" class="btn btn-primary border text-light" value="CARI">
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-1">
        </div>
    </div>
    <div class="row mt-1">
        <div class="col-md-12">
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
            <div class="card ">
                <div style="height:450px;overflow:auto;">
                    <table  class="  align-items-center mb-0  table-bordered border-dark">
                        <thead class=" bg-primary text-center text-light shadow font-weight-bolder sticky-top  ">
                           
                                <tr class="text-xs">
                                <th style="width: 5%;">Line</th>
                                <th style="width: 12%;">Unit Mesin</th>
                                <th style="width:15%;">Masalah</th>
                                <th  style="width:15%;">Penyebab</th>
                                <th  style="width:15%;">Perbaikan</th>
                                <th  style="width:15%;">Pencegahan</th>
                                <th  style="width:7%;">TGL</th>
                                <th style="width:7%;">No Doc</th>
                                <th  style="width:4%; text-align:center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($list as $item)
                                <tr class=" text-xs  ">
                                    <td class="text-xs">{{ $item->line }}</td>
                                    <td>{{ $item->unitmesin }}</td>
                                    <td >
                                       {{ $item->masalah }}
                                    </td>
                                    <td>
                                       {{ $item->penyebab }}
                                    </td>
                                    <td>
                                       {{ $item->perbaikan }}
                                    </td>
                                    <td>
                                       {{ $item->pencegahan }}
                                    </td>
                                    <td>{{ $item->tgl_input }}</td>
                                    <td>{{ $item->prob_cod }}</td>
                                    <td style="text-align: center;"><a href="{{ route('adm.problemmsn.view_d', $item->id) }}">
                                    <button type="button" style="background-color: rgb(255, 255, 0); ">Lihat</button>
                                    </a>
                                    </td>
                                </tr>
                            @empty
                                <div class="alert alert-danger">
                                    Data belum Tersedia.
                                </div>
                            @endforelse
                        </tbody>
                    </table> 
                </div>
            </div>
            </div>
        </div>
    </div>
    </div>



@endsection