<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionUser extends Model
{
    use HasFactory;

    protected $table = 'subscriptions';

    protected $fillable = [
        'user_id',
        'subscription_plan_id',
        'start_date',
        'end_date',
        'status',
        'invoice_id',
        'created_at',
        'updated_at',
    ];

    protected $appends = [
        'start_date_formatted',
        'end_date_formatted',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id', 'id');
    }

    public function getStartDateFormattedAttribute($value)
    {
        return $this->asDateTime($value)->format('d F Y');
    }

    public function getEndDateFormattedAttribute($value)
    {
        return $this->asDateTime($value)->format('d F Y');
    }
}
