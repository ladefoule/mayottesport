<?php $i = 0 ?>
@foreach ($matches as $match)
    @include('journee.modele-calendrier-main', ['match' => infos('matches', $match->id), 'i' => $i])
    <?php $i++; ?>
@endforeach
