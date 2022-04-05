<?php

namespace Amp\Websocket\Server;

use Amp\Http\Server\Request;
use Amp\Http\Server\Response;
use Amp\Socket\Socket;
use Amp\Websocket\Client;
use Amp\Websocket\CompressionContext;
use Amp\Websocket\DefaultHeartbeatQueue;
use Amp\Websocket\DefaultRateLimiter;
use Amp\Websocket\HeartbeatQueue;
use Amp\Websocket\RateLimiter;
use Amp\Websocket\Rfc6455Client;

final class Rfc6455ClientFactory implements ClientFactory
{
    public function __construct(
        private readonly ?HeartbeatQueue $heartbeatQueue = new DefaultHeartbeatQueue(),
        private readonly ?RateLimiter $rateLimiter = new DefaultRateLimiter(),
        private readonly bool $textOnly = Client::DEFAULT_TEXT_ONLY,
        private readonly bool $validateUtf8 = Client::DEFAULT_VALIDATE_UTF8,
        private readonly int $messageSizeLimit = Client::DEFAULT_MESSAGE_SIZE_LIMIT,
        private readonly int $frameSizeLimit = Client::DEFAULT_FRAME_SIZE_LIMIT,
        private readonly int $streamThreshold = Client::DEFAULT_STREAM_THRESHOLD,
        private readonly int $frameSplitThreshold = Client::DEFAULT_FRAME_SPLIT_THRESHOLD,
        private readonly float $closePeriod = Client::DEFAULT_CLOSE_PERIOD,
    ) {
    }

    public function createClient(
        Request $request,
        Response $response,
        Socket $socket,
        ?CompressionContext $compressionContext = null,
    ): Client {
        return new Rfc6455Client(
            socket: $socket,
            masked: false,
            compressionContext: $compressionContext,
            heartbeatQueue: $this->heartbeatQueue,
            rateLimiter: $this->rateLimiter,
            textOnly: $this->textOnly,
            validateUtf8: $this->validateUtf8,
            messageSizeLimit: $this->messageSizeLimit,
            frameSizeLimit: $this->frameSizeLimit,
            streamThreshold: $this->streamThreshold,
            frameSplitThreshold: $this->frameSplitThreshold,
            closePeriod: $this->closePeriod,
        );
    }
}
