<?php

namespace EmaSalas\OtpGenerator\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class OneTimePassword extends Model
{
  protected $fillable = [
    'model',
    'model_id',
    'token',
    'validity',
    'expired_at'
  ];

  protected $hidden = [
    'created_at',
    'updated_at'
  ];

  protected $casts = [
    'expired_at' => 'datetime',
    'validity' => 'boolean'
  ];

  public function scopeTokenAlive(Builder $query, $validTimeInMinutes = 10): void
  {
    $query->where('validity', 1)
    ->whereDate(
      'created_at',
      '<=',
      now()->addMinutes($validTimeInMinutes)
    );
  }
}