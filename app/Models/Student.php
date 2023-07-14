<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    public $timestamps = false;

    function getGenderName() {
        $genderMap = [0 => "nam", 1 => "nữ", 2 => "khác"];
        return $genderMap[$this->gender];
    }
    
}
