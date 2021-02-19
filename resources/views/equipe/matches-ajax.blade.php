<?php $i = 0 ?>
@foreach ($matches as $match)
    @include('journee.calendrier-modele-complet', ['match' => infos('matches', $match->id), 'i' => $i])
    <?php $i++; ?>
@endforeach
