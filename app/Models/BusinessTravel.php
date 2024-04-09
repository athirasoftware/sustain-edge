<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessTravel extends Model
{
    use HasFactory;
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'business_travel';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'bu_particulars',
        'bu_region',
        'bu_emission_factor_data',
        'bu_mode_of_transportation',
        'bu_type_of_transportation',
        'bu_one_way_return',
        'bu_from',
        'bu_to',
        'bu_activity',
        'bu_uom',
        'bu_quantity',
        'bu_converted_val',
        'bu_emission_factor',
        'bu_total_emissions'
    ];
}
