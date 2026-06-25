<?php

declare(strict_types=1);

namespace RunApi\Hailuo\Tests\Unit;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use RunApi\Core\ClientOptions;
use RunApi\Core\Errors\ValidationException;
use RunApi\Core\Tests\Fixtures\QueueHttpClient;
use RunApi\Hailuo\HailuoClient;
use RunApi\Hailuo\Models\CompletedVideoTaskResponse;
use RunApi\Hailuo\Resources\ImageToVideo;
use RunApi\Hailuo\Resources\TextToVideo;

final class HailuoClientTest extends TestCase
{
    public function testExposesTypedResources(): void
    {
        $client = new HailuoClient(new ClientOptions(apiKey: 'k', httpClient: new QueueHttpClient([]), maxRetries: 0));

        self::assertInstanceOf(TextToVideo::class, $client->textToVideo);
        self::assertInstanceOf(ImageToVideo::class, $client->imageToVideo);
    }

    public function testCreatePostsCompactedBodyToCorrectPath(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1"}'),
        ]);
        $client = new HailuoClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $task = $client->textToVideo->create([
            'model' => 'hailuo-02-text-to-video-pro',
            'duration_seconds' => 6,
            'prompt' => 'A product render',
            'callback_url' => '',
            'seed' => null,
        ]);

        $body = json_decode((string) $transport->requests[0]->getBody(), true, flags: JSON_THROW_ON_ERROR);

        self::assertSame('task_1', $task->id);
        self::assertSame('/api/v1/hailuo/text_to_video', $transport->requests[0]->getUri()->getPath());
        self::assertSame('hailuo-02-text-to-video-pro', $body['model']);
        self::assertArrayNotHasKey('callback_url', $body);
        self::assertArrayNotHasKey('seed', $body);
    }

    public function testRunReturnsTypedCompletedResponseAndPreservesUnknownFields(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1"}'),
            new Response(200, [], '{"id":"task_1","status":"completed","videos":[{"url":"https://file.runapi.ai/result"}],"extra_field":"kept"}'),
        ]);
        $client = new HailuoClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $result = $client->textToVideo->run([
            'model' => 'hailuo-02-text-to-video-pro',
            'duration_seconds' => 6,
            'prompt' => 'A product render',
        ]);

        self::assertInstanceOf(CompletedVideoTaskResponse::class, $result);
        self::assertSame('https://file.runapi.ai/result', $result->videos[0]->url);
        self::assertSame('kept', $result->toArray()['extra_field']);
        self::assertSame('/api/v1/hailuo/text_to_video/task_1', $transport->requests[1]->getUri()->getPath());
    }

    public function testCompletedResponseRequiresResultFiles(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1"}'),
            new Response(200, [], '{"id":"task_1","status":"completed"}'),
        ]);
        $client = new HailuoClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('videos is required');

        $client->textToVideo->run([
            'model' => 'hailuo-02-text-to-video-pro',
            'duration_seconds' => 6,
            'prompt' => 'A product render',
        ]);
    }

    public function testRejectsInvalidContractEnum(): void
    {
        $client = new HailuoClient(new ClientOptions(apiKey: 'k', httpClient: new QueueHttpClient([]), maxRetries: 0));

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('duration_seconds must be one of the allowed values');

        $client->textToVideo->create([
        'model' => 'hailuo-02-text-to-video-standard',
        'prompt' => 'A product render',
        'duration_seconds' => 7,
        ]);
    }

    public function testSecondaryResourceUsesItsOwnPath(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_2"}'),
        ]);
        $client = new HailuoClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $client->imageToVideo->create([
            'model' => 'hailuo-02-image-to-video-pro',
            'duration_seconds' => 6,
            'first_frame_image_url' => 'https://cdn.runapi.ai/public/samples/image.jpg',
            'last_frame_image_url' => 'https://cdn.runapi.ai/public/samples/image.jpg',
            'output_resolution' => '768p',
            'prompt' => 'A product render',
        ]);

        self::assertSame('/api/v1/hailuo/image_to_video', $transport->requests[0]->getUri()->getPath());
    }
}
