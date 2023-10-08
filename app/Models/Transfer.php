<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Transfer extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'transfer';

    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
    ];

    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'transfer_detail')
            ->withPivot('date', 'time', 'amount');
    }
}
