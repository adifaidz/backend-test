<?php

namespace App\Library\JsonAPI\Repository;

use Illuminate\Support\Facades\Http;

abstract class BaseRepository {
    protected $http;

    public function __construct() {
        $this->http = Http::withOptions([
            'base_uri' => $this->getBaseURL()
        ]);
    }

    public function getBaseURL() {
        return env('JSON_API_BASE_URL');
    }
}
