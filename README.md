# Hailuo PHP SDK for RunAPI

[![Packagist](https://img.shields.io/packagist/v/runapi-ai/hailuo)](https://packagist.org/packages/runapi-ai/hailuo)
[![License](https://img.shields.io/github/license/runapi-ai/hailuo-php)](https://github.com/runapi-ai/hailuo-php/blob/main/LICENSE)

The Hailuo PHP SDK is the Composer package for Hailuo on RunAPI. Use it when your PHP application needs associative-array request bodies, task status lookup, polling helpers, file helpers, and consistent RunAPI errors.

## Install

```bash
composer require runapi-ai/hailuo
```

## Quick start

```php
<?php

require __DIR__ . "/vendor/autoload.php";

use RunApi\Hailuo\HailuoClient;

$client = new HailuoClient(); // reads RUNAPI_API_KEY

$task = $client->textToVideo->create([
    'model' => 'hailuo-02-text-to-video-pro',
    'prompt' => 'A precise product render on white marble',
]);

$status = $client->textToVideo->get($task->id);

$result = $client->textToVideo->run([
    'model' => 'hailuo-02-text-to-video-pro',
    'prompt' => 'A serene mountain lake at dawn',
]);

echo $result->videos[0]->url . PHP_EOL;
```

Use `create()` to submit a task and return quickly, `get()` to fetch the latest task state, and `run()` when a script should create and poll until completion. In web request handlers, prefer `create()` plus webhook or later `get()` polling so a worker is not held open.

Returned file URLs are temporary. Download and store generated files in your own durable storage within the retention window.

All SDK exceptions inherit from `RunApi\Core\Errors\RunApiException`, including validation, authentication, rate limit, task failure, and task timeout errors.

## Links

- Model page: https://runapi.ai/models/hailuo
- SDK docs: https://runapi.ai/docs#sdk-hailuo
- Product docs: https://runapi.ai/docs#hailuo
- Pricing and rate limits: https://runapi.ai/models/hailuo/02-text-to-video-pro
- Full catalog: https://runapi.ai/models
- GitHub repository: https://github.com/runapi-ai/hailuo-php
- Multi-language SDK repository: https://github.com/runapi-ai/hailuo-sdk

## License

Licensed under the Apache License, Version 2.0.
