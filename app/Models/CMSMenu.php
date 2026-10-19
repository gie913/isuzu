<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CMSMenu extends Model
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
        return $this->belongsTo(CMSMenu::class, 'parent_id', 'id');
    }

    /**
     * Submenus relationship
     */
    public function children()
    {
        return $this->hasMany(CMSMenu::class, 'parent_id', 'id')
            ->where('is_active', true)
            ->orderBy('sort_number');
    }
}
