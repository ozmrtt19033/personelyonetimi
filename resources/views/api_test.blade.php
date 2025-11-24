<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>API Test Paneli</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white">

<div class="container mt-5 text-center">
    <h1>📡 API Bağlantı Testi</h1>
    <p>Aşağıdaki butona basınca Mobil Uygulama gibi davranıp veri göndereceğiz.</p>

    <button id="btnGonder" class="btn btn-success btn-lg mt-3">Veriyi Gönder (POST)</button>

    <div id="sonuc" class="alert alert-info mt-4" style="display:none;">
        Sonuçlar burada görünecek...
    </div>
</div>

<script>
    document.getElementById('btnGonder').addEventListener('click', function() {

        // Butona basınca API'ye istek atıyoruz
        fetch('/api/personel', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                ad_soyad: "Mobil Test Kullanıcısı",
                email: "mobil_test" + Math.floor(Math.random() * 1000) + "@atc.com", // Rastgele email ürettik hata vermesin
                departman: "Mobil Ar-Ge",
                maas: 75000
            })
        })
            .then(response => response.json())
            .then(data => {
                console.log("Gelen Cevap:", data);

                // Ekrana yazdıralım
                let kutu = document.getElementById('sonuc');
                kutu.style.display = 'block';

                if(data.status) {
                    kutu.className = 'alert alert-success';
                    kutu.innerHTML = '✅ BAŞARILI: ' + data.message + '<br>Kayıt ID: ' + data.data.id;
                } else {
                    kutu.className = 'alert alert-danger';
                    kutu.innerHTML = '❌ HATA: ' + JSON.stringify(data);
                }
            })
            .catch(error => {
                console.error('Bağlantı Hatası:', error);
                alert("Bir hata oluştu reis, konsola bak!");
            });
    });
</script>

</body>
</html>
