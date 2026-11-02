<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    use HasFactory, HasUuids;

    protected $table = 't_upload';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'm_upload_setup_id',
        'm_module_id',
        'name',
        'path',
        'type',
        'file_source',
        'is_external_storage',
        'is_temporary',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'is_external_storage' => 'boolean',
            'is_temporary' => 'boolean',
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