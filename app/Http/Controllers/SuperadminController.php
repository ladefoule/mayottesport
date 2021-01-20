<?php

namespace App\Http\Controllers;

use App\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SuperadminController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        Log::info("Accès au controller Superadmin - Ip : " . request()->ip());
    }

    /**
     * Suppression de tout le cache
     *
     * @return void
     */
    public function cacheFlush(Request $request)
    {
        Log::info(" -------- Controller Superadmin : cacheFlush -------- ");
        Cache::flush();
        Log::info('Suppression de tout le cache !');
    }

    /**
     * Upload d'image
     *
     * @return \Illuminate\Http\Response
     */
    public function imageUpload()
    {
        Log::info(" -------- Controller Superadmin : imageUpload -------- ");
        return view('superadmin.upload-image');
    }

    /**
     * Upload d'image (POST)
     *
     * @param Request $request
     * @return void
     */
    public function imageUploadPost(Request $request)
    {
        Log::info(" -------- Controller Superadmin : imageUploadPost -------- ");
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'nom' => 'required|alpha_dash|min:5|max:50'
        ]);

        $imageName = $request->nom . '-' .time().'.'.$request->image->extension();  
        $request->image->move(storage_path('app/public/img'), $imageName);
        return back()
            ->with('success','You have successfully upload image.')
            ->with('image',$imageName);
    }

    /**
     * Pdf Parser : accès au formulaire
     *
     * @return \Illuminate\View\View
     */
    public function pdfParser()
    {
        Log::info(" -------- Controller Superadmin : pdfParser -------- ");
        return view('superadmin.pdfparser');
    }

    /**
     * Pdf Parser : traitement de la requète
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function pdfParserPost(Request $request)
    {
        Log::info(" -------- Controller Superadmin : pdfParserPost -------- ");
        $rules = [
            'pdf' => 'required|file|mimes:pdf|max:5000',
        ];

        Validator::make($request->all(), $rules)->validate();

        $file = $request->pdf;
        if($file->getSize() > 5000000 || $file->getMimeType() != 'application/pdf') // < 5Mo et PDF
            return redirect()->back();
        return view('superadmin.pdfparser', ['file' => $file]);
    }
}
