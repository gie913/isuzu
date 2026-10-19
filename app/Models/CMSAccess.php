<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CMSAccess extends Model
{
    use HasFactory, HasUuids;

    protected $table = 't_module_access';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'm_module_id',
        'm_role_id',
        'can_read',
        'can_create',
        'can_update',
        'can_delete',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'can_read'   => 'integer',
            'can_create' => 'integer',
            'can_update' => 'integer',
            'can_delete' => 'integer',
            'is_active'  => 'boolean',
        ];
    }

    public function module()
    {
        return $this->belongsTo(CMSMenu::class, 'm_module_id', 'id');
    }
}
