<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $table = 'schools';

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'subject_school');
    }

    public function grades()
    {
        return $this->belongsToMany(Grade::class, 'grade_school');
    }

    public function files()
    {
        return $this->hasMany(FileSubmission::class, 'school_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
