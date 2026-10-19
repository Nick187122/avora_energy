<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\UploadedFile;

trait UploadsFiles
{
    protected function storeUpload(UploadedFile $file, string $folder = 'uploads'): string
    {
        $name = uniqid('', true).'-'.preg_replace('/[^A-Za-z0-9.\-_]/', '', $file->getClientOriginalName());
        $path = $file->storeAs($folder, $name, 'public');

        return 'storage/'.$path;
    }
}
