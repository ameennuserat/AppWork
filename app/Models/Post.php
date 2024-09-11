<?php

namespace App\Models;

use App\Models\Worker;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Post_photos;

class Post extends Model
{
    use HasFactory;
    protected $table = 'posts';
    protected $fillable = ['content','price','status','worker_id'];

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }
    public function review()
    {
        return $this->hasMany(WorkerReview::class);
    }
    public function photos()
    {
        return $this->belongs(Post_photos::class);
    }
}
