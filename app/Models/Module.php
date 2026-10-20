<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'm_module';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'label',
        'resource_class',
        'path',
        'icon',
        'sort_number',
        'parent_id',
        'is_menubar',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'is_active'   => 'boolean',
            'is_menubar'  => 'boolean',
            'sort_number' => 'float',
        ];
    }

    /**
     * Parent menu relationship
     */
    public function parent()
    {
        return $this->belongsTo(Module::class, 'parent_id', 'id');
    }

    /**
     * Submenus relationship
     */
    public function children()
    {
        return $this->hasMany(Module::class, 'parent_id', 'id')
            ->where('is_active', true)
            ->orderBy('sort_number');
    }

    /**
     * User who created the module.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * User who last updated the module.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
