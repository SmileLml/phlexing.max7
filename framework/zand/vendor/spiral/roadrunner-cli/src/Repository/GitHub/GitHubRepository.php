<?php

namespace Spiral\RoadRunner\Console\Repository\GitHub;

use Spiral\RoadRunner\Console\Repository\ReleasesCollection;
use Spiral\RoadRunner\Console\Repository\RepositoryInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * @psalm-import-type GitHubReleaseApiResponse from GitHubRelease
 */
final class GitHubRepository implements RepositoryInterface
{
    /**
     * @var string
     */
    private const URL_RELEASES = 'https://api.github.com/repos/%s/releases';

    /**
     * @var HttpClientInterface
     */
    private $client;

    /**
     * @var string
     */
    private $name;

    /**
     * @var array|string[]
     */
    private $headers = [
        'accept' => 'application/vnd.github.v3+json',
    ];

    /**
     * @param string $owner
     * @param string $repository
     * @param HttpClientInterface|null $client
     */
    public function __construct($owner, $repository, $client = null)
    {
        $this->name = $owner . '/' . $repository;
        $this->client = $client ?? HttpClient::create();
    }

    /**
     * @param string $owner
     * @param string $name
     * @param HttpClientInterface|null $client
     * @return GitHubRepository
     */
    public static function create($owner, $name, $client = null)
    {
        return new GitHubRepository($owner, $name, $client);
    }

    /**
     * {@inheritDoc}
     * @throws ExceptionInterface
     */
    public function getReleases()
    {
        return ReleasesCollection::from(function () {
            $page = 0;

            do {
                $response = $this->releasesRequest(++$page);

                /** @psalm-var GitHubReleaseApiResponse $data */
                foreach ($response->toArray() as $data) {
                    yield GitHubRelease::fromApiResponse($this, $this->client, $data);
                }
            } while ($this->hasNextPage($response));
        });
    }

    /**
     * @param positive-int $page
     * @return ResponseInterface
     * @throws TransportExceptionInterface
     */
    private function releasesRequest($page)
    {
        return $this->request('GET', $this->uri(self::URL_RELEASES), [
            'query' => [
                'page' => $page,
            ],
        ]);
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $options
     * @return ResponseInterface
     * @throws TransportExceptionInterface
     * @see HttpClientInterface::request()
     */
    protected function request($method, $uri, $options = [])
    {
        // Merge headers with defaults
        $options['headers'] = \array_merge($this->headers, (array)($options['headers'] ?? []));

        return $this->client->request($method, $uri, $options);
    }

    /**
     * @param string $pattern
     * @return string
     */
    private function uri($pattern)
    {
        return \sprintf($pattern, $this->getName());
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param ResponseInterface $response
     * @return bool
     * @throws ExceptionInterface
     */
    private function hasNextPage($response)
    {
        $headers = $response->getHeaders();
        $link = $headers['link'] ?? [];

        if (! isset($link[0])) {
            return false;
        }

        return strpos($link[0], 'rel="next"') !== false;
    }
}
