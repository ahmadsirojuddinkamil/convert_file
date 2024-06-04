<?php

namespace Modules\Monitoring\App\Http\Controllers;

use App\Http\Controllers\Controller;

class StorageLinkController extends Controller
{
    public function generateStorageLink()
    {
        $randomString = shell_exec('openssl rand -hex 32');
        $hashedUuid = hash('sha256', $randomString);
        return $hashedUuid;

        // if (!file_exists($_SERVER['DOCUMENT_ROOT'].'/storage')) {
        //     $targetFolder = base_path().'/storage/app/public';
        //     $linkFolder = $_SERVER['DOCUMENT_ROOT'].'/storage';
        //     symlink($targetFolder, $linkFolder);

        //     return response()->json(['message' => 'success create storage link!'], 200);
        // }

        // return abort(404);
    }
}
