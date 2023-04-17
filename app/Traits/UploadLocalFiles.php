<?php
namespace App\Traits;

use Illuminate\Http\Request;

trait UploadLocalFiles
{
    public function storeDocument(Request $request)
    {
        $name = uniqid(date('HisYmd'));
        $extension = $request->document->extension();
        $nameFile = "{$name}.{$extension}";
        $upload = $request->document->storeAs('documents', $nameFile);

        return ['ok' => $upload, 'nameFile' => $nameFile];
    }
}
