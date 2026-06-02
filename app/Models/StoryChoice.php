<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\StoryNode;

class StoryChoice extends Model
{
    use HasFactory;

    protected $fillable = ['story_node_id', 'choice_text', 'next_node_id'];

    // Relasi: Pilihan ini milik sebuah cerita (node)
    public function node()
    {
        return $this->belongsTo(StoryNode::class, 'story_node_id');
    }

    // Relasi: Pilihan ini mengarah ke cerita (node) berikutnya
    public function nextNode()
    {
        return $this->belongsTo(StoryNode::class, 'next_node_id');
    }
}
