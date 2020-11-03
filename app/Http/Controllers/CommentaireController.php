<?php

namespace App\Http\Controllers;

use App\Commentaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentaireController extends Controller
{
    public function store(Request $request)
    {
        $rules = [
            'user_id' => 'required|integer|exists:users,id',
            'match_id' => 'required|integer|exists:matches,id',
            'comm' => 'required|min:3'
        ];

        $request = Validator::make($request->all(), $rules)->validate();
        $userId = $request['user_id'];
        $matchId = $request['match_id'];
        $comm = $request['comm'];
        $commentaire = Commentaire::create([
            'user_id' => $userId,
            'match_id' => $matchId,
            'comm' => $comm,
        ]);

        return $commentaire;
    }
}
