<?php

namespace Tests\Unit\Services\CredentialMakers;

use App\Contracts\Credentials\MakerInterface;
use App\Models\Credential;
use App\Services\CredentialMakers\BrowserMakerService;
use PHPUnit\Framework\TestCase;

class BrowserMakerServiceTest extends TestCase
{
    /** @test */
    public function it_should_return_file_content(): void
    {
        $credentialMaker = new BrowserMakerService();

        $this->assertEquals('base64_encoded_fake_content', $credentialMaker->makeImage('any name', 'any@email.com', null));
        $this->assertEquals('base64_encoded_fake_content', $credentialMaker->makePDF('any name', 'any@email.com', null));
    }
}
