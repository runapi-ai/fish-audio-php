<?php

declare(strict_types=1);

namespace RunApi\FishAudio\Models;

use RunApi\Core\Models\BaseModel;
use RunApi\Core\Models\TaskBillingFacts;
use RunApi\Core\Support\Payload;

/** Completed synchronous text-to-speech response. */
readonly class TextToSpeechResponse extends BaseModel
{
    public ?TaskBillingFacts $billing;

    /**
     * @param list<Audio> $audios
     * @param array<string, mixed> $raw Raw response payload preserved by `toArray()`.
     */
    public function __construct(
        public string $id,
        public string $status,
        public array $audios,
        public ?string $error = null,
        array $raw = [],
        ?TaskBillingFacts $billing = null,
    ) {
        $this->billing = $billing ?? self::billing($raw);
        parent::__construct($raw === [] ? [
            'id' => $id,
            'status' => $status,
            'audios' => array_map(static fn (Audio $audio): array => $audio->toArray(), $audios),
            'error' => $error,
            'billing' => $this->billing?->toArray(),
        ] : $raw);
    }

    /** @param array<string, mixed> $raw */
    public static function fromArray(array $raw): self
    {
        return new self(
            id: Payload::string($raw, 'id'),
            status: Payload::string($raw, 'status'),
            audios: Payload::listOf($raw, 'audios', Audio::fromArray(...), required: true),
            error: Payload::optionalString($raw, 'error'),
            raw: $raw,
        );
    }

    /** @param array<string, mixed> $raw */
    private static function billing(array $raw): ?TaskBillingFacts
    {
        return isset($raw['billing']) && is_array($raw['billing']) ? TaskBillingFacts::fromArray($raw['billing']) : null;
    }
}
