<?php

declare(strict_types=1);

namespace RunApi\FishAudio\Resources;

use RunApi\Core\Http\HttpClient;
use RunApi\Core\RequestOptions;
use RunApi\FishAudio\Models\VoicesResponse;

/** Account-owned reusable voice listing operations. */
readonly class ListVoices extends SyncResource
{
    /** @param array{page_number?: int, page_size?: int} $params */
    public function run(array $params = [], ?RequestOptions $options = null): VoicesResponse
    {
        $response = $this->execute($params, $options, method: 'get', placement: 'query');

        /** @var VoicesResponse $response */
        return $response;
    }

    public static function fromHttp(HttpClient $http): self
    {
        return new self($http, '/api/v1/fish_audio/voices', 'fish-audio/list-voices', VoicesResponse::class);
    }
}
