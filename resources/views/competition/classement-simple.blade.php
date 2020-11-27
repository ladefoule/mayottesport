<h4 class="h5 py-2 text-center">Le classement</h4>
<table class="table">
    <thead>
        <th>Pos.</th>
        <th>{{ Str::ucfirst('équipe') }}</th>
        <th>Pts</th>
    </thead>
    <tbody class="border-bottom">
        <?php $i = 1; ?>
        @foreach ($classement as $equipe)
            <tr>
                <td>{{ $i++ }}</td>
                <td align="left"><a href="" class="text-dark">{{ $equipe['nom'] }}</a></td>
                <td>{{ $equipe['points'] }}</td>
            </tr>
            @php
                if($i == 5) break;
            @endphp
        @endforeach
    </tbody>
</table>
<a class="d-block text-center" href="{{ $hrefClassementComplet }}">Classement complet</a>
