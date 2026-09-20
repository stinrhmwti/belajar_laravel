<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $content
 * @property array|null $variables
 * @property string|null $description
 * @property bool $is_active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class WhatsappTemplate extends Model
{
    protected $table = 'whatsapp_templates';

    protected $fillable = [
        'code',
        'name',
        'content',
        'variables',
        'description',
        'is_active',
    ];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke history log pengiriman pesan dengan template ini.
     */
    public function logs(): HasMany
    {
        return $this->hasMany(WhatsappLog::class, 'whatsapp_template_id');
    }

    /**
     * Scope query untuk template yang berstatus aktif.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
