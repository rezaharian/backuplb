@extends('hr.dashboard.layout.layout')

@section('content')
    <div class="container">
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
        <div class="alert alert-info text-center">
            <p>{{ Session::get('success') }}</p>
        </div>
    @endif


    <a href="/hr/dashboard/overt/create/">
        <button class="btn btn-sm btn-primary m-0 mb-1 btn-tambah ">Tambah</button>
    </a>
    <div class="card card1 card-plain border-primary rounded-0">
        <div class="row ">
                <div class="col-md-12 ">
                    <div style="height:500px;overflow:auto;">
                        <table class="table" style="font-size: 10pt;">
                            <thead class="bg-primary text-light sticky-top ">
                                <tr class="fw-bold">
                                    <td>No</td>
                                    <td>Kode</td>
                                    <td>Tanggal</td>
                                    <td>Hari</td>
                                    <td>Bagian</td>
                                    <td>Pekerjaan</td>
                                    <td>Action</td>
                                </tr>
                            </thead>
                            <tbody class="text-secondary">
                                @foreach ($data as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->ot_cod }}</td>
                                        <td>{{ $item->ot_dat }}</td>
                                        <td>{{ $item->ot_day }}</td>
                                        <td>{{ $item->ot_bag }}</td>
                                        <td>{{ $item->keterangan }}</td>
                                        <td class="item-center text-center">
                                        <a  href="{{ route('hr.overt.detail', ['id' => $item->id ]) }}"><i
                                    class="fas fa-eye"></i>    </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
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
            placeholder: 'SEMUA',
            ajax: {
                url: '/autocompleted',
                dataType: 'json',
                delay: 5,
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: [item.nama_asli, item.no_payroll],
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
     .card1, .btn-tambah {
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
