<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yeni Personel Ekle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">➕ Yeni Personel Ekle</h4>
                </div>
                <div class="card-body">

                    <form action="{{ route('personel.store') }}" method="POST">

                        @csrf

                        <div class="mb-3">
                            <label for="ad_soyad" class="form-label">Ad Soyad</label>
                            <input type="text" class="form-control" name="ad_soyad" value="{{ old('ad_soyad') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">E-Posta Adresi</label>
                            <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="departman" class="form-label">Departman</label>
                                <select name="departman" class="form-select">
                                    <option value="">Seçiniz...</option>
                                    <option value="Yazılım" {{ old('departman') == 'Yazılım' ? 'selected' : '' }}>Yazılım</option>
                                    <option value="Muhasebe" {{ old('departman') == 'Muhasebe' ? 'selected' : '' }}>Muhasebe</option>
                                    <option value="İnsan Kaynakları" {{ old('departman') == 'İnsan Kaynakları' ? 'selected' : '' }}>İnsan Kaynakları</option>
                                    <option value="Satış" {{ old('departman') == 'Satış' ? 'selected' : '' }}>Satış</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="maas" class="form-label">Maaş (₺)</label>
                                <input type="number" step="0.01" class="form-control" name="maas" value="{{ old('maas') }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="ise_baslama_tarihi" class="form-label">İşe Başlama Tarihi</label>
                            <input type="date" class="form-control" name="ise_baslama_tarihi" value="{{ old('ise_baslama_tarihi') }}">
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('personel.index') }}" class="btn btn-secondary">⬅ Geri Dön</a>
                            <button type="submit" class="btn btn-success">💾 Kaydet</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
