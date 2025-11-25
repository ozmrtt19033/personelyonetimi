<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>{{ $personel->ad_soyad }} - Detay</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">👤 Personel Kartı</h4>
                    <a href="{{ route('personel.index') }}" class="btn btn-sm btn-light">⬅ Listeye Dön</a>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 border-end">
                            <h2 class="text-primary">{{ $personel->ad_soyad }}</h2>
                            <p class="text-muted">{{ $personel->email }}</p>

                            <hr>

                            <p><strong>🏢 Departman:</strong>
                                <span class="badge bg-secondary">
                                    {{ $personel->departman?->ad ?? 'Atanmamış' }}
                                </span>
                            </p>

                            <p><strong>💰 Maaş:</strong> {{ number_format($personel->maas, 2) }} ₺</p>

                            <p><strong>📅 Başlama Tarihi:</strong>
                                {{ $personel->ise_baslama_tarihi ? \Carbon\Carbon::parse($personel->ise_baslama_tarihi)->format('d.m.Y') : '-' }}
                            </p>
                        </div>

                        <div class="col-md-6">
                            <h5 class="card-title text-dark border-bottom pb-2">📂 Görevli Olduğu Projeler</h5>

                            @if($personel->projects->count() > 0)
                                <div class="list-group list-group-flush mt-3">
                                    @foreach($personel->projects as $proje)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <span>{{ $proje->ad }}</span>
                                            <span class="badge bg-primary rounded-pill">Aktif</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-warning mt-3">
                                    Bu personel henüz hiçbir projeye atanmamış reis.
                                </div>
                            @endif

                        </div>
                    </div>
                </div>

                <div class="card-footer text-muted text-end">
                    Kayıt Tarihi: {{ $personel->created_at->diffForHumans() }}
                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>
