@foreach($liste as $id => $ligne)
<tr>
    <td><input type="checkbox" id="check{{ $id }}" value="{{ $id }}"></td>
    {{-- <td align="left" class="px-2 align-middle">{{ $ligne->crud_name }}</td> --}}
    @for ($i = 0; $i < count($ligne->afficher); $i++)
        <td align="left" class="px-2 align-middle">{{ $ligne->afficher[$i] }}</td>
    @endfor
    <td class="text-right">
        <a href="{{ $ligne->href_show }}" title="Voir" class="text-decoration-none">
            <button class="btn-sm btn-success">
                {!! \Config::get('listes.boutons.voir') !!}
                <span class="d-none d-lg-inline">Voir</span>
            </button>
        </a>
        <a href="{{ $ligne->href_update }}" title="Editer" class="text-decoration-none">
            <button class="btn-sm btn-info text-white">
                {!! \Config::get('listes.boutons.editer') !!}
                <span class="d-none d-lg-inline">Éditer</span>
            </button>
        </a>
        <a href="" title="Supprimer" class="text-decoration-none">
            <button class="btn-sm btn-danger">
                {!! \Config::get('listes.boutons.supprimer') !!}
                <span class="d-none d-lg-inline">Supprimer</span>
            </button>
        </a>
    </td>
</tr>
@endforeach
