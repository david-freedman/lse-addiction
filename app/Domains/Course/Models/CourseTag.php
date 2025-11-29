<?php

namespace App\Domains\Course\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class CourseTag extends Model
{
    protected $fillable = [
        'name',
        'slug',
    ];

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_tag', 'tag_id', 'course_id');
    }

    public static function createFromName(string $name): self
    {
        return self::create([
            'name' => $name,
            'slug' => Str::slug($name),
        ]);
    }

    public static function findOrCreateByName(string $name): self
    {
        $slug = Str::slug($name);

        return self::firstOrCreate(
            ['slug' => $slug],
            ['name' => $name]
        );
    }
}
