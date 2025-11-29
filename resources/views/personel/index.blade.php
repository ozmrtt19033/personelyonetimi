@extends('layouts.app') @section('content')
    <div class="container">

        <!--components içerisinden verileri dinamik olarak çekiyorum!!!-->
        @if(session('success'))
            @component('components.alert', ['type' => 'success'])
                {{ session('success') }}
            @endcomponent
        @endif

        @if(session('error'))
            @component('components.alert', ['type' => 'danger'])
                <strong>Hata:</strong> {{ session('error') }}
            @endcomponent
        @endif

        <div class="card shadow">

            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">👨‍💼 Personel Listesi</h4>

                <div>
                    <a href="{{ route('personel.export') }}" class="btn btn-success btn-sm me-2">📊 Excel</a>

                    @if(auth()->check() && auth()->user()->role == 'admin')
                        <a href="{{ route('personel.create') }}" class="btn btn-light btn-sm text-primary">➕ Yeni
                            Ekle</a>
                    @endif
                </div>
            </div>

            <div class="card-body">

                <table id="yajra_datatable" class="table table-hover table-bordered">
                    <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Foto</th>
                        <th>Ad Soyad</th>
                        <th>Departman</th>
                        <th>Maaş</th>
                        <th>İşlemler</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>

            </div>
        </div>
    </div>

@endsection

@push('scripts')

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {
            var table = $('#yajra_datatable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true, // İŞTE SİHİR BURADA! (Veriyi sunucudan parça parça çeker)
                ajax: "{{ route('personel.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'gorsel', name: 'gorsel', orderable: false, searchable: false},
                    {data: 'ad_soyad', name: 'ad_soyad'},
                    {data: 'departman_ad', name: 'departman.ad'}, // İlişkili aramayı böyle yapar
                    {data: 'maas', name: 'maas'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],

                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/tr.json'
                }
            });
        });

        // Silme İşlemi İçin Fonksiyon (SweetAlert kullanırsan daha şık olur ama şimdilik düz yapalım)
        function deletePersonel(id) {
            if (confirm('Gerçekten silmek istiyor musun reis?')) {
                $.ajax({
                    url: '/personel/' + id,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        $('#yajra_datatable').DataTable().ajax.reload(); // Tabloyu yenile
                        alert('Silindi!');
                    },
                    error: function (err) {
                        alert('Hata oluştu!');
                    }
                });
            }
        }
    </script>
@endpush

