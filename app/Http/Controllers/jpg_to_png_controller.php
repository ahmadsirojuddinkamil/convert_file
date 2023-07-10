<?php

namespace App\Http\Controllers;

use App\Http\Requests\create_jpg_to_png_request;
use App\Models\{Jpg, Png};
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Ramsey\Uuid\Uuid;

class jpg_to_png_controller extends Controller
{
    public function index()
    {
        return view('pages.convert.jpg_to_png.index');
    }

    public function show($save_uuid_show_from_route)
    {
        $find_and_get_data_file = Png::where('uuid', $save_uuid_show_from_route)->latest()->get();

        if (!$find_and_get_data_file) {
            abort(404);
        } else {
            return view('pages.convert.jpg_to_png.show', compact('find_and_get_data_file'));
        }
    }

    public function create(create_jpg_to_png_request $request)
    {
        $validate_data = $request->validated();

        $jpg_file_path = $validate_data['file']->store('public/document_jpg_to_png');
        $jpg_file_path = str_replace('public/', '', $jpg_file_path);

        $png_file_path = str_replace('.jpg', '.png', $jpg_file_path);

        $image = Image::make(storage_path('app/public/' . $jpg_file_path));
        $image->encode('png', 100)->save(storage_path('app/public/' . $png_file_path));

        $data_uuid_local_storage = $request->uuid;
        $data_file = Jpg::create([
            'uuid' => $data_uuid_local_storage ?? Uuid::uuid4()->toString(),
            'name' => $validate_data['file']->getClientOriginalName(),
            'file' => $jpg_file_path,
        ]);

        Png::create([
            'jpg_id' => $data_file->id,
            'uuid' => $data_file['uuid'],
            'name' => pathinfo($validate_data['file']->getClientOriginalName(), PATHINFO_FILENAME) . '.png',
            'file' => 'document_jpg_to_png/' . basename($png_file_path),
        ]);

        return redirect('/jpg_to_png/'. $data_file['uuid'] . '/file')->with([
            'uuid' => $data_file['uuid'],
            'success' => 'File berhasil di convert!'
        ]);
    }

    public function download($save_uuid_download_from_route)
    {
        $find_png_from_uuid = Png::where('uuid', $save_uuid_download_from_route)->pluck('file');
        $find_png_from_uuid = str_replace(['[', ']', '"'], '', $find_png_from_uuid);
        $box = 'storage/' . $find_png_from_uuid;
        return response()->download($box);
    }

    public function delete($save_uuid_delete_from_route)
    {
        $find_and_get_file_jpg = Jpg::where('uuid', $save_uuid_delete_from_route)->get();
        $find_and_get_file_png = Png::where('uuid', $save_uuid_delete_from_route)->get();

        foreach ($find_and_get_file_jpg as $result_jpg) {
            if ($result_jpg->file) {
                Storage::delete('public/' . $result_jpg->file);
            }
            $result_jpg->delete();
        }

        foreach ($find_and_get_file_png as $result_png) {
            if ($result_png->file) {
                Storage::delete('public/' . $result_png->file);
            }
            $result_png->delete();
        }

        Jpg::where('uuid', $save_uuid_delete_from_route)->delete();
    }
}
