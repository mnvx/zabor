<?php

namespace Mnv\Zabor\Models;

use Model;

/**
 * Model
 */
class ElectricMeter extends Model
{
    protected $primaryKey = 'counter_number'; // fake

    /**
     * @var string The database table used by the model.
     */
    public $table = 'mnv_electric_meter';

    protected $fillable = [
        'user_id',
        'counter_number',
        'tariff_number',
    ];
}