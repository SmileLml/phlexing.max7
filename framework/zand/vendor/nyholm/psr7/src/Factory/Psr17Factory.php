<?php

namespace Nyholm\Psr7\Factory;

use Nyholm\Psr7\{Request, Response, ServerRequest, Stream, UploadedFile, Uri};
use Psr\Http\Message\{RequestFactoryInterface, RequestInterface, ResponseFactoryInterface, ResponseInterface, ServerRequestFactoryInterface, ServerRequestInterface, StreamFactoryInterface, StreamInterface, UploadedFileFactoryInterface, UploadedFileInterface, UriFactoryInterface, UriInterface};

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 * @author Martijn van der Ven <martijn@vanderven.se>
 *
 * @final This class should never be extended. See https://github.com/Nyholm/psr7/blob/master/doc/final.md
 */
class Psr17Factory implements RequestFactoryInterface, ResponseFactoryInterface, ServerRequestFactoryInterface, StreamFactoryInterface, UploadedFileFactoryInterface, UriFactoryInterface
{
    /**
     * @param string $method
     */
    public function createRequest($method, $uri)
    {
        return new Request($method, $uri);
    }

    /**
     * @param int $code
     * @param string $reasonPhrase
     */
    public function createResponse($code = 200, $reasonPhrase = '')
    {
        if (2 > \func_num_args()) {
            // This will make the Response class to use a custom reasonPhrase
            $reasonPhrase = null;
        }

        return new Response($code, [], null, '1.1', $reasonPhrase);
    }

    /**
     * @param string $content
     */
    public function createStream($content = '')
    {
        return Stream::create($content);
    }

    /**
     * @param string $filename
     * @param string $mode
     */
    public function createStreamFromFile($filename, $mode = 'r')
    {
        if ('' === $filename) {
            throw new \RuntimeException('Path cannot be empty');
        }

        if (false === $resource = @\fopen($filename, $mode)) {
            if ('' === $mode || false === \in_array($mode[0], ['r', 'w', 'a', 'x', 'c'], true)) {
                throw new \InvalidArgumentException(\sprintf('The mode "%s" is invalid.', $mode));
            }

            throw new \RuntimeException(\sprintf('The file "%s" cannot be opened: %s', $filename, \error_get_last()['message'] ?? ''));
        }

        return Stream::create($resource);
    }

    public function createStreamFromResource($resource)
    {
        return Stream::create($resource);
    }

    /**
     * @param \Psr\Http\Message\StreamInterface $stream
     * @param int|null $size
     * @param int $error
     * @param string|null $clientFilename
     * @param string|null $clientMediaType
     */
    public function createUploadedFile($stream, $size = null, $error = \UPLOAD_ERR_OK, $clientFilename = null, $clientMediaType = null)
    {
        if (null === $size) {
            $size = $stream->getSize();
        }

        return new UploadedFile($stream, $size, $error, $clientFilename, $clientMediaType);
    }

    /**
     * @param string $uri
     */
    public function createUri($uri = '')
    {
        return new Uri($uri);
    }

    /**
     * @param string $method
     * @param mixed[] $serverParams
     */
    public function createServerRequest($method, $uri, $serverParams = [])
    {
        return new ServerRequest($method, $uri, [], null, '1.1', $serverParams);
    }
}
