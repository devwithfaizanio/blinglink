<?php

namespace App\Http\Controllers\web\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CkeditorController extends Controller
{
    public function upload(Request $request)
    {
//        if($request->hasFile('upload')) {
//            $originName = $request->file('upload')->getClientOriginalName();
//            $fileName = pathinfo($originName, PATHINFO_FILENAME);
//            $extension = $request->file('upload')->getClientOriginalExtension();
//            $fileName = $fileName.'_'.time().'.'.$extension;
//
//            $request->file('upload')->move(public_path('uploads'), $fileName);
//
//            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
//            $url = asset('public/uploads/'.$fileName);
        ////            dd($url);
//            $msg = 'Image uploaded successfully';
//            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";
//
//            @header('Content-type: text/html; charset=utf-8');
//            echo $response;
//        }
        if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName.'_'.time().'.'.$extension;

            $request->file('upload')->move(public_path('media'), $fileName);

            $url = asset('media/'.$fileName);

            return response()->json(['fileName' => $fileName, 'uploaded' => 1, 'url' => $url]);
        }
    }
}
