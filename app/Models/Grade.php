<?php
namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grade extends Model {
    use HasFactory, BelongsToTenant, SoftDeletes;

    protected $fillable = ['name', 'school_id', 'network_id']; // Make sure school_id is fillable

    /**
     * Get all file submissions for this grade
     */
    public function fileSubmissions()
    {
        return $this->hasMany(FileSubmission::class);
    }

    /**
     * Teachers assigned to this grade.
     */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('school_id')
            ->withTimestamps();
    }

    public function schools(): BelongsToMany
    {
        return $this->belongsToMany(School::class, 'grade_school')->withTimestamps();
    }

    public function networks(): BelongsToMany
    {
        return $this->belongsToMany(Network::class, 'grade_network')->withTimestamps();
    }
}
