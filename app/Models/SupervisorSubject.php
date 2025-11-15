<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupervisorSubject extends Model
{
    use HasFactory;

    protected $fillable = [
        'supervisor_id',
        'subject_id',
        'school_id',
    ];

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
