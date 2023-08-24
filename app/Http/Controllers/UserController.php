<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Service\HelperService;

class UserController extends Controller
{
    public function DetectFaces(Request $request, HelperService $helperService){
        $request->validate([
            'images.*' => 'image|mimes:jpeg,png,jpg|max:6000', // Validasi format dan ukuran gambar
        ]);

        if ($request->hasFile('images')) {
            $FacesUrl = $helperService->UploadImages($request->file('images'));
            $DetectFaces = $helperService->DetectFaces($FacesUrl);

            return response()->json([
                'message' => 'Detection Success',
                'images' => $DetectFaces,
            ]);
        }

        return response()->json([
            'message' => 'Tidak ada gambar yang diunggah',
        ], 400);
    }
}
