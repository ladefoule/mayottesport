@foreach($liste as $ligne)
<tr>
    <td><input type="checkbox" id="check{{ $ligne['id'] }}" value="{{ $ligne['id'] }}"></td>
    @for ($i = 0; $i < count($listeAttributsVisibles); $i++)
        <td align="left"
            @if ($i>=2) class="d-none d-lg-block" @endif
        >{{ $ligne['afficher'][$i] }}</td>
    @endfor
    <td class="text-right">
        <a href="{{ $ligne['href_voir'] }}" title="Voir" class="text-decoration-none">
            <button class="btn-sm btn-success">
                {!! \Config::get('constant.boutons.voir') !!}
                <span class="d-none d-lg-inline">Voir</span>
            </button>
        </a>
        <a href="{{ $ligne['href_editer'] }}" title="Editer" class="text-decoration-none">
            <button class="btn-sm btn-info text-white">
                {!! \Config::get('constant.boutons.editer') !!}
                <span class="d-none d-lg-inline">Éditer</span>
            </button>
        </a>
        <a href="" title="Supprimer" class="text-decoration-none">
            <button class="btn-sm btn-danger">
                {!! \Config::get('constant.boutons.supprimer') !!}
                <span class="d-none d-lg-inline">Supprimer</span>
            </button>
        </a>
    </td>
</tr>
@endforeach
