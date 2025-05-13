namespace App\Models;

use MongoDB\Eloquent\Model as Eloquent;

class Task extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'tasks';
    protected $fillable = ['name', 'description'];
}
