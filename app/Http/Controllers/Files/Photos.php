<?php

namespace Mnemosine\Http\Controllers\Files;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Mnemosine\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Mnemosine\Photography;
use Image;

class Photos extends Controller
{
    /**
    * Tasks related to save image to disks
    *
    * @param \Illuminate\Http\Request File $photoFile
    * @param String $module
    * @return string Path where the image was saved
    */
    public function savePhotoToDisk($photoFile, $module){
        $pathOriginals = config('fileuploads.'. $module .'.photographs.originals');
        $pathThumbnails = config('fileuploads.'. $module .'.photographs.thumbnails');

        // save into the local public disk
        $path = $photoFile->store($pathOriginals);
        $fileName = str_after($path, $pathOriginals . '/');

        if(config('fileuploads.cloud.backup')){
            // save to amazon s3
            Storage::disk('s3')->put($pathOriginals, $photoFile);
        }

        // generate image thumbnail
        $fullPathOriginal = storage_path() . '/app/public/' . $pathOriginals . '/' . $fileName;
        $fullPathThumbnail = storage_path() . '/app/public/' . $pathThumbnails . '/' . $fileName;
        $img = Image::make($fullPathOriginal)->widen(config('fileuploads.'. $module .'.photographs.thumbnail_width'), function ($constraint) {
            $constraint->upsize();
        });
        // save the thumbnail
        $img->save($fullPathThumbnail);

        return $fileName;
    }

    public function deletePhotoFromDisk($fileName, $module){
        $pathOriginal = config('fileuploads.'. $module .'.photographs.originals');
        $pathThumbnail = config('fileuploads.'. $module .'.photographs.thumbnails');
        $pathTrashed = config('fileuploads.'. $module .'.photographs.trashed');

        // move to thash in local disk
        Storage::move($pathOriginal . '/' . $fileName, $pathTrashed . '/' . $fileName);

        // delete thumbnail in local disk
        Storage::delete($pathThumbnail . '/' . $fileName);

        if(config('fileuploads.cloud.backup')){
            // delete from amazon s3
            Storage::disk('s3')->delete($pathOriginal . '/' . $fileName);
        }
    }

    public function setPhotoValues(Photography $photography, Request $request, $idx, $piece_id){
        if(isset($request->photo_date[$idx])) {
            $photography->photographed_at = Carbon::parse($request->photo_date[$idx])->timestamp;
        }

        if(isset($request->file('photo_file')[$idx])){
            $photography->mime_type = $request->file('photo_file')[$idx]->getClientMimeType();
            $photography->size = $request->file('photo_file')[$idx]->getClientSize(); // en Kb
        }
        $photography->piece_id = $piece_id;

        $photography->photographer = $request->photo_author[$idx];
        $photography->description = $request->photo_description[$idx];
    }
}
