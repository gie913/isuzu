<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadSetup extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'm_upload_setup';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'm_module_id',
        'name',
        'path',
        'type',
        'allowed_files',
        'is_resized',
        'pic_height',
        'pic_width',
        'is_cropped',
        'ratio_height',
        'ratio_width',
        'using_thumb',
        'thumb_height',
        'thumb_width',
        'max_size',
        'is_external_storage',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'is_resized' => 'boolean',
            'is_cropped' => 'boolean',
            'using_thumb' => 'boolean',
            'is_external_storage' => 'boolean',
            'is_active' => 'boolean',
            'pic_height' => 'integer',
            'pic_width' => 'integer',
            'ratio_height' => 'integer',
            'ratio_width' => 'integer',
            'thumb_height' => 'integer',
            'thumb_width' => 'integer',
            'max_size' => 'integer',
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