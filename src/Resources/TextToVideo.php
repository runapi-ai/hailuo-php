<?php

declare(strict_types=1);

namespace RunApi\Hailuo\Resources;

use RunApi\Core\Http\HttpClient;
use RunApi\Core\Models\TaskCreateResponse;
use RunApi\Core\RequestOptions;
use RunApi\Core\Resources\TypedConfiguredResource;
use RunApi\Hailuo\Models\CompletedVideoTaskResponse;
use RunApi\Hailuo\Models\VideoTaskResponse;
use RunApi\Hailuo\Types;

/**
 * Generates video from a text prompt.
 */
readonly class TextToVideo extends TypedConfiguredResource
{
    /**
     * Submits a Hailuo text-to-video task and returns immediately with a task id.
     *
     * @param array{
     *   model: string,
     *   prompt: string,
     *   callback_url?: string,
     *   duration_seconds?: int
     * } $params
     */
    public function create(array $params, ?RequestOptions $options = null): TaskCreateResponse
    {
        return parent::create($params, $options);
    }

    /**
     * Fetches the current status of a Hailuo text-to-video task by id.
     */
    public function get(string $id, ?RequestOptions $options = null): VideoTaskResponse
    {
        $response = parent::get($id, $options);

        /** @var VideoTaskResponse $response */
        return $response;
    }

    /**
     * Submits a Hailuo text-to-video task and polls until it completes.
     *
     * @param array{
     *   model: string,
     *   prompt: string,
     *   callback_url?: string,
     *   duration_seconds?: int
     * } $params
     */
    public function run(array $params, ?RequestOptions $options = null): CompletedVideoTaskResponse
    {
        $response = parent::run($params, $options);

        /** @var CompletedVideoTaskResponse $response */
        return $response;
    }

    /**
     * Create the resource using the shared RunAPI HTTP transport.
     */
    public static function fromHttp(HttpClient $http): self
    {
        return new self(
            $http,
            '/api/v1/hailuo/text_to_video',
            'hailuo/text-to-video',
            VideoTaskResponse::class,
            CompletedVideoTaskResponse::class,
            Types::TEXT_TO_VIDEO_MODELS,
            'text-to-video',
            VideoTaskResponse::class,
            CompletedVideoTaskResponse::class,
        );
    }
}
