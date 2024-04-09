<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WasteManagement extends Model
{
    use HasFactory;
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'waste_mangement';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'wa_waste_type',
        'wa_region_id',
        'wa_ef_data',
        'wa_treatment_type',
        'wa_activity',
        'wa_uom',
        'wa_quantity',
        'wa_converted_val',
        'wa_emission_factor',
        'wa_total_emissions',
        'wa_status',
    ];
}
