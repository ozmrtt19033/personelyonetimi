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

            <a href="{{ route('personel.pdf', $personel->id) }}" class="btn btn-sm btn-danger" title="PDF İndir">
                📄
            </a>

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
