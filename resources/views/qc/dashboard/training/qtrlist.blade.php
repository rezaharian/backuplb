@extends('qc.dashboard.layout.layout')

@section('content')
    <div class="section  px-2">
        <div class="row ">
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
            <div class="col-md-10">
                <div class="row">
                    <div class="col-md-5 ">
                        <a href="/qc/training/qtrcreate"  >
                            <button class="btn btn-md btn-primary m-0  "><i class="fas fa-add"></i></button>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
            </div>
        </div>
        <div class="row mt-1">
            <div class="col-md-12">
            <div class="card  ">
                <div style="height:450px;overflow:auto;">
                    <table  class="  align-items-center mb-0 table-bordered border-secondary border-light " >
                        <thead class=" bg-primary text-center text-uppercase  text-light shadow font-weight-bolder sticky-top  ">
                           
                            <tr class="text-xs opacity-10" >
                                <td style="width:10%;">No Doc</td>
                                <td style="width:15%;">Pemateri</td>
                                <td style="width:20%;" >Pelatihan</td>
                                <td hidden style="width:20%;">Kompetensi</td>
                                <td style="width:8%;">Tanggal</td>
                                <td style="width:10%;">Tipe</td>
                                <td style="width:1%;">Action</td>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($training as $item)
                                <tr class=" text-uppercase    " style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif; font-size:10pt;",>
                                    <td class="opacity-7">{{ $item->train_cod }}</td>
                                    <td class="opacity-7">{{ $item->pemateri }}</td>
                                    <td class="opacity-7">{{ $item->train_tema }}</td>
                                    <td hidden class="opacity-7">{{ $item->kompetensi }}</td>
                                    <td class="opacity-7" class="text-center">{{ $item->train_dat }}</td>
                                    <td class="opacity-7">{{ $item->tipe }}</td>
                                    <td class="text-center " ><a href="{{ route('training.qtrview', $item->id) }}"
                                          ><i class="fas fa-eye"></i>
                                        </a></td>
                                </tr>
                            @empty
                                <div class="alert alert-danger">
                                    Data Post belum Tersedia.
                                </div>
                            @endforelse
                </div>
            </div>
        </div>
    </div>
    </div>


 
@endsection

