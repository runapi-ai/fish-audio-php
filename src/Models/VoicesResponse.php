<?php

declare(strict_types=1);

namespace RunApi\FishAudio\Models;

use RunApi\Core\Models\BaseModel;
use RunApi\Core\Support\Payload;

/** Paginated response containing account-owned reusable voices. */
readonly class VoicesResponse extends BaseModel
{
    /**
     * @param list<Voice> $voices
     * @param array<string, mixed> $raw
     */
    public function __construct(
        public array $voices,
        public int $total,
        public int $pageNumber,
        public int $pageSize,
        array $raw = [],
    ) {
        parent::__construct($raw === [] ? [
            'voices' => array_map(static fn (Voice $voice): array => $voice->toArray(), $voices),
            'total' => $total,
            'page_number' => $pageNumber,
            'page_size' => $pageSize] : $raw);
    }

    /** @param array<string, mixed> $raw */
    public static function fromArray(array $raw): self
    {
        return new self(
            voices: Payload::listOf($raw, 'voices', Voice::fromArray(...), required: true),
            total: Payload::int($raw, 'total'),
            pageNumber: Payload::int($raw, 'page_number'),
            pageSize: Payload::int($raw, 'page_size'),
            raw: $raw,
        );
    }
}
