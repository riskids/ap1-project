<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\HelperService;
use App\Http\Requests\VerifyFacesRequest;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function DetectFaces(Request $request, HelperService $helperService){
        $request->validate([
            'images.*' => 'image|mimes:jpeg,png,jpg|max:6000', // Validasi format dan ukuran gambar
        ]);

        if ($request->hasFile('images')) {
            $FacesUrl = $helperService->UploadImages($request->file('images'));

            //for test jmeter
            return response()->json(['message' => 'OK', 'data' => $FacesUrl], Response::HTTP_OK);

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

    public function VerifyFaces(VerifyFacesRequest $request, HelperService $helperService, ) 
    {
        $response = $helperService->VerifyFace($request->faceId1, $request->faceId2);

        if ($response->successful()) {
            return response()->json([
                'message' => 'Verify Faces Success',
                'response' => $response->json(),
            ]);
        }else{
            return  $response->json();
        }
    }
}
