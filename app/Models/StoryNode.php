<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\StoryChoice;

class StoryNode extends Model
{
    use HasFactory;

    protected $fillable = ['genre', 'location', 'content', 'is_ending'];

    // Relasi: Satu cerita bisa memiliki banyak pilihan
    public function choices()
    {
        return $this->hasMany(StoryChoice::class);
    }
}