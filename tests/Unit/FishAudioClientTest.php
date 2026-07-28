<?php

declare(strict_types=1);

namespace RunApi\FishAudio\Tests\Unit;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use RunApi\Core\ClientOptions;
use RunApi\Core\Errors\ValidationException;
use RunApi\Core\Tests\Fixtures\QueueHttpClient;
use RunApi\FishAudio\FishAudioClient;
use RunApi\FishAudio\Models\TextToSpeechResponse;
use RunApi\FishAudio\Resources\TextToSpeech;

final class FishAudioClientTest extends TestCase
{
    public function testExposesTypedSynchronousResource(): void
    {
        $client = new FishAudioClient(new ClientOptions(apiKey: 'k', httpClient: new QueueHttpClient([]), maxRetries: 0));

        self::assertInstanceOf(TextToSpeech::class, $client->textToSpeech);
    }

    public function testRunPostsOnlyPublicParamsAndReturnsManagedAudio(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1","status":"completed","audios":[{"url":"https://runapi.ai/audio.mp3","format":"mp3","mime_type":"audio/mpeg","size_bytes":128}],"billing":{"settlement":{"charged_amount_cents":11,"amount_micro_cents":1050000}},"extra_field":"kept"}'),
        ]);
        $client = new FishAudioClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $result = $client->textToSpeech->run([
            'model' => 's1',
            'text' => 'A product render',
            'references' => [['audio' => 'UklGRg==', 'text' => 'Reference transcript']],
        ]);

        $body = json_decode((string) $transport->requests[0]->getBody(), true, flags: JSON_THROW_ON_ERROR);

        self::assertInstanceOf(TextToSpeechResponse::class, $result);
        self::assertSame('completed', $result->status);
        self::assertSame('audio/mpeg', $result->audios[0]->mimeType);
        self::assertSame(128, $result->audios[0]->sizeBytes);
        self::assertSame(11, $result->billing?->settlement?->chargedAmountCents);
        self::assertSame('kept', $result->toArray()['extra_field']);
        self::assertSame([
            'model' => 's1',
            'text' => 'A product render',
            'references' => [['audio' => 'UklGRg==', 'text' => 'Reference transcript']],
        ], $body);
        self::assertSame('/api/v1/fish_audio/text_to_speech', $transport->requests[0]->getUri()->getPath());
    }

    public function testRunRequiresManagedAudioMetadata(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1","status":"completed","audios":[{"url":"https://runapi.ai/audio.mp3"}]}'),
        ]);
        $client = new FishAudioClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('format must be a string');

        $client->textToSpeech->run([
            'model' => 's1',
            'text' => 'Hello from RunAPI',
        ]);
    }
}
