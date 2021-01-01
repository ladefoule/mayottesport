<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PdfParserController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        Log::info("Accès au controller PdfParser - Ip : " . request()->ip());
    }

    public function get()
    {
        Log::info(" -------- Controller PdfParser : get -------- ");
        return view('admin.pdfparser');
    }

    public function post(Request $request)
    {
        Log::info(" -------- Controller PdfParser : post -------- ");
        PdfParserController::validPosts($request);

        $file = $request->pdf;
        if($file->getSize() > 5000000 || $file->getMimeType() != 'application/pdf') // < 5Mo et PDF
            return redirect()->back();
        return view('admin.pdfparser', ['file' => $file]);
    }

    /**
     * Règles de validations
     *
     * @param Request $request
     * @return void
     */
    private static function validPosts(Request $request)
    {
        $rules = [
            'pdf' => 'required|file|max:5000',
        ];

        $validator = Validator::make($request->all(), $rules);
        $validator->validate();
    }
}
