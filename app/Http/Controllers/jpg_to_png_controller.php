<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class jpg_to_png_controller extends Controller
{
    public function index()
    {
        return view('pages.convert.jpg_to_png.index');
    }

    public function create()
    {
        $pngFilePath = storage_path('app/public/document_jpg_to_png/background-baru.jpg');
        $jpgFilePath = storage_path('app/public/document_jpg_to_png/background-baru.png');

        $image = Image::make($pngFilePath);
        $image->encode('png', 100)->save($jpgFilePath);

        return $jpgFilePath;
    }
}
