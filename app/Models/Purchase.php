<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $primaryKey = 'purchase_id';
    
    protected $fillable = [
        'vendor_id',
        'user_id',
        'project_id',
        'total_amount',
        'purchase_date',
        'status'
    ];

    public function purchaseDetails()
    {
        return $this->hasMany(PurchaseDetail::class, 'purchase_id', 'purchase_id');
    }
}