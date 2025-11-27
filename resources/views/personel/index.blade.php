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

            <div class="card mb-3">
                <div class="card-body">
                    <input type="text" id="search" class="form-control"
                           placeholder="🔍 Personel ara (İsim, departman...)...">
                </div>
            </div>

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

                <table class="table table-hover table-bordered">
                    <thead class="table-dark">
                    <tr>
                        <th>Foto</th>
                        <th>Ad Soyad</th>
                        <th>Departman</th>
                        <th>Maaş</th>
                        <th>İşlemler</th>
                    </tr>
                    </thead>
                    <tbody id="personel-table-body">
                    @include('personel.tbody')
                    </tbody>
                </table>

            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // Arama kutusunu seç
        const searchInput = document.getElementById('search');
        const tableBody = document.getElementById('personel-table-body');

        // Klavye tuşuna basıldığında (keyup)
        searchInput.addEventListener('keyup', function () {
            let query = this.value;

            // AJAX İsteği (Fetch API)
            fetch("{{ route('personel.index') }}?search=" + query, {
                headers: {
                    "X-Requested-With": "XMLHttpRequest" // Laravel'e "Bu bir AJAX isteğidir" diyoruz
                }
            })
                .then(response => response.text()) // HTML cevabı alıyoruz
                .then(html => {
                    // Tablo gövdesini gelen yeni HTML ile değiştir
                    tableBody.innerHTML = html;
                })
                .catch(error => console.error('Hata:', error));
        });
    </script>
@endpush
