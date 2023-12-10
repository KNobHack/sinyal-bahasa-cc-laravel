<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Gate;

class Event extends Model
{
    use HasFactory;

    // Relationship
    public function host(): Relation
    {
        return $this->belongsTo(User::class);
    }

    public function participants(): Relation
    {
        return $this->belongsToMany(User::class, 'event_participant');
    }

    // Function
    public function join($user_id = null): void
    {
        if ($user_id == null) {
            $user_id = auth('api')->id();
        }

        Gate::authorize('join', [$this, $user_id]);

        $this->participants()->attach($user_id);
    }

    public function disjoin($user_id = null): void
    {
        if ($user_id == null) {
            $user_id = auth('api')->id();
        }

        Gate::authorize('disjoin', [$this, $user_id]);

        $this->participants()->detach($user_id);
    }
}
