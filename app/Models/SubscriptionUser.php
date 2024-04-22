<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubscriptionUser extends Model
{
    use HasFactory;

    protected $table = 'subscription_users';

    protected $fillable = [
        'user_id',
        'subscription_plan_id',
        'subscription_id',
        'start_date',
        'end_date',
        'status',
        'invoice_id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'start_date' => 'datetime:Y-m-d',
        'end_date' => 'datetime:Y-m-d',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected $appends = [
        'start_date_formatted',
        'end_date_formatted',
    ];

    protected $with = ['plan'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id', 'id');
    }

    public function getStartDateFormattedAttribute()
    {
        return $this->asDateTime($this->start_date)->format('d F Y');
    }

    public function getEndDateFormattedAttribute()
    {
        return $this->asDateTime($this->end_date)->format('d F Y');
    }
}
