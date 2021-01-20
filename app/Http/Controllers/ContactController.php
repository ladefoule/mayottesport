<?php
 
namespace App\Http\Controllers;
 
use App\Mail\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
 
class ContactController extends Controller
{
    public function create()
    {
        Log::info(" -------- Controller Contact : create -------- ");
        return view('contact');
    }
 
    public function post(Request $request)
    {
        Log::info(" -------- Controller Contact : post -------- ");
        $rules = [
            'nom' => 'required|min:3|max:30',
            'captcha' => 'required|captcha',
            'email' => 'required|email',
            'message' => 'required|min:5',
        ];

        $data = Validator::make($request->all(), $rules)->validate();
        // Mail::to('magik.systematik@hotmail.com')
        Mail::to(config('mail.contact'))
            ->queue(new Contact($data));
 
            return back()
                ->with('success','Votre message a bien été envoyé. Merci.');
    }
}