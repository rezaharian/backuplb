@extends('hr.dashboard.layout.layout')

@section('content')
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
            <div class="alert alert-info text-center px-5">
                <p>{{ Session::get('success') }}</p>
            </div>
        @endif

        {{-- <form action="/hr/dashboard/pegawai/kontrak/laporan/print" target="_blank">
            <div class="row">
                <div class="col-md-10  text-end">
                </div>
                <div class="col-md-2  text-end">
                    
                    <input type="date" class="form-control form-control-sm" value="{{ $r_bln }}"
                    name="bulan" id="exampleFormControlInput1" placeholder="dari tanggal" hidden>
                    
                    <input type="date" class="form-control form-control-sm" value="{{ $r_thn }}"
                    name="tahun" id="exampleFormControlInput1" placeholder="sampai tanggal" hidden>
                    <div class="form-group">
                        
                        <button type="submit" class="btn  btn-md btn-primary  m-0 "><i
                            class="fas fa-print"></i></button>
                        </div>
                    </div>
                </div>
            </form>   --}}

        <div class="row text-end fade-in">
            <div class="col-md-10">

                <h6 class="fw-bold text-secondary">Daftar Karyawan Habis Kontrak {{ $r_bln }} - {{ $r_thn }}
                </h6>
            </div>
            <div class="col-md-2">

                <a class="" target="_blank"
                    href="{{ route('datapegawai.print_laporan_list', ['bln' => $r_bln, 'thn' => $r_thn]) }}">
                    <i class="fas fa-print"></i>
                </a>
            </div>
        </div>

        <div class="col-md-4 fade-in">
          <div class="card card-plain rounded-0 border-primary">
            <div class="card-header  pb-0 bg-primary rounded-0">
                <h4 class="font-weight-bold text-white fs-4">Cari Kontrak</h4>
              </div>
              <div class="card-body">
                <form action="/hr/dashboard/pegawai/kontrak/laporan/list">
                  <div class="mb-3">
                    <label for="bulan" class="form-label text-secondary fs-6">Bulan</label>
                    <select class="form-control form-control-sm fs-6" id="bulan" name="bulan">
                      <option selected value="{{ $r_bln }}">{{ $r_bln }}</option>
                      @foreach ($r_bulan as $item)
                        <option>{{ $item }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="mb-3">
                    <label for="tahun" class="form-label text-secondary fs-6">Tahun</label>
                    <select class="form-control form-control-sm fs-6" id="tahun" name="tahun">
                      @foreach ($r_tahun as $year)
                        <option value="{{ $year }}" {{ $year == $now_t ? 'selected' : '' }}>{{ $year }}</option>
                      @endforeach
                    </select>
                  </div>
                  <button type="submit" class="btn btn-primary btn-sm fs-6">Cari</button>
                </form>
              </div>
            </div>
          </div>
          
          

          <div class="col-md-8 fade-in">
            <div class="card card-plain rounded-0 border-primary">
              <div style="height:500px;overflow:auto;">
                <table class="table table-sm table-hover">
                  <thead class="bg-primary text-white sticky-top">
                    <tr class="fw-bold">
                      <th scope="col" style="font-size: 12px;">No</th>
                      <th scope="col" style="font-size: 12px;">Nama Karyawan</th>
                      <th scope="col" style="font-size: 12px;">Tgl Perpanjang</th>
                      <th scope="col" style="font-size: 12px;">Tgl Berakhir</th>
                    </tr>
                  </thead>
                  <tbody style="font-size: 11px;" class="text-secondary ">
                    @foreach ($data as $item)
                      <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $item->nama_asli }}</td>
                        <td>{{ $item->perpanjang }}</td>
                        <td>{{ $item->Berakhir }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          
          
    </div>


    <style>
      /* Define the initial state of the elements */
      .fade-in {
          opacity: 0;
          animation: fade-in 0.9s forwards;
      }
      .form-control{
        border-radius: 0;
      }

      @keyframes fade-in {
          from {
              opacity: 0;
              transform: translateY(100%);
          }
          to {
              opacity: 1;
              transform: translateY(0);
          }
      }
  </style>



  <style>
    /* Define the initial state of the elements */
    .fade-in {
        opacity: 0;
        transform: translateY(50px); /* Menggeser elemen 50 piksel ke bawah */
        transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
    }

    /* Define the final state of the elements */
    .fade-in.show {
        opacity: 1;
        transform: translateY(0); /* Mengembalikan elemen ke posisi semula */
    }
</style>




@endsection
