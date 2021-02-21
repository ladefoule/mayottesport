<?php
 
namespace App\Http\Controllers;
 
use App\Mail\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
 
class ContactController extends Controller
{
    /**
     * Accès au formulaire de contact
     *
     * @return void
     */
    public function create()
    {
        Log::info(" -------- Controller Contact : create -------- ");
        return view('contact');
    }
 
    /**
     * Traitement du formulaire de contact et envoie du mail à l'admin
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function post(Request $request)
    {
        // Champ caché pour piéger les robots
        if(isset($request['prenom']) && $request['prenom'] != '')
            abort(404);

        Log::info(" -------- Controller Contact : post -------- ");
        $rules = [
            'nom' => 'required|min:3|max:30',
            'captcha' => 'required|captcha',
            'email' => 'required|email',
            'message' => 'required|min:5',
        ];

        $data = Validator::make($request->all(), $rules)->validate();
        
        Mail::to(env('APP_CONTACT_EMAIL'))
            ->queue(new Contact(collect($data)));
 
        return back()
            ->with('success','Votre message a bien été envoyé. Merci.');
    }
}