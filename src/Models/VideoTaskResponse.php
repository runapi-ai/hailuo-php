<?php

declare(strict_types=1);

namespace RunApi\Hailuo\Models;

use RunApi\Core\Models\TaskResponse;
use RunApi\Core\Support\Payload;

/**
 * Async video task response with lifecycle status and output files.
 */
readonly class VideoTaskResponse extends TaskResponse
{
    /**
     * Create a video task response value object.
     *
     * @param list<Video> $videos
     * @param array<string, mixed> $raw
     */
    public function __construct(?string $id, string $status, ?string $error = null, public array $videos = [], array $raw = [])
    {
        parent::__construct(id: $id, status: $status, error: $error, raw: $raw === [] ? ['id' => $id, 'status' => $status, 'error' => $error, 'videos' => array_map(static fn (Video $video): array => $video->toArray(), $videos)] : $raw);
    }

    /**
     * Hydrate a video task response from a RunAPI response object.
     *
     * @param array<string, mixed> $raw
     */
    public static function fromArray(array $raw): self
    {
        return new self(id: Payload::string($raw, 'id'), status: Payload::string($raw, 'status'), error: self::error($raw), videos: self::videos($raw), raw: $raw);
    }

    /**
     * @param array<string, mixed> $raw
     *
     * @return list<Video>
     */
    protected static function videos(array $raw, bool $required = false): array
    {
        return Payload::listOf($raw, 'videos', Video::fromArray(...), $required);
    }
}
