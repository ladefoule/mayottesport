<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadFileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function imageUpload()
    {
        return view('admin.upload-image');
    }

    public function imageUploadPost(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'nom' => 'required|alpha_dash|min:5|max:50'
        ]);

        $imageName = $request->nom . '-' .time().'.'.$request->image->extension();  
        $request->image->move(storage_path('app/public/upload/img'), $imageName);
        return back()
            ->with('success','You have successfully upload image.')
            ->with('image',$imageName);
    }
}