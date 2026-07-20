<?php

declare(strict_types=1);

namespace RunApi\FishAudio;

use RunApi\Core\BaseClient;
use RunApi\Core\ClientOptions;
use RunApi\FishAudio\Resources\TextToSpeech;

/**
 * Fish Audio RunAPI PHP client.
 *
 * The client exposes typed model resources plus the universal `files` and
 * `account` resources.
 */
final class FishAudioClient extends BaseClient
{
    /** Text to speech operations for Fish Audio. */
    public readonly TextToSpeech $textToSpeech;

    /** Create a Fish Audio client with optional API key, base URL, and transport overrides. */
    public function __construct(ClientOptions $options = new ClientOptions())
    {
        parent::__construct($options);
        $this->textToSpeech = TextToSpeech::fromHttp($this->http);
    }
}
