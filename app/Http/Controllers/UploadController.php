<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadController extends DefaultController
{

    public function index()
    {
        return view('admin.upload.index');
    }

    public function uploadChunk(Request $request)
    {
        dd($request);
    }
}
