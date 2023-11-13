@extends('hr.dashboard.layout.layout')

@section('content')
    <form method="POST">
        @csrf
        <div class="row">
            <div class="col-md-4">
                <table class="table">
                    <tr>
                        <div class="row ms-3 bg-gradien-light  pt-3 text-center item-center justify-content-center">
                            <div class="col-md-2">
                                <a href="{{ route('hr.kompetensi.delete', $view->id) }}" onclick="return confirm('Apakah Anda Yakin ?');" class="btn btn-sm btn-danger">Delete</a>
                            </div>
                            <div class="col-md-2 ms-2">
                                <a href="{{ route('hr.kompetensi.edit', $view->id) }}" class="btn btn-sm btn-primary">Edit</a>
    
                            </div>
    
                        </div>
                        
                    </tr>
                    <tr>
                        <td><label for="example-text-input" class="form-control-label">NO Kompetensi</label></td>
                        <td><input disabled class="form-control" type="text" name="train_cod"
                                value="{{ $view->kompe_cod }}">
                        </td>
                    </tr>
                    <tr>
                        <td><label for="example-text-input" class="form-control-label">bagian</label></td>
                        <td><input disabled class="form-control" type="text" name="tempat" value="{{ $view->bagian }}">
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        </table>

        </div>

        <div class="row">
            <div class="col-lg-12">
                <table class="table" style="width:100%;">
                    <thead class="bg-secondary text-light">
                        <th>No.</th>
                        <th>Kompetensi</th>
                        <th>jenis Kompetensi</th>
                    </thead>
                    <tbody id="tbl-barang-body">
                        @forelse ($view_d as $item)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $item->kompetensi }}</td>
                                <td>{{ $item->jenis }}</td>
                            </tr>
                        @empty
                            <td>
                                <h1>data tidak ada</h1>
                            </td>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>

        <div class="row btnSave" style="display:none;">
            <div class="col-lg-12">
                <button type="submit" class="btn btn-primary">SIMPAN </button>
            </div>
        </div>

        </div>
    </form>
@endsection
