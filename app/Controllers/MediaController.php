<?php

namespace App\Controllers;

use Library\Framework\Http\Request;
use Library\Framework\Http\Response;

class MediaController
{
    public function serve(Request $request)
    {
        $disk = $request->input('disk') ?? '';
        $path = $request->input('path') ?? '';
        $expires = $request->input('expires') ?? '';
        $sig = $request->input('sig') ?? '';

        $valid = storage()->validateTempUrl(
            $disk,
            $path,
            $expires,
            $sig
        );

        if ($valid) {
            $fullPath = storage()->getFullPath('local', $path);
            return (new Response())->file($fullPath);
        }

        return view('error/404');
    }
}