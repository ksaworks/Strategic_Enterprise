<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Exception;

class ResourceBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'resource_id',
        'user_id',
        'project_id',
        'start_time',
        'end_time',
        'purpose',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function resource()
    {
        return $this->belongsTo(CompanyResource::class, 'resource_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Bootstrap the model and its traits.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($booking) {
            // Validate start time < end time
            if ($booking->start_time >= $booking->end_time) {
                throw new Exception("O horário de início deve ser anterior ao horário de término.");
            }

            // Check for overlaps
            $exists = static::where('resource_id', $booking->resource_id)
                ->where(function (Builder $query) use ($booking) {
                    $query->whereBetween('start_time', [$booking->start_time, $booking->end_time])
                        ->orWhereBetween('end_time', [$booking->start_time, $booking->end_time])
                        ->orWhere(function (Builder $query) use ($booking) {
                            $query->where('start_time', '<', $booking->start_time)
                                ->where('end_time', '>', $booking->end_time);
                        });
                })
                ->where('id', '!=', $booking->id ?? 0) // Ignore self when updating
                ->exists();

            if ($exists) {
                throw new Exception("Este recurso já está reservado neste horário.");
            }
        });
    }
}
