<?php

namespace App\Jobs;

use Storage;
use Carbon\Carbon;

use App\Models\Photo;
use App\Repositories\FlickrPhotosRepositories;

use Psr\Http\Message\ResponseInterface;

use App\Events\ProcessingPhotoComplete;

use GuzzleHttp\Client as HttpClient;

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
     * The name provider.
     *
     * @var string
     */
    protected $provider;

    /**
     * The id extern provider.
     *
     * @var string
     */
    protected $externId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Photo $photo)
    {
        $this->photo = $photo;
        $this->provider = $photo->provider;
        $this->externId = $photo->extern_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(FlickrPhotosRepositories $photos)
    {
        $prey = last($photos->sizes($this->externId));
        $client = new HttpClient();
        $response = $client->get($prey['source']);

        $this->photo->server = $this->selectServerName();

        $this->save($response) && $this->photo->save();

        event(new ProcessingPhotoComplete($this->photo));
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
