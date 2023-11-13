@extends('hr.dashboard.layout.layout')

@section('content')
    <div class="container  px-3">
        <div class="row">
           
            
            <div class="col-md-12">
                <div class="card card-plain rounded-0 border-primary">
                    <form class="mt-4 mx-2" action="/hr/dashboard/training/tra_kar_print/" target="_blank" >
                    <div class="row">
               
                        <div class="col-md-3">
                                {{-- <input type="text" class="form-control form-control-sm"  name="no_payroll" id="exampleFormControlInput1"
                                    placeholder="NIK" required> --}}
                                    <select required class="nama_asli form-control font-weight-bolder" name="no_payroll" type="text" id="no_payroll">
                                    </select>
                        </div>
                        <div class="col-md-3">
                                <div class="form-group">
                                    <select class="form-control form-control-sm"name="jns_tr" id="">
                                        <option value="">SEMUA</option>
                                        <option value="Pelatihan">PELATIHAN</option>
                                        <option value="Sosialisasi">SOSIALISASI</option>
                                    </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="date" class="form-control form-control-sm"  name="train_dat_awal" id="exampleFormControlInput1"
                                    placeholder="dari tanggal" required>
                            </div>
                        </div>s/d
                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="date" class="form-control form-control-sm"  name="train_dat_akhir" id="exampleFormControlInput1"
                                    placeholder="sampai tanggal" required>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-rounded btn-sm form-control" >submit</button>
                            </div>
                        </div>
                    </div>
                </form>
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
                <div class="alert alert-danger text-center">
                    <p>{{ Session::get('success') }}</p>
                </div>
            @endif
            </div>
        </div>

        </div>
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>


    <script type="text/javascript">
        $('.nama_asli').select2({
            placeholder: 'Nama ...',
            ajax: {
                url: '/autocompleted',
                dataType: 'json',
                delay: 5,
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: [item.nama_asli , item.no_payroll],
                                id: item.no_payroll,
                                // id: item.id,

                            }
                        })
                    };
                },
                cache: false
            }
        });
    </script>
    </div>

    
    <style>
        .form-control{
            border-radius: 0;
        }
       
        .card-plain {
        opacity: 0;
        animation-name: fade-in;
        animation-duration: 0.3s;
        animation-fill-mode: forwards;
        animation-timing-function: ease-in-out;
        animation-delay: 0.3s;
    }

    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    </style>


@endsection
