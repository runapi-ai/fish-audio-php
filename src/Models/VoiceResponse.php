<?php

declare(strict_types=1);

namespace RunApi\FishAudio\Models;

use RunApi\Core\Models\BaseModel;
use RunApi\Core\Support\Payload;

/** Response containing one reusable voice. */
readonly class VoiceResponse extends BaseModel
{
    /** @param array<string, mixed> $raw */
    public function __construct(public Voice $voice, array $raw = [])
    {
        parent::__construct($raw === [] ? ['voice' => $voice->toArray()] : $raw);
    }

    /** @param array<string, mixed> $raw */
    public static function fromArray(array $raw): self
    {
        return new self(
            voice: Voice::fromArray(Payload::array($raw, 'voice')),
            raw: $raw,
        );
    }
}
