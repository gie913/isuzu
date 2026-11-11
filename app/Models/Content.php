<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory, HasUuids;

    protected $table = 't_content';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'description',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * User who created the record.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * User who last updated the record.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}