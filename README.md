# Fish Audio PHP SDK for RunAPI

[![Packagist](https://img.shields.io/packagist/v/runapi-ai/fish-audio)](https://packagist.org/packages/runapi-ai/fish-audio)
[![License](https://img.shields.io/github/license/runapi-ai/fish-audio-php)](https://github.com/runapi-ai/fish-audio-php/blob/main/LICENSE)

Use the Fish Audio PHP SDK to create account-owned voice resources and generate
speech through RunAPI with a synchronous Composer client and typed responses.

## Install

```bash
composer require runapi-ai/fish-audio
```

## Quick start

```php
<?php

require __DIR__ . "/vendor/autoload.php";

use RunApi\FishAudio\FishAudioClient;

$client = new FishAudioClient(); // reads RUNAPI_API_KEY
$created = $client->createVoice->run([
    'name' => 'Narrator',
    'source_audio_url' => 'https://cdn.runapi.ai/public/samples/voice.mp3',
]);
$voice = $client->getVoice->run(['voice_id' => $created->voice->voiceId]);
if ($voice->voice->state !== 'trained') {
    throw new RuntimeException("Voice is {$voice->voice->state}");
}

$result = $client->textToSpeech->run([
    'model' => 's2.1-pro',
    'text' => 'Hello from RunAPI [excited]',
    'voice_id' => $created->voice->voiceId,
    'output_format' => 'wav',
    'sample_rate_hz' => 44100,
]);

echo $result->audios[0]->url . PHP_EOL;
```

Pass request parameters as associative arrays with snake_case keys. Keep
`RUNAPI_API_KEY` in the environment or your secret manager.

Use `$client->listVoices->run(['page_number' => 1, 'page_size' => 10])` to list
voice resources created by the current account. Only voices in the `trained`
state can be submitted for speech generation; a returned `voice_id` is a
best-effort reference and may stop working later.

Speech and voice requests return their terminal responses directly when
available and otherwise follow an accepted Task automatically. Use `subscribe()`
to observe Task updates.

Alternatively, pass request-scoped `references` entries with base64-encoded raw
audio bytes and exact transcripts when the request must not depend on prior reuse.

The output defaults to MP3. MP3 supports `32000` or `44100` Hz and optional
`bitrate_kbps` values of `64`, `128`, or `192`. WAV supports `8000`, `16000`,
`24000`, `32000`, or `44100` Hz and does not accept `bitrate_kbps`.

The supported models are `s1`, `s2-pro`, and `s2.1-pro`. Update, delete,
revoke, or voice-library management methods are not provided; voice retention
and source-audio training behavior are not promised.

## Links

- Model page: https://runapi.ai/models/fish-audio
- SDK docs: https://runapi.ai/docs/resources/sdks
- Product docs: https://runapi.ai/docs/api/fish-audio/text-to-speech
- Pricing and rate limits: https://runapi.ai/models/fish-audio/s2.1-pro
- Full catalog: https://runapi.ai/models
- GitHub repository: https://github.com/runapi-ai/fish-audio-php
- Multi-language SDK repository: https://github.com/runapi-ai/fish-audio-sdk

## License

Licensed under the Apache License, Version 2.0.
