<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DownStream extends Model
{
    use HasFactory;
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'down_stream';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ds_particulars',
        'ds_region',
        'ds_emission_factor_data',
        'ds_mode_of_transportation',
        'ds_type_of_transportation',
        'ds_one_way_return',
        'ds_from',
        'ds_to',
        'ds_activity',
        'ds_uom',
        'ds_quantity',
        'ds_converted_val',
        'ds_emission_factor',
        'ds_total_emissions'
    ];
}
