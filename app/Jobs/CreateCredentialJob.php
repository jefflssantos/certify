<?php

namespace App\Jobs;

use App\Contracts\Credentials\MakerInterface;
use App\Models\Credential;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class CreateCredentialJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private Credential $credential)
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(MakerInterface $maker)
    {
        $image = $maker->makeImage(
            $this->credential->issued_to,
            $this->credential->email,
            $this->credential->expires_at?->format('Y/m/d'),
        );

        $pdf = $maker->makePDF(
            $this->credential->issued_to,
            $this->credential->email,
            $this->credential->expires_at?->format('Y/m/d'),
        );

        Storage::put($imagePath = "public/images/{$this->credential->uuid}.jpeg", $image);
        Storage::put($pdfPath = "public/pdf/{$this->credential->uuid}.pdf", $pdf);

        $this->credential->update([
            'image' => $imagePath,
            'pdf' => $pdfPath
        ]);
    }
}
