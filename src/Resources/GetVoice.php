<?php

declare(strict_types=1);

namespace RunApi\FishAudio\Resources;

use RunApi\Core\Errors\ValidationException;
use RunApi\Core\Http\HttpClient;
use RunApi\Core\RequestOptions;
use RunApi\FishAudio\Models\VoiceResponse;

/** Account-owned reusable voice lookup operations. */
readonly class GetVoice extends SyncResource
{
    /** @param array{voice_id?: mixed} $params */
    public function run(array $params, ?RequestOptions $options = null): VoiceResponse
    {
        $voiceId = $params['voice_id'] ?? null;
        if (!is_string($voiceId)) {
            throw new ValidationException('voice_id must be a string');
        }
        $path = '/api/v1/fish_audio/voices/' . rawurlencode($voiceId);
        $response = $this->execute($params, $options, method: 'get', path: $path, placement: 'none');

        /** @var VoiceResponse $response */
        return $response;
    }

    public static function fromHttp(HttpClient $http): self
    {
        return new self($http, '/api/v1/fish_audio/voices', 'fish-audio/get-voice', VoiceResponse::class);
    }
}
