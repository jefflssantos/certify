<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $uuid
 */
class Credential extends Model
{
    use HasFactory;
    use Uuid;

    protected $fillable = [
        'issued_to',
        'email',
        'image',
        'pdf',
        'expires_at'
    ];

    protected $dates = ['expires_at'];

    protected $casts = [
        'expires_at' => 'date:Y-m-d',
    ];

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }
}
