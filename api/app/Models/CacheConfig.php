<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CacheConfig extends Model
{
    use HasFactory, SoftDeletes;
    protected $dateFormat = 'U';
    protected $fillable = [
        'app_id',
        'custom',
        'driver',
        'host',
        'port',
    ];

    public function app(): BelongsTo
    {
        return $this->belongsTo(App::class);
    }
}
