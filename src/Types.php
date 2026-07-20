<?php

declare(strict_types=1);

namespace RunApi\FishAudio;

final class Types
{
    /**
     * Allowed model slugs for text to speech requests.
     *
     * @var list<string>
     */
    public const TEXT_TO_SPEECH_MODELS = ['s1', 's2-pro'];

    private function __construct()
    {
    }
}
