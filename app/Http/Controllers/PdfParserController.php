<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PdfParserController extends Controller
{
    public function get()
    {
        return view('admin.pdfparser');
    }

    public function post(Request $request)
    {
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
