<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Personel Listesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            ✅ {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>👨‍💼 Personel Listesi</h1>
        <a href="{{route('personel.create')}}" class="btn btn-primary">Yeni Personel Ekle</a>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <table class="table table-hover table-bordered">
                <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Foto</th>
                    <th>Ad Soyad</th>
                    <th>Departman</th>
                    <th>Maaş</th>
                    @if(auth()->check() && auth()->user()->role == 'admin')
                        <th>İşlemler</th>

                    @endif

                </tr>
                </thead>
                <tbody>
                @forelse($personeller as $personel)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if($personel->gorsel)
                                <img src="{{ asset('storage/' . $personel->gorsel) }}"
                                     alt="Foto" width="50" height="50" class="rounded-circle" style="object-fit: cover;">
                            @else
                                <span class="badge bg-secondary">Resim Yok</span>
                            @endif
                        </td>
                        <td>{{ $personel->ad_soyad }}</td>
                        <td>
                            <span class="badge bg-info text-dark">{{ json_decode($personel->departman)->ad ?? 'Departman Bulunamadı' }}</span>
                        </td>
                        <td>{{ number_format($personel->maas, 2) }} ₺</td>
                        @if(auth()->check() && auth()->user()->role == 'admin')

                            <td>
                                <a href="{{ route('personel.edit', $personel->id) }}" class="btn btn-sm btn-warning">Düzenle</a>
                                <a href="{{ route('personel.show', $personel->id) }}" class="btn btn-sm btn-info text-white" title="Detay">
                                    👁️
                                </a>
                                <form action="{{ route('personel.destroy', $personel->id) }}" method="POST" class="d-inline">

                                    @csrf
                                    @method('DELETE') <button type="button" class="btn btn-sm btn-outline-danger"
                                                              onclick="return confirm('Reis, bu personeli silmek istediğine emin misin?') ? this.parentElement.submit() : null;">
                                        Sil
                                    </button>
                                </form>
                            </td>
                        @endif

                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            Henüz kayıtlı personel yok reis.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>


</body>
</html>
