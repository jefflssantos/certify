<?php

namespace App\Services\CredentialMakers;

use App\Contracts\Credentials\MakerInterface;

class BrowserMakerService implements MakerInterface
{
    public function makeImage(string $name, string $email, ?string $expiresAt): string
    {
        return $this->shoot($name, $email, $expiresAt, 'jpeg');
    }

    public function makePDF(string $name, string $email, ?string $expiresAt): string
    {
        return $this->shoot($name, $email, $expiresAt, 'pdf');
    }

    private function shoot(string $name, string $email, ?string $expiresAt, string $type): string
    {
        // inject a lib to make the files from a html page

        return 'base64_encoded_fake_content';
    }
}
