<?php

declare(strict_types=1);

namespace RunApi\Hailuo;

/**
 * Constants for model slugs supported by the Hailuo PHP SDK.
 */
final class Types
{
    /** @var list<string> */
    public const TEXT_TO_VIDEO_MODELS = ['hailuo-02-text-to-video-pro', 'hailuo-02-text-to-video-standard'];

    /** @var list<string> */
    public const IMAGE_TO_VIDEO_MODELS = ['hailuo-02-image-to-video-pro', 'hailuo-02-image-to-video-standard', 'hailuo-2.3-image-to-video-pro', 'hailuo-2.3-image-to-video-standard'];

    private function __construct()
    {
    }
}
