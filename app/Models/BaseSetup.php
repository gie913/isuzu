<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseSetup extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'm_base_setup';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'title',
        'meta_description',
        'meta_keyword',
        'long_description',
        'using_dual_lang',
        'default_lang',
        'primary_image_src',
        'secondary_image_src',
        'alternative1_image_src',
        'alternative2_image_src',
        'alternative_image_src',
        'x_icon_src',
        'author',
        'address',
        'contact',
        'dashboard_theme_config',
        'asset_version',
        'additional_scripts',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'using_dual_lang' => 'integer',
            'contact' => 'array',
            'address' => 'array',
            'dashboard_theme_config' => 'array',
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