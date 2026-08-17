<?php

declare(strict_types=1);

namespace RunApi\FishAudio\Resources;

use RunApi\Core\Http\HttpClient;
use RunApi\Core\RequestOptions;
use RunApi\FishAudio\Models\VoiceResponse;

/** Account-owned reusable voice creation operations. */
readonly class CreateVoice extends SyncResource
{
    /** @param array{name: string, source_audio_url: string} $params */
    public function run(array $params, ?RequestOptions $options = null): VoiceResponse
    {
        $response = parent::run($params, $options);

        /** @var VoiceResponse $response */
        return $response;
    }

    public static function fromHttp(HttpClient $http): self
    {
        return new self($http, '/api/v1/fish_audio/voices', 'fish-audio/create-voice', VoiceResponse::class);
    }
}
