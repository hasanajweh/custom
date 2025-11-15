<?php
// app/Models/Subject.php
namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subject extends Model {
    use HasFactory, BelongsToTenant;

    protected $fillable = ['name', 'school_id']; // Make sure school_id is fillable

    /**
     * Get all file submissions for this subject
     */
    public function fileSubmissions()
    {
        return $this->hasMany(FileSubmission::class);
    }

    /**
     * Teachers assigned to this subject.
     */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('school_id')
            ->withTimestamps();
    }
}
