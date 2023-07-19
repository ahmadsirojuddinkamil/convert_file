<?php

namespace App\Services;

use App\Models\Jpg;
use App\Models\Png;
use Illuminate\Support\Facades\Storage;

class DeleteFileService
{
    public function deleteAllFile($saveUuidFromCallRequest)
    {
        $findAndGetFileJpg = Jpg::FindJpgByUuid($saveUuidFromCallRequest)->get();
        $findAndGetFilePng = Png::FindPngByUuid($saveUuidFromCallRequest)->get();

        if ($findAndGetFileJpg) {
            foreach ($findAndGetFileJpg as $resultJpg) {
                if ($resultJpg->file) {
                    Storage::delete('public/' . $resultJpg->file);
                }
                $resultJpg->delete();
            }

            Jpg::FindJpgByUuid($saveUuidFromCallRequest)->delete();
        }

        if ($findAndGetFilePng) {
            foreach ($findAndGetFilePng as $resultPng) {
                if ($resultPng->file) {
                    Storage::delete('public/' . $resultPng->file);
                }
                $resultPng->delete();
            }

            Png::FindPngByUuid($saveUuidFromCallRequest)->delete();
        }
    }
}
