<?php

namespace App\Http\Controllers;

use App\Http\Requests\createJpgToPngRequest;
use App\Models\{Jpg, Png};
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Ramsey\Uuid\Uuid;

class jpgToPngController extends Controller
{
    public function index()
    {
        return view('pages.convert.jpgToPng.index');
    }

    public function show($saveUuidShowFromRoute)
    {
        $findAndGetDataFile = Png::where('uuid', $saveUuidShowFromRoute)->latest()->get();

        if (!$findAndGetDataFile) {
            abort(404);
        } else {
            return view('pages.convert.jpgToPng.show', compact('findAndGetDataFile'));
        }
    }

    public function create(createJpgToPngRequest $request)
    {
        $validateData = $request->validated();

        $jpgFilePath = $validateData['file']->store('public/document_jpg_to_png');
        $jpgFilePath = str_replace('public/', '', $jpgFilePath);

        $pngFilePath = str_replace('.jpg', '.png', $jpgFilePath);

        $image = Image::make(storage_path('app/public/' . $jpgFilePath));
        $image->encode('png', 100)->save(storage_path('app/public/' . $pngFilePath));

        $dataUuidLocalStorage = $request->uuid;
        $dataFile = Jpg::create([
            'uuid' => $dataUuidLocalStorage ?? Uuid::uuid4()->toString(),
            'name' => $validateData['file']->getClientOriginalName(),
            'file' => $jpgFilePath,
        ]);

        Png::create([
            'jpg_id' => $dataFile->id,
            'uuid' => $dataFile['uuid'],
            'name' => pathinfo($validateData['file']->getClientOriginalName(), PATHINFO_FILENAME) . '.png',
            'file' => 'document_jpg_to_png/' . basename($pngFilePath),
        ]);

        return redirect('/jpg_to_png/'. $dataFile['uuid'] . '/file')->with([
            'uuid' => $dataFile['uuid'],
            'success' => 'File berhasil di convert!'
        ]);
    }

    public function download($saveUuidDownloadFromRoute)
    {
        $png = Png::where('uuid', $saveUuidDownloadFromRoute)->firstOrFail();

        $box = 'storage/' . $png->file;
        $fileName = pathinfo($png->name, PATHINFO_FILENAME) . '.png';

        return response()->download($box, $fileName);
    }

    public function delete($saveUuidDeleteFromRoute)
    {
        $findAndGetFileJpg = Jpg::where('uuid', $saveUuidDeleteFromRoute)->get();
        $findAndGetFilePng = Png::where('uuid', $saveUuidDeleteFromRoute)->get();

        foreach ($findAndGetFileJpg as $resultJpg) {
            if ($resultJpg->file) {
                Storage::delete('public/' . $resultJpg->file);
            }
            $resultJpg->delete();
        }

        foreach ($findAndGetFilePng as $resultPng) {
            if ($resultPng->file) {
                Storage::delete('public/' . $resultPng->file);
            }
            $resultPng->delete();
        }

        Jpg::where('uuid', $saveUuidDeleteFromRoute)->delete();
    }
}
