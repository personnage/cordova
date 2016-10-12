<?php declare(strict_types=1);

namespace App\Repositories;

use Exception;
use RuntimeException;
use GuzzleHttp\Client as HttpClient;
use Psr\Http\Message\ResponseInterface;

class GeocodingGoogleRepositories
{
    /**
     * Ошибок нет, адрес обработан и получен хотя бы один геокод.
     *
     * @var string
     */
    const OK = 'OK';

    /**
     * Геокодирование успешно выполнено, однако результаты не найдены.
     * Это может произойти, если геокодировщику был передан несуществующий
     * адрес (address).
     *
     * @var string
     */
    const ZERO_RESULTS = 'ZERO_RESULTS';

    /**
     * Указывает на превышение квоты.
     *
     * @var string
     */
    const OVER_QUERY_LIMIT = 'OVER_QUERY_LIMIT';

    /**
     * Указывает на отклонение запроса.
     *
     * @var string
     */
    const REQUEST_DENIED = 'REQUEST_DENIED';

    /**
     * Как правило, указывает на отсутствие в запросе полей address,
     * components или latlng.
     *
     * @var string
     */
    const INVALID_REQUEST = 'INVALID_REQUEST';

    /**
     * Указывает, что запрос не удалось обработать из-за ошибки сервера.
     * Если повторить попытку, запрос может оказаться успешным.
     *
     * @var string
     */
    const UNKNOWN_ERROR = 'UNKNOWN_ERROR';

    /**
     * @var HttpClient
     */
    private $httpClient;

    public function __construct()
    {
        $this->setHttpClient(new HttpClient([
            'base_uri' => 'https://maps.googleapis.com/maps/api/geocode/'
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

    protected function key($default = null): string
    {
        return env('GOOGLE_API_KEY', $default);
    }

    protected function format(): string
    {
        return 'json';
    }

    protected function toArray(ResponseInterface $response): array
    {
        return json_decode(strval($response->getBody()), true, 32);
    }

    public function findByLocation(string $latitude, string $longitude): array
    {
        $response = $this->getHttpClient()->get($this->format(), [
            'query' => [
                'key' => $this->key(),
                'latlng' => "{$latitude},{$longitude}",
            ]
        ]);

        $output = $this->toArray($response);

        try {
            $this->checkStatus($output['status']);
        } catch (Exception $e) {
            throw new RuntimeException('New message...', 1, $e);
        }

        return $this->transform($output['results']);
    }

    protected function transform(array $input): array
    {
        $first = head($input);

        return $first;

        foreach ($first['address_components'] as $component) {
            # code...
        }

        return [
            'place_id' => $first['place_id'],
            'formatted_address' =>$first['formatted_address'],
        ];
    }

    protected function checkStatus(string $status): bool
    {
        switch ($status) {
            case static::INVALID_REQUEST:
                throw new Exception('Error Processing Request');

            case static::OK:
            default:
                return true;
        }
    }
}
