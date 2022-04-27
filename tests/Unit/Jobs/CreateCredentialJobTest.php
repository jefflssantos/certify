<?php

namespace Tests\Unit\Jobs;

use App\Contracts\Credentials\MakerInterface;
use App\Jobs\CreateCredentialJob;
use App\Models\Credential;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CreateCredentialJobTest extends TestCase
{
    /** @test */
    public function it_should_store_credentials_files(): void
    {
        Storage::fake();
        $credential = $this->partialMock(Credential::class);
        $credential->uuid = 'uuid-example';
        $credential->issued_to = 'any-name';
        $credential->email = 'any-email@.com';

        $credentialMaker = $this->mock(MakerInterface::class);

        $credentialMaker
            ->shouldReceive('makeImage')
            ->with($credential->issued_to, $credential->email, $credential->expires_at)
            ->andReturn('file-content');

        $credentialMaker
            ->shouldReceive('makePDF')
            ->with($credential->issued_to, $credential->email, $credential->expires_at)
            ->andReturn('file-content');

        Storage::shouldReceive('put')
            ->with('public/images/uuid-example.jpeg', 'file-content')
            ->once()
            ->andReturn('storage/path/to/image');

        Storage::shouldReceive('put')
            ->with('public/pdf/uuid-example.pdf', 'file-content')
            ->once()
            ->andReturn('storage/path/to/pdf');

        $credential
            ->shouldReceive('update')
            ->with([
                'image' => 'public/images/uuid-example.jpeg',
                'pdf' => 'public/pdf/uuid-example.pdf'
            ])
            ->andReturn(true);

        $job = new CreateCredentialJob($credential);
        $job->handle($credentialMaker);
    }
}
