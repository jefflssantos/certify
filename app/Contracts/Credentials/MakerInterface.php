<?php

namespace App\Contracts\Credentials;

interface MakerInterface
{
    public function makeImage(string $issuedTo, string $email, ?string $expiresAt): string;

    public function makePDF(string $issuedTo, string $email, ?string $expiresAt): string;
}
