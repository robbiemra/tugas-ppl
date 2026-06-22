<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoryArchive extends Model
{
    protected $fillable = [
        'genre',
        'location',
        'node_content',
        'choices_json',
        'image_url',
        'usage_count',
    ];

    protected $casts = [
        'choices_json' => 'array',
    ];

    /**
     * Get a random archived story for given genre and location
     */
    public static function getRandomByGenreAndLocation($genre, $location)
    {
        return static::where('genre', $genre)
            ->where('location', $location)
            ->inRandomOrder()
            ->first();
    }

    /**
     * Get multiple random archived stories (up to $limit)
     */
    public static function getRandomMultipleByGenreAndLocation($genre, $location, $limit = 5)
    {
        return static::where('genre', $genre)
            ->where('location', $location)
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    /**
     * Increment usage count
     */
    public function incrementUsageCount()
    {
        $this->increment('usage_count');
    }
}
