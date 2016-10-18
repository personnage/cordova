<?php declare(strict_types=1);

namespace App\Jobs;

use App\Events\ProcessingPhotoComplete;
use App\Models\Photo;
use App\Repositories\FlickrPhotosRepositories;
use App\Repositories\GeocodingRepositories;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client as HttpClient;
use Psr\Http\Message\ResponseInterface;
use Storage;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessingExternalPhoto implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Photo
     */
    protected $photo;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Photo $photo)
    {
        $this->photo = $photo;
    }

    public function handle(GeocodingRepositories $geocodingRepositories)
    {
        $repositories = $this->getRepositoriesByProvider($this->photo->provider);

        // Должен быть общий интерфейс для доступных репозиториев.
        // Дальше что-то находим:
        // $photo = $repositories-findById($this->photo->extern_id);
        // обрабатываем и сохраняем.
        // За недостатком сведений, что будет дальше, обраюотаем только Flickr.

        $this->flickrHandler($repositories);
        $this->geocodingHandler($geocodingRepositories);

        // Fire event complite photo...
        // event(new ProcessingPhotoComplete($this->photo));
    }

    protected function getRepositoriesByProvider(string $provider)
    {
        switch ($provider) {
            case 'flickr':
                return new FlickrPhotosRepositories;

            default:
                throw new Exception("Repositories not found", 404);
        }
    }

    /**
     * Perform processing.
     *
     * @return bool
     */
    protected function flickrHandler(FlickrPhotosRepositories $repositories): bool
    {
        $prey = last($repositories->sizes($this->photo->extern_id));
        $client = new HttpClient();
        $response = $client->get($prey['source']);

        $this->photo->server = $this->selectServerName();

        return $this->save($response) && $this->photo->save();
    }

    /**
     * Perform geocoding.
     *
     * @param  GeocodingRepositories $repositories
     * @return int
     */
    protected function geocodingHandler(GeocodingRepositories $repositories): int
    {
        $lat = $this->photo->location->latitude;
        $lng = $this->photo->location->longitude;

        try {
            $results = $repositories->findByPoint($lat, $lng);

            return $this->photo->location()->update([
                'place_id' => $results['place_id'],

                // Update latitude and longitude...
                // Не может замапить массив через мутатор (bug 😠)
                // 'location' => $results['location'],
                'location_type' => $results['location_type'],
            ]);
        } catch (Exception $e) {
            // Add record to log file...
            return 0;
        }
    }

    protected function selectServerName(): string
    {
        return (string) Carbon::today()->timestamp;
    }

    protected function buildPath(): string
    {
        $filename = sprintf('%d_%s.jpg', $this->photo->id, $this->photo->label);

        return join('/', ['photos', $this->photo->server, $filename]);
    }

    protected function save(ResponseInterface $response): bool
    {
        return Storage::put($this->buildPath(), $response->getBody());
    }
}
