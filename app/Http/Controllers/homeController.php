<?php

namespace App\Http\Controllers;

use App\Services\DeleteFileService;

class homeController extends Controller
{
    public function index()
    {
        return view('pages.home.index');
    }

    public function deleteAllFile($saveUuidDeleteFromRoute, DeleteFileService $serviceDelete)
    {
        return $serviceDelete->deleteAllFile($saveUuidDeleteFromRoute);
    }
}
