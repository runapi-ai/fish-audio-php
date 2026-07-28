<?php

declare(strict_types=1);

namespace RunApi\FishAudio\Resources;

use RunApi\Core\Http\HttpClient;
use RunApi\Core\RequestOptions;
use RunApi\FishAudio\Models\TextToSpeechResponse;

/** Text to speech operations for Fish Audio. */
readonly class TextToSpeech extends SyncResource
{
    /**
     * Run text to speech and return its response.
     *
     * @param array{
     *   text: string,
     *   model?: string,
     *   references?: list<array{audio: string, text: string}>
     * } $params
     */
    public function run(array $params, ?RequestOptions $options = null): TextToSpeechResponse
    {
        $response = parent::run($params, $options);

        /** @var TextToSpeechResponse $response */
        return $response;
    }

    /** Create the resource using the shared RunAPI HTTP transport. */
    public static function fromHttp(HttpClient $http): self
    {
        return new self(
            $http,
            '/api/v1/fish_audio/text_to_speech',
            'fish-audio/text-to-speech',
            TextToSpeechResponse::class,
        );
    }
}
