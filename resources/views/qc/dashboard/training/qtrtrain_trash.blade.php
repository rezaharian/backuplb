@extends('qc.dashboard.layout.layout')

@section('content')

<div class="container px-5">

    <div class="text-center font-xs">
        <strong>
            Data yang pernah di hapus :
        </strong>
    </div>
    <div>
        @foreach ($data_dd as $item)
<a href="/qc/training/qtrrestore_all/{{ $item->train_cod }}" class="btn btn-success btn-sm">Restore All</a>
@endforeach
</div>


<table class="table">
    <thead class="bg-secondary text-light">
        <th>Kode</th>
        <th>Tanggal</th>
        <th>NIK</th>
        <th>Nama Karyawan</th>
        <th>Nilai</th>
        <th>Keterangan</th>
        <th>Cek HR</th>
        <th>Opsi</th>
    </thead>
    
    @forelse ($data_d as $item)
    
        
    <tr>
        <td>{{ $item->train_cod }}</td>
        <td>{{ $item->train_dat }}</td>
        <td>{{ $item->no_payroll }}</td>
        <td>{{ $item->nama_asli }}</td>
        <td>{{ $item->nilai }}</td>
        <td>{{ $item->keterangan }}</td>
        <td>{{ $item->approve }}</td>  
        <td>
            <a href="/qc/training/qtrrestore_trash/{{ $item->id }}" class="btn btn-success btn-sm">Restore</a>

        </td> 
    </tr>
    @empty
</table>
<h2>data tidak ada</h2>

    @endforelse
    
    
</div>
    @endsection