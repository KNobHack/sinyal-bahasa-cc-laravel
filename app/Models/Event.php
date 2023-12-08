<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class Event extends Model
{
    use HasFactory;

    // Relationship
    public function host(): Relation
    {
        return $this->belongsTo(User::class);
    }
}
