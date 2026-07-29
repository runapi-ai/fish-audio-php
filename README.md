# Fish Audio PHP SDK for RunAPI

[![Packagist](https://img.shields.io/packagist/v/runapi-ai/fish-audio)](https://packagist.org/packages/runapi-ai/fish-audio)
[![License](https://img.shields.io/github/license/runapi-ai/fish-audio-php)](https://github.com/runapi-ai/fish-audio-php/blob/main/LICENSE)

Use the Fish Audio PHP SDK to generate speech through RunAPI with a
synchronous Composer client and typed managed-audio responses.

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
$result = $client->textToSpeech->run([
    'model' => 's1',
    'references' => [['audio' => 'UklGRg==', 'text' => 'Reference transcript']],
    'text' => 'A product render',
]);

echo $result->audios[0]->url . PHP_EOL;
```

Pass request parameters as associative arrays with snake_case keys. Keep
`RUNAPI_API_KEY` in the environment or your secret manager.

## Links

- Model page: https://runapi.ai/models/fish-audio
- SDK docs: https://runapi.ai/docs/resources/sdks
- Product docs: https://runapi.ai/docs/api/fish-audio/text-to-speech
- Pricing and rate limits: https://runapi.ai/models/fish-audio/s1
- Full catalog: https://runapi.ai/models
- GitHub repository: https://github.com/runapi-ai/fish-audio-php
- Multi-language SDK repository: https://github.com/runapi-ai/fish-audio-sdk

## License

Licensed under the Apache License, Version 2.0.
