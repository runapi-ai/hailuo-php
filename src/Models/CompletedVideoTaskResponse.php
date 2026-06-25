<?php

declare(strict_types=1);

namespace RunApi\Hailuo\Models;

use RunApi\Core\Support\Payload;

/**
 * Completed video task response returned by run(); outputs are guaranteed present.
 */
readonly class CompletedVideoTaskResponse extends VideoTaskResponse
{
    /**
     * Hydrate a completed video task response from a RunAPI response object.
     *
     * @param array<string, mixed> $raw
     */
    public static function fromArray(array $raw): self
    {
        return new self(id: Payload::string($raw, 'id'), status: Payload::string($raw, 'status'), error: self::error($raw), videos: self::videos($raw, required: true), raw: $raw);
    }

    /**
     * Narrow a polled task response after completion has been confirmed.
     */
    public static function fromResponse(VideoTaskResponse $response): self
    {
        return self::fromArray($response->toArray());
    }
}
