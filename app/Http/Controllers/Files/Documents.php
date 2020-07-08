<?php

namespace Mnemosine\Http\Controllers\Files;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Mnemosine\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Mnemosine\Document;

class Documents extends Controller
{
    /**
    * Tasks related to save document to disks
    *
    * @param \Illuminate\Http\Request File $documentFile
    * @param String $module
    * @return string Path where the document was saved
    */
    public function saveDocumentToDisk($documentFile, $module){
        $pathOriginals = config('fileuploads.'. $module .'.documents.originals');

        // save into the local public disk
        $path = $documentFile->store($pathOriginals);
        $fileName = str_after($path, $pathOriginals . '/');

        if(config('fileuploads.cloud.backup')){
            // save to amazon s3
            Storage::disk('s3')->put($pathOriginals, $documentFile);
        }

        return $fileName;
    }

    public function deleteDocumentFromDisk($fileName, $module){
        $pathOriginal = config('fileuploads.'. $module .'.documents.originals');
        $pathTrashed = config('fileuploads.'. $module .'.documents.trashed');

        // move to thash in local disk
        Storage::move($pathOriginal . '/' . $fileName, $pathTrashed . '/' . $fileName);

        if(config('fileuploads.cloud.backup')){
            // delete from amazon s3
            Storage::disk('s3')->delete($pathOriginal . '/' . $fileName);
        }
    }

    public function setDocumentValues(Document $document, Request $request, $idx, $piece_id){
        if(isset($request->file('document_file')[$idx])){
            $document->mime_type = $request->file('document_file')[$idx]->getClientMimeType();
            $document->size = $request->file('document_file')[$idx]->getClientSize(); // en Kb
        }
        $document->piece_id = $piece_id;
        $document->name = $request->document_name[$idx];
    }
}
