<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeCommute extends Model
{
    use HasFactory;
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'employee_commute';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ec_particulars',
        'ec_region',
        'ec_emission_factor_data',
        'ec_mode_of_transportation',
        'ec_type_of_transportation',
        'ec_activity',
        'ec_uom',
        'ec_quantity',
        'ec_converted_val',
        'ec_emission_factor',
        'ec_total_emissions'
    ];
}
