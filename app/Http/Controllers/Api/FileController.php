<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FileRequest;
use App\Http\Resources\FileResource;
use App\Models\File;
use App\Models\Folder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return FileResource::collection(File::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FileRequest $request)
    {
        $validated = $request->validated();
        $folder = Folder::find($validated['folder_id']);
        if (!Storage::disk('local')->exists('public/'.$validated['user_id'].'/'.$folder['title'])) {
            return response()->json([
                'error' => 'Folder does not exist or you don\'t have any folders'
            ], 400);
        }

        Storage::disk('local')->put('public/'.$validated['user_id'].'/'.$folder['title'], $validated['file']);

        $validated['type'] = $validated['file']->extension();
        if (!isset($validated['type'])) { $validated['type'] = 'Unknown'; }

        if ($validated['file']->getSize() / (1024 * 1024 * 1024) >= 1) {
            $validated['size'] = round($validated['file']->getSize() / (1024 * 1024 * 1024)).' GB';
        } elseif ($validated['file']->getSize() / (1024 * 1024) >= 1) {
            $validated['size'] = round($validated['file']->getSize() / (1024 * 1024)).' MB';
        } elseif ($validated['file']->getSize() / 1024 >= 1) {
            $validated['size'] = round($validated['file']->getSize() / 1024).' KB';
        } else {
            $validated['size'] = round($validated['file']->getSize()).' B';
        }
        $validated['file'] = $validated['file']->hashName();
        $createdFile = File::create($validated);
        return new FileResource($createdFile);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(File $file)
    {
        return new FileResource($file);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, File $file)
    {
        $validated = $request->validate([
            'title' => '',
            'description' => ''
        ]);

        if ($validated['title'] == $file['title'] && $validated['description'] == $file['description']) {
            return response()->json([
                'error' => 'Title and description are the same'
            ], 400);
        }

        $file->update($validated);
        return new FileResource($file);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(File $file)    {
        $file->delete();
        $folder = Folder::find($file['folder_id']);
        Storage::disk('local')->delete($folder->folder_location.'/'.$file->file);
        return new FileResource($file);
    }

    public function getUserFiles (Folder $folder) {
        $files = File::where('user_id', auth()->user()->id)
            ->where('folder_id', $folder->id)
            ->get();
        return FileResource::collection($files);
    }

    public function getFile (Request $request, File $file)
    {
        if(!$request->hasValidSignature()) return abort(401);
        $folder = Folder::find($file->folder_id);
        return Storage::disk('local')->response($folder->folder_location.'/'.$file->file);
    }
}
