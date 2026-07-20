<?php

declare(strict_types=1);

namespace RunApi\FishAudio\Models;

use RunApi\Core\Models\BaseModel;
use RunApi\Core\Support\Payload;

/** RunAPI-managed MP3 audio result metadata. */
readonly class Audio extends BaseModel
{
    /** @param array<string, mixed> $raw Raw response payload preserved by `toArray()`. */
    public function __construct(
        public string $url,
        public string $format,
        public string $mimeType,
        public int $sizeBytes,
        array $raw = [],
    ) {
        parent::__construct($raw === [] ? [
            'url' => $url,
            'format' => $format,
            'mime_type' => $mimeType,
            'size_bytes' => $sizeBytes,
        ] : $raw);
    }

    /** @param array<string, mixed> $raw */
    public static function fromArray(array $raw): self
    {
        return new self(
            url: Payload::string($raw, 'url'),
            format: Payload::string($raw, 'format'),
            mimeType: Payload::string($raw, 'mime_type'),
            sizeBytes: Payload::int($raw, 'size_bytes'),
            raw: $raw,
        );
    }
}
