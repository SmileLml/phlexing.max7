<?php

namespace Spiral\RoadRunner\Http;

use JetBrains\PhpStorm\Immutable;

/**
 * @psalm-immutable
 *
 * @psalm-type UploadedFile = array {
 *      name:       string,
 *      error:      positive-int|0,
 *      tmpName:    string,
 *      size:       positive-int|0,
 *      mime:       string
 * }
 *
 * @psalm-type HeadersList = array<string, array<array-key, string>>
 * @psalm-type AttributesList = array<string, mixed>
 * @psalm-type QueryArgumentsList = array<string, string>
 * @psalm-type CookiesList = array<string, string>
 * @psalm-type UploadedFilesList = array<array-key, UploadedFile>
 */
final class Request
{
    public const PARSED_BODY_ATTRIBUTE_NAME = 'rr_parsed_body';
    /**
     * @var string
     */
    public $remoteAddr = '127.0.0.1';
    /**
     * @var string
     */
    public $protocol = 'HTTP/1.0';
    /**
     * @var string
     */
    public $method = 'GET';
    /**
     * @var string
     */
    public $uri = 'http://localhost';
    /**
     * @var HeadersList
     */
    public $headers = [];
    /**
     * @var CookiesList
     */
    public $cookies = [];
    /**
     * @var UploadedFilesList
     */
    public $uploads = [];
    /**
     * @var AttributesList
     */
    public $attributes = [];
    /**
     * @var QueryArgumentsList
     */
    public $query = [];
    /**
     * @var string
     */
    public $body = '';
    /**
     * @var bool
     */
    public $parsed = false;
    /**
     * @return string
     */
    public function getRemoteAddr()
    {
        return (string)($this->attributes['ipAddress'] ?? $this->remoteAddr);
    }
    /**
     * @return array|null
     * @throws \JsonException
     */
    public function getParsedBody()
    {
        if ($this->parsed) {
            return (array)\json_decode($this->body, true, 512, 0);
        }

        return null;
    }
}
