<h4 class="h5 py-2 text-center"><i>Le classement</i></h4>
<table class="table table-striped">
    <thead>
        <th class="px-3">#</th>
        <th class="px-2">équipe</th>
        <th class="px-2">pts</th>
    </thead>
    <tbody class="border-bottom">
        @foreach ($classement->slice(0,5) as $i => $equipe)
            <tr>
                <td class="px-2">{{ $i+1 }}</td>
                <td align="left" class="px-2"><a href="{{ $equipe['hrefEquipe'] }}" class="text-dark">{{-- <img class="d-none d-xl-block float-left mr-2" width="20" src="{{ $equipe['fanion'] }}"> --}}{{ $equipe['nom'] }}</a></td>
                <td class="px-2">{{ $equipe['points'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
<a class="d-block text-center" href="{{ $hrefClassementComplet }}">Le classement complet</a>
