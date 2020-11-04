<?php

namespace App\Http\Controllers;

use App\Commentaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        return [
            'nom' => $commentaire->user->pseudo,
            'date' => $commentaire->created_at->format('d/m/Y à H:i:s'),
            'comm' => $commentaire->comm,
            'id' => $commentaire->id
        ];
    }

    /**
     * Delete comment
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function delete(Request $request)
    {
        $rules = [
            'id' => 'required|integer|exists:commentaires,id'
        ];

        $request = Validator::make($request->all(), $rules)->validate();
        $commentaireId = $request['id'];
        $commentaire = Commentaire::findOrFail($commentaireId);
        if($commentaire->user_id != Auth::id())
            abort(404);

        $commentaire->delete();
        return response(null, 204);
    }
}
