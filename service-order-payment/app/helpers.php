<?php

use Illuminate\Support\Facades\Http;

function createPremiumAccess($data)
{
    $url = env('SERVICE_COURSE_URL').'api/my-courses/premium';
    // dd($url);

    try {
        $response = Http::post($url, $data);
        $data = $response->json();
        $data['status_code'] = $response->getStatusCode();
        return $data;
    } catch (\Throwable $th) {
        return [
            'status' => 'error',
            'http_code' => 500,
            'message' => 'Service mentor unavailable'
        ];
    }
}