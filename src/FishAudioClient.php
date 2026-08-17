<?php

declare(strict_types=1);

namespace RunApi\FishAudio;

use RunApi\Core\BaseClient;
use RunApi\Core\ClientOptions;
use RunApi\FishAudio\Resources\CreateVoice;
use RunApi\FishAudio\Resources\GetVoice;
use RunApi\FishAudio\Resources\ListVoices;
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
    /** Account-owned reusable voice creation operations. */
    public readonly CreateVoice $createVoice;
    /** Account-owned reusable voice listing operations. */
    public readonly ListVoices $listVoices;
    /** Account-owned reusable voice lookup operations. */
    public readonly GetVoice $getVoice;

    /** Create a Fish Audio client with optional API key, base URL, and transport overrides. */
    public function __construct(ClientOptions $options = new ClientOptions())
    {
        parent::__construct($options);
        $this->textToSpeech = TextToSpeech::fromHttp($this->http);
        $this->createVoice = CreateVoice::fromHttp($this->http);
        $this->listVoices = ListVoices::fromHttp($this->http);
        $this->getVoice = GetVoice::fromHttp($this->http);
    }
}
