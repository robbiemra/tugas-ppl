<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_name', 
        'selected_genre', 
        'character_visual', 
        'accumulated_story',
        'gender',
        'full_story_json',
        'user_id',
        'is_finished',
        'selected_location',
        'current_node_json',
        'story_step'
    ];

    // Di dalam model UserHistory, tambahkan ini di $casts
protected $casts = [
    'full_story_json' => 'array',
    'current_node_json' => 'array',
    'is_finished' => 'boolean',
];

// Tambahkan method ini di model UserHistory
public function addStoryPage($content, $imageUrl = null)
{
    $storyPages = $this->full_story_json ?: [];
    $storyPages[] = [
        'content' => $content,
        'image' => $imageUrl,
        'timestamp' => now()->toDateTimeString()
    ];
    $this->full_story_json = $storyPages;
    $this->save();
}
}
