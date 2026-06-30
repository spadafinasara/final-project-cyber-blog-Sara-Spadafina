<?php

namespace App\Models;

use Illuminate\Support\Str;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Article extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'title', 'subtitle', 'body', 'image', 'user_id', 'category_id', 'is_accepted', 'slug'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function tags(){
        return $this->belongsToMany(Tag::class);
    }

    public function toSearchableArray(){
        return [
            'id' => $this->id,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'body' => $this->body,
            'category' => $this->category,
        ];
    }
    
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function readDuration(){
        $totalWords = Str::wordCount($this->body);
        $minutesToRead = round($totalWords / 200);
        return intval($minutesToRead);
    }

        public static function sanitizeBody($body)
            {
                $allowedTags = '<p><b><i><u><strong><em><a><ul><li><ol><br><h1><h2><h3><h4><blockquote>';

                // Rimuove tutti i tag NON nella lista bianca (es. <script>, <img>, <svg>)
                $clean = strip_tags($body, $allowedTags);

                // Rimuove attributi pericolosi anche dai tag permessi (onerror, onclick, ecc.)
                $clean = preg_replace('/\son\w+\s*=\s*"[^"]*"/i', '', $clean);
                $clean = preg_replace("/\son\w+\s*=\s*'[^']*'/i", '', $clean);

                // Rimuove href/src che iniziano con javascript:
                $clean = preg_replace('/(href|src)\s*=\s*["\']javascript:[^"\']*["\']/i', '', $clean);

                return $clean;
            }
}
