<?php declare(strict_types=1);

namespace App\Repositories;

use Exception;
use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Client as HttpClient;
use Psr\Http\Message\ResponseInterface;

abstract class FlickrRepositories
{
    /**
     * @var HttpClient
     */
    private $httpClient;

    public function __construct()
    {
        $this->setHttpClient(new HttpClient([
            'base_uri' => 'https://api.flickr.com/services/rest/'
        ]));
    }

    protected function setHttpClient(HttpClient $client)
    {
        $this->httpClient = $client;
    }

    protected function getHttpClient(): HttpClient
    {
        return $this->httpClient;
    }

    protected function key($default = null): array
    {
        return [
            'api_key' => env('FLICKR_KEY', $default),
        ];
    }

    protected function format($default = null): array
    {
        return [
            'format' => $default ?? 'php_serial',
        ];
    }

    protected function method(string $name): array
    {
        return [
            'method' => $this->resolveMethod($name)
        ];
    }

    abstract protected function resolveMethod(string $name);

    protected function async(array $query = []): Promise
    {
        return $this->getHttpClient()->getAsync('', compact('query'));
    }

    protected function buildQuery(array $data = []): array
    {
        return array_merge($this->key(), $this->format(), $data);
    }

    protected function unwrapResponse(ResponseInterface $response, bool $strict = true): array
    {
        $body = (string) $response->getBody();
        $result = unserialize($body);

        $strict && $this->checkResponseByStat($result);

        return $result;
    }

    protected function checkResponseByStat(array $data)
    {
        if (array_key_exists('stat', $data) && 'ok' != $data['stat']) {
            throw new Exception($data['message'], $data['code']);
        }
    }
}
