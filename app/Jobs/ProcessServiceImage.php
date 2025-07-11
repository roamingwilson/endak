<?php

namespace App\Jobs;

use App\Models\Services;
use App\Models\GeneralImage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ProcessServiceImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $service;
    protected $tempPath;

    /**
     * Create a new job instance.
     *
     * @param \App\Models\Services $service
     * @param string $tempPath
     */
    public function __construct(Services $service, $tempPath)
    {
        $this->service = $service;
        $this->tempPath = $tempPath;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Ensure the temporary file exists before processing
        if (Storage::disk('public')->exists($this->tempPath)) {
            // Create a new path in the final destination
            $newPath = 'services/' . basename($this->tempPath);

            // Move the file from the temp location to the final destination
            Storage::disk('public')->move($this->tempPath, $newPath);

            // Create the image record in the database
            $image = new GeneralImage([
                'path' => $newPath,
            ]);

            $this->service->images()->save($image);
        }
    }
}
