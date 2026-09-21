<?php

namespace App\Services\Asaas;

use Illuminate\Support\Facades\Http;

class AsaasClient
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function request()
    {
        return Http::baseUrl(config('services.asaas.base_url'))
            ->withHeaders([
                'access_token' => config('services.asaas.key'),
                'User-Agent' => 'Gabometro',
                'Content-Type' => 'application/json',
            ])
            ->acceptJson()
            ->throw();
    }
}
