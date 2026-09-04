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
use RunApi\FishAudio\Models\VoiceResponse;
use RunApi\FishAudio\Models\VoicesResponse;
use RunApi\FishAudio\Resources\CreateVoice;
use RunApi\FishAudio\Resources\GetVoice;
use RunApi\FishAudio\Resources\ListVoices;
use RunApi\FishAudio\Resources\TextToSpeech;

final class FishAudioClientTest extends TestCase
{
    public function testExposesTypedSynchronousResource(): void
    {
        $client = new FishAudioClient(new ClientOptions(apiKey: 'k', httpClient: new QueueHttpClient([]), maxRetries: 0));

        self::assertInstanceOf(TextToSpeech::class, $client->textToSpeech);
        self::assertInstanceOf(CreateVoice::class, $client->createVoice);
        self::assertInstanceOf(ListVoices::class, $client->listVoices);
        self::assertInstanceOf(GetVoice::class, $client->getVoice);
    }

    public function testRunPostsOnlyPublicParamsAndReturnsManagedAudio(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1","status":"completed","audios":[{"url":"https://runapi.ai/audio.mp3","format":"mp3","mime_type":"audio/mpeg","size_bytes":128}],"billing":{"settlement":{"charged_amount_cents":11,"amount_micro_cents":1050000}},"extra_field":"kept"}'),
        ]);
        $client = new FishAudioClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $result = $client->textToSpeech->run([
            'model' => 's2.1-pro',
            'text' => 'A product render',
            'output_format' => 'wav',
            'sample_rate_hz' => 24000,
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
            'model' => 's2.1-pro',
            'text' => 'A product render',
            'output_format' => 'wav',
            'sample_rate_hz' => 24000,
            'references' => [['audio' => 'UklGRg==', 'text' => 'Reference transcript']],
        ], $body);
        self::assertSame('/api/v1/fish_audio/text_to_speech', $transport->requests[0]->getUri()->getPath());
    }

    public function testRunPostsReusableVoiceId(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1","status":"completed","audios":[]}'),
        ]);
        $client = new FishAudioClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $client->textToSpeech->run([
            'model' => 's1',
            'text' => 'Hello from RunAPI',
            'voice_id' => 'voice_1',
        ]);

        $body = json_decode((string) $transport->requests[0]->getBody(), true, flags: JSON_THROW_ON_ERROR);
        self::assertSame(['model' => 's1', 'text' => 'Hello from RunAPI', 'voice_id' => 'voice_1'], $body);
    }

    public function testCreatesPrivateReusableVoice(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"voice":{"voice_id":"voice_1","name":"Narrator","state":"training"},"billing":{"reservation":null,"settlement":{"charged_amount_cents":0,"amount_micro_cents":0},"refund":null}}'),
        ]);
        $client = new FishAudioClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $result = $client->createVoice->run([
            'name' => 'Narrator',
            'source_audio_url' => 'https://cdn.runapi.ai/narrator.mp3',
        ]);

        self::assertInstanceOf(VoiceResponse::class, $result);
        self::assertSame('training', $result->voice->state);
        self::assertSame(0, $result->billing->settlement?->amountMicroCents);
        self::assertSame('/api/v1/fish_audio/voices', $transport->requests[0]->getUri()->getPath());
        self::assertSame('POST', $transport->requests[0]->getMethod());
    }

    public function testListsAccountOwnedReusableVoices(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"voices":[{"voice_id":"voice_1","name":"Narrator","state":"trained"}],"total":1,"page_number":2,"page_size":25,"billing":{"reservation":null,"settlement":{"charged_amount_cents":0,"amount_micro_cents":0},"refund":null}}'),
        ]);
        $client = new FishAudioClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $result = $client->listVoices->run(['page_number' => 2, 'page_size' => 25]);

        self::assertInstanceOf(VoicesResponse::class, $result);
        self::assertSame('voice_1', $result->voices[0]->voiceId);
        self::assertSame(0, $result->billing->settlement?->amountMicroCents);
        self::assertSame('/api/v1/fish_audio/voices', $transport->requests[0]->getUri()->getPath());
        self::assertSame('page_number=2&page_size=25', $transport->requests[0]->getUri()->getQuery());
        self::assertSame('GET', $transport->requests[0]->getMethod());
    }

    public function testGetsEncodedAccountOwnedReusableVoiceId(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"voice":{"voice_id":"voice/1","name":"Narrator","state":"trained"},"billing":{"reservation":null,"settlement":{"charged_amount_cents":0,"amount_micro_cents":0},"refund":null}}'),
        ]);
        $client = new FishAudioClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $result = $client->getVoice->run(['voice_id' => 'voice/1']);

        self::assertInstanceOf(VoiceResponse::class, $result);
        self::assertSame('trained', $result->voice->state);
        self::assertSame(0, $result->billing->settlement?->amountMicroCents);
        self::assertSame('/api/v1/fish_audio/voices/voice%2F1', $transport->requests[0]->getUri()->getPath());
        self::assertSame('GET', $transport->requests[0]->getMethod());
    }

    public function testListVoicesSubscribeUsesTheListRequestShape(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"voices":[],"total":0,"page_number":2,"page_size":25,"billing":{"reservation":null,"settlement":null,"refund":null}}'),
        ]);
        $client = new FishAudioClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        iterator_to_array($client->listVoices->subscribe(['page_number' => 2, 'page_size' => 25]));

        self::assertSame('GET', $transport->requests[0]->getMethod());
        self::assertSame('page_number=2&page_size=25', $transport->requests[0]->getUri()->getQuery());
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
