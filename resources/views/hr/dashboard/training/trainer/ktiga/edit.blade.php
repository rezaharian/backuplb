@extends('hr.dashboard.training.trainer.layout.layout')

@section('content')

<div class="dekstop-only">
    <div class="card card-plain card1">
        <div class="card-header pb-0 text-left">
            <img  src="/image/logos/logo-ext.png" alt="" style="width:20%;">
            <h4 class="font-weight-bolder text-dark text-center ">
                FORMULIR PELAPORAN K3 DAN KESELAMATAN ASET (FPK3)</h4>
        </div>
        <div class="">
            <form action="/trainer/ktiga/update/{{ $data->id }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class=" bg-dark text-light ">
                    <p class="fw-bold m-0">Bagian I : <br>
                        Temuan Masalah/ ketidak sesuaian</p>
                </div>
                <div class="row p-0">
                    <div class="col">   

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <td style="border: 1px solid black; border-right: none;"><label for="example-text-input"
                                            class="form-control-label text-secondary">Diajukan
                                            Oleh</label></td>
                                    <td style="border: 1px solid black; border-left: none; border-right: none;"><input
                                            class="form-control form-control-sm" type="text" name="pemohon" value="{{ $data->pemohon }}"
                                            id="field1">
                                    </td>
                                    <td style="border: 1px solid black; border-right: none; border-left: none"><label
                                            for="example-text-input" class="form-control-label text-secondary">No
                                            Urut</label></td>
                                    <td style="border: 1px solid black; border-left: none; border-right: none;"><input
                                            readonly class="form-control form-control-sm" type="text" name="no_urut"
                                            value="{{ $data->no_urut }}"></td>
                                    <td style="border: 1px solid black; border-right: none; border-left: none"><label
                                            for="example-text-input" class="form-control-label text-secondary">Kode
                                            Masalah</label></td>
                                    <td style="border: 1px solid black; border-left: none;">
                                        <select name="jenis_masalah" class="form-select form-select-sm" id="field1">
                                            <option value="{{ $data->jenis_masalah }}" selected>{{ $data->jenis_masalah }}</option>
                                            <option value="K3" >K3</option>
                                            <option value="Umum">Umum</option>
                                        </select>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="border: 1px solid black; border-right: none;">
                                        <label for="example-text-input"
                                            class="form-control-label text-secondary">Bagian</label>
                                    </td>
                                    <td style="border: 1px solid black; border-left: none;">
                                        <select class="form-control form-control-sm" type="text" name="bagian"
                                        id="field1">
                                        <option value="{{ $data->bagian }}" selected>{{ $data->bagian }}</option>
                                        @foreach ($bagian as $item)
                                            <option value="{{ $item->bagian }}">{{ $item->bagian }}</option>
                                        @endforeach
                                    </select>
                                    </td>
                                    <td colspan="2" style="border: 1px solid black; border-right: none; "
                                        class="text-end">
                                        <label for="example-text-input"
                                            class="form-control-label text-secondary">Tanggal</label>
                                    </td>
                                    <td colspan="2" style="border: 1px solid black; border-left: none;">
                                        <input  class="form-control form-control-sm" type="date" name="tanggal" value="{{ $data->tanggal }}">
                                    </td>
                                </tr>
                                <tr>

                                    <td colspan="6" style="border: 1px solid black;">
                                        <textarea class="form-control form-control-sm mb-2" name="masalah" style="height: 150px;">{{ $data->masalah }}</textarea>
                                        <small>Lampirkan dokumen/foto ketidaksesuaian:</small>
                                        <input type="file" id="gambar" name="file_foto" accept="image/*" onchange="previewImage(event)">
                                        <br>
                                        <img id="preview" src="{{ $data->file_foto ? asset('uploads/ktiga/' . $data->file_foto) : '' }}" alt="Preview Gambar"
                                            style="max-width: 200px;">
                                        
                                    </td>
                                </tr>
                               
                            </table>
                        </div>
                    </div>
                </div>
                <td>
                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                </td>
                </tr>
                </table>
            </form>

        </div>
    </div>
    </div>
    <div class="mobile-only">
<h1>bentar ya</h1>
    </div>
    <style>
        .table-responsive {
            overflow-x: auto;
        }

        .radio-label {
            text-transform: initial;
        }
        /* untuk mengatur desktop atau hp */
        .dekstop-only {
    display: none;
  }

  .mobile-only {
    display: block;
  }

  /* Atur tampilan berdasarkan ukuran layar */
  @media only screen and (min-width: 768px) {
    .dekstop-only {
      display: block;
    }

    .mobile-only {
      display: none;
    }
  }
    </style>

    <script>
        // Preview gambar saat dipilih
        const gambar = document.getElementById('gambar');
        const preview = document.getElementById('preview');

        gambar.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.addEventListener('load', function() {
                    preview.src = reader.result;
                });
                reader.readAsDataURL(file);
            }
        });


        function toggleCatatan(show) {
            var catatanTidakEfektif = document.getElementById("catatanTidakEfektif");
            catatanTidakEfektif.style.display = show ? "block" : "none";
        }

        // gambar
        // Fungsi untuk menampilkan preview gambar
function previewImage(event) {
    var input = event.target;
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('preview').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        // Jika tidak ada gambar yang dipilih, tampilkan gambar dari database
        document.getElementById('preview').src = '{{ $data->file_foto ? asset('uploads/ktiga/' . $data->file_foto) : '' }}';
    }
}
package
    </script>
@endsection