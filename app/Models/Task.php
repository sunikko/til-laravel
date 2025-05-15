<?php
namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;
use MongoDB\Laravel\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Eloquent
{
    use SoftDeletes, HasFactory;
    
    protected $connection = 'mongodb';
    protected $collection = 'project_tasks';
    protected $fillable = ['name', 'description', 'secure_token'];
    protected $dates = ['deleted_at'];
    protected $guarded = [];
    protected string|null $secure_token;
}
