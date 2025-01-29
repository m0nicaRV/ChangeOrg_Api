<?php

namespace App\Policies;

use App\Models\Peticione;
use App\User;
use Illuminate\Auth\Access\Response;

class PeticionePolicy
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
    public function view(User $user, Peticione $peticione): bool
    {
        //
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Peticione $peticione): bool
    {
        //
        if ($user->role_id==1 || $user->id==$peticione->user_id) {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Peticione $peticione): bool
    {
        if ($user->role_id==1 || $user->id==$peticione->user_id) {
            return true;
        }
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Peticione $peticione): bool
    {
        if ($user->role_id==1 || $user->id==$peticione->user_id) {
            return true;
        }
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Peticione $peticione): bool
    {
        //
        return true;
    }

    public function before(User $user, string $ability): ?bool
    {
        if ($user->role_id==1) {
            return true;
        }

    }

    public function cambiarEstado(User $user, Peticione $peticione): bool
    {
        return false;
    }
    
}
