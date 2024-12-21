<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
    protected $table = 'purchase_details';
    protected $primaryKey = 'purchase_detail_id'; // Tentukan primary key yang benar
    
    protected $fillable = [
        'purchase_id',
        'product_id',
        'quantity',
        'subtotal'
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id', 'purchase_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}