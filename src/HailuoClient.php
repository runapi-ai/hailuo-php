<?php

declare(strict_types=1);

namespace RunApi\Hailuo;

use RunApi\Core\BaseClient;
use RunApi\Core\ClientOptions;
use RunApi\Hailuo\Resources\ImageToVideo;
use RunApi\Hailuo\Resources\TextToVideo;

/**
 * Provides Hailuo text-to-video and image-to-video generation.
 *
 * Exposes typed model resources plus the universal files and account resources.
 */
final class HailuoClient extends BaseClient
{
    /**
     * Text to video operations.
     */
    public readonly TextToVideo $textToVideo;
    /**
     * Image to video operations.
     */
    public readonly ImageToVideo $imageToVideo;

    /**
     * Create a Hailuo client with optional API key, base URL, and transport overrides.
     */
    public function __construct(ClientOptions $options = new ClientOptions())
    {
        parent::__construct($options);
        $this->textToVideo = TextToVideo::fromHttp($this->http);
        $this->imageToVideo = ImageToVideo::fromHttp($this->http);
    }
}
