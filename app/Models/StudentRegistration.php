<?php

namespace App\Models;

use App\Models\Regencie;
use App\Models\District;
use App\Models\Province;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'ktp_address',
        'current_address',
        'province_id',
        'regency_id',
        'district_id',
        'nationality',
        'birth_date',
        'birth_place',
        'gender',
        'status',
        'religion',
        'photo',
    ];



    public function province()
    {
        return $this->belongsTo(Province::class);
    }

        public function regency()
    {
        return $this->belongsTo(Regencie::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

}
