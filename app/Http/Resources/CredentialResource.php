<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property string $uuid
 * @property string $issued_to
 * @property string $email
 * @property string $image
 * @property string $pdf
 * @property object|null $expires_at
 * @property object $created_at
 * @property object $updated_at
 */
class CredentialResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'uuid' => $this->uuid,
            'issued_to' => $this->issued_to,
            'email' => $this->email,
            'image' => $this->image,
            'pdf' => $this->pdf,
            'expires_at' => $this->expires_at?->format('Y-m-d'),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s')
        ];
    }
}
