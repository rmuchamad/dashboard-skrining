<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class WilayahController extends Controller
{
    private const BASE_GITHUB = 'https://raw.githubusercontent.com/emsifa/api-wilayah-indonesia/master/static/api/';

    private const BASE_JSdelivr = 'https://cdn.jsdelivr.net/gh/emsifa/api-wilayah-indonesia@master/static/api/';

    public function provinces(): JsonResponse
    {
        return response()->json($this->safeFetch('provinces.json'));
    }

    public function regencies(string $provinceId): JsonResponse
    {
        return response()->json($this->safeFetch('regencies/'.$provinceId.'.json'));
    }

    public function districts(string $regencyId): JsonResponse
    {
        return response()->json($this->safeFetch('districts/'.$regencyId.'.json'));
    }

    public function villages(string $districtId): JsonResponse
    {
        return response()->json($this->safeFetch('villages/'.$districtId.'.json'));
    }

    /**
     * @return list<array<string,mixed>>
     */
    private function safeFetch(string $path): array
    {
        try {
            return $this->fetchCached($path);
        } catch (\Throwable) {
            return [];
        }
    }

    /**
     * @return list<array<string,mixed>>
     */
    private function fetchCached(string $path): array
    {
        // Bump cache version jika format/endpoint berubah (hindari cache "kosong" dari versi lama).
        $key = 'wilayah:v3:'.sha1($path);

        return Cache::remember($key, 60 * 60 * 24, function () use ($path) {
            return $this->fetchRemote($path);
        });
    }

    /**
     * @return list<array<string,mixed>>
     */
    private function fetchRemote(string $path): array
    {
        $candidates = [
            self::BASE_GITHUB.$path,
            self::BASE_JSdelivr.$path,
        ];

        $lastException = null;

        foreach ($candidates as $url) {
            try {
                $response = $this->httpClient()->acceptJson()->get($url);

                if (!$response->successful()) {
                    continue;
                }

                $data = $response->json();
                if (is_array($data)) {
                    return $data;
                }
            } catch (\Throwable $e) {
                $lastException = $e;
            }
        }

        throw $lastException ?? new \RuntimeException('Wilayah fetch failed');
    }

    private function httpClient(): PendingRequest
    {
        $request = Http::timeout(60);

        // Laragon/Windows sering gagal verifikasi SSL ke host publik; aman untuk local dev.
        if (app()->environment('local', 'testing')) {
            $request = $request->withoutVerifying();
        }

        return $request;
    }
}
