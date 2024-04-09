<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CapitalGoods extends Model
{
    use HasFactory;
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'capital_goods';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'capital_goods_item',
        'cap_suplier_info',
        'cap_quantity',
        'cap_uom',
        'cap_converted_val',
        'cap_emission_factor',
        'cap_total_emissions',
        'cap_status',
    ];
}
