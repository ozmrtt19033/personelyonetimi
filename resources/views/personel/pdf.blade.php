<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Personel Kartı</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .container { border: 2px solid #333; padding: 20px; width: 100%; }
        .header { text-align: center; border-bottom: 1px solid #ccc; margin-bottom: 20px; }
        .photo { text-align: center; margin-bottom: 20px; }
        .photo img { width: 150px; height: 150px; border-radius: 50%; border: 1px solid #000; }
        .info { font-size: 18px; line-height: 1.6; }
        .label { font-weight: bold; width: 150px; display: inline-block; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>ATC YAZILIM A.Ş.</h1>
        <h3>Personel Kimlik Kartı</h3>
    </div>

    <div class="photo">
        @if($personel->gorsel)
            <img src="{{ public_path('storage/' . $personel->gorsel) }}">
        @else
            <p>[Resim Yok]</p>
        @endif
    </div>

    <div class="info">
        <div><span class="label">Ad Soyad:</span> {{ $personel->ad_soyad }}</div>
        <div><span class="label">E-Posta:</span> {{ $personel->email }}</div>
        <div><span class="label">Departman:</span> {{ $personel->departman?->ad }}</div>
        <div><span class="label">Maaş:</span> {{ number_format($personel->maas, 2) }} ₺</div>
        <div><span class="label">Giriş Tarihi:</span> {{ $personel->ise_baslama_tarihi }}</div>
    </div>

    <br><hr>

    <h4>Görevli Olduğu Projeler:</h4>
    <ul>
        @foreach($personel->projects as $proje)
            <li>{{ $proje->ad }}</li>
        @endforeach
    </ul>
</div>

</body>
</html>
