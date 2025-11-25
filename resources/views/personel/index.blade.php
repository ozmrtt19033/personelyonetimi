@extends('layouts.app') @section('content')
    <div class="container">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">👨‍💼 Personel Listesi</h4>

                <div>
                    <a href="{{ route('personel.export') }}" class="btn btn-success btn-sm me-2">📊 Excel</a>

                    @if(auth()->check() && auth()->user()->role == 'admin')
                        <a href="{{ route('personel.create') }}" class="btn btn-light btn-sm text-primary">➕ Yeni Ekle</a>
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
                    <tbody>
                    @forelse($personeller as $personel)
                        <tr>
                            <td>
                                @if($personel->gorsel)
                                    <img src="{{ asset('storage/' . $personel->gorsel) }}" width="50" class="rounded-circle" style="object-fit: cover;">
                                @else
                                    <span class="badge bg-secondary">Yok</span>
                                @endif
                            </td>
                            <td>
                                {{ $personel->ad_soyad }} <br>
                                @foreach($personel->projects as $proje)
                                    <span class="badge bg-info text-dark" style="font-size: 0.7em">{{ $proje->ad }}</span>
                                @endforeach
                            </td>
                            <td>{{ $personel->departman?->ad }}</td>
                            <td>{{ number_format($personel->maas, 2) }} ₺</td>
                            <td>
                                <a href="{{ route('personel.show', $personel->id) }}" class="btn btn-sm btn-info text-white">👁️</a>

                                @if(auth()->check() && auth()->user()->role == 'admin')
                                    <a href="{{ route('personel.edit', $personel->id) }}" class="btn btn-sm btn-warning">✏️</a>

                                    <form action="{{ route('personel.destroy', $personel->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="return confirm('Silmek istediğine emin misin?')">🗑️</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">Kayıt yok.</td></tr>
                    @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>
@endsection
