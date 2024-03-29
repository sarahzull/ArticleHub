<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_id',
        'external_id',
        'name',
        'description',
        'price',
        'created_at',
        'updated_at',
        'permission_id'
    ];

    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}
