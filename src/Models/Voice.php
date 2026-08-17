<?php

declare(strict_types=1);

namespace RunApi\FishAudio\Models;

use RunApi\Core\Models\BaseModel;
use RunApi\Core\Support\Payload;

/** Account-owned reusable voice. */
readonly class Voice extends BaseModel
{
    /** @param array<string, mixed> $raw */
    public function __construct(
        public string $voiceId,
        public string $state,
        public ?string $name = null,
        array $raw = [],
    ) {
        parent::__construct($raw === [] ? [
            'voice_id' => $voiceId,
            'name' => $name,
            'state' => $state,
        ] : $raw);
    }

    /** @param array<string, mixed> $raw */
    public static function fromArray(array $raw): self
    {
        return new self(
            voiceId: Payload::string($raw, 'voice_id'),
            state: Payload::string($raw, 'state'),
            name: Payload::optionalString($raw, 'name'),
            raw: $raw,
        );
    }
}
