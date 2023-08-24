<?php

namespace App\Service;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class HelperService{
    public function UploadImages($images){
        $uploadedImages = [];

        foreach ($images as $image) {
            $path = $image->store('images'); // Menyimpan gambar ke penyimpanan Azure dengan folder "images"

            // Menyimpan path gambar ke array
            $uploadedImages[] = [
                'path' => $path,
                'url' => Storage::disk('local')->url($path),
            ];
        }

        dd($uploadedImages);
        return $uploadedImages;
    }

    public function DetectFaces($url_images){
        $key = env('AZURE_FACE_API_KEY');
        $url = env('AZURE_FACE_API_ENDPOINT');
        // dd($url);

        foreach ($url_images as $index => $url_image) {
            $response ["images_". ($index + 1)] = Http::withHeaders([
                'Content-Type: application/json',
                'Accept' => 'application/json',
                'Ocp-Apim-Subscription-Key' => $key,
            ])
            ->post($url . "/face/v1.0/detect?returnFaceAttributes=qualityForRecognition,accessories&recognitionModel=recognition_04", [
                'url' => $url_image['url'],
            ])[0];
        }

        return $response;
    }

    public function VerifyFace($faceid1, $faceid2){
        $key = env('AZURE_FACE_API_KEY'); //secretkey, get from .env
        $url = env('AZURE_FACE_API_ENDPOINT'); //endpoint api azure, get from .env

            $response = Http::withHeaders([
                'Content-Type: application/json',
                'Accept' => 'application/json',
                'Ocp-Apim-Subscription-Key' => $key,
            ])
            ->post($url . "/face/v1.0/verify", [
                'faceId1' => $faceid1, //face id1
                'faceId2' => $faceid2, //face id2
            ])[0];

        return $response;
    }
    
}