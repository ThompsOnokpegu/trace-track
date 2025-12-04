<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\ProjectStage;
use Illuminate\Support\Str;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tracking_code',
        'title',
        'location',
        'current_status',
        'installation_date',
        'notes',
    ];

    protected $casts = [
        'current_status' => ProjectStage::class,
        'installation_date' => 'date',
    ];

    // Auto-generate tracking code on creation
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($project) {
            if (empty($project->tracking_code)) {
                $project->tracking_code = strtoupper(Str::random(8));
            }
        });
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function updates(): HasMany
    {
        return $this->hasMany(ProjectUpdate::class)->latest();
    }
}