<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Caixinha;
use App\Models\User;

class CaixinhaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Caixinha $caixinha): bool
    {
        return $caixinha->user_id === null || $user->id === $caixinha->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Caixinha $caixinha): bool
    {
        return $caixinha->user_id === null || $user->id === $caixinha->user_id;
    }

    public function delete(User $user, Caixinha $caixinha): bool
    {
        return $caixinha->user_id === null || $user->id === $caixinha->user_id;
    }

    public function restore(User $user, Caixinha $caixinha): bool
    {
        return $caixinha->user_id === null || $user->id === $caixinha->user_id;
    }

    public function forceDelete(User $user, Caixinha $caixinha): bool
    {
        return $caixinha->user_id === null || $user->id === $caixinha->user_id;
    }
}
