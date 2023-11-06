<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;

class App extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $dateFormat = 'U';

    protected $fillable = [
        'client_id',
        'application',
        'path',
        'subdomain',
        'grpc_port',
        'use_domain',
        'domain',
        'use_s3',
        'aws_region',
        'aws_bucket',
        'started_at',
        'installed_at',
    ];

    protected $appends = [
        'url_kubernetes',
        'url_subscribe'

    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function databaseConfig(): HasOne
    {
        return $this->hasOne(DatabaseConfig::class);
    }

    public function cacheConfig(): HasOne
    {
        return $this->hasOne(CacheConfig::class);
    }

    public function getUrlKubernetesAttribute(): string
    {
        $url = Cache::get("app.{$this->id}.url");
        if (!$url) {
            $url = URL::signedRoute('app.kubernetes', ['app' => $this->id]);
            Cache::put("app.{$this->id}.url", $url, 60 * 60 * 24);
        }
        return $url;
    }

    public function getUrlSubscribeAttribute(): string
    {
        $url = Cache::get("app.{$this->id}.subscribe");
        if (!$url) {
            $url = URL::signedRoute('app.subscribe', ['app' => $this->id]);
            Cache::put("app.{$this->id}.subscribe", $url, 60 * 60 * 24);
        }
        return $url;
    }
}
