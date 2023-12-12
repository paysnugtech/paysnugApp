<?php

namespace App\Policies;

use App\Enums\RoleEnum;
use App\Models\CardType;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CardTypePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {

        $roles = [
            RoleEnum::ADMIN->value,
            RoleEnum::USER->value
        ];
        
        return in_array($user->role->name, $roles);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CardType $idCardType): bool
    {

        $roles = [
            RoleEnum::ADMIN->value,
            RoleEnum::USER->value
        ];
        
        return in_array($user->role->name, $roles);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {

        $roles = [
            RoleEnum::ADMIN->value,
            RoleEnum::USER->value
        ];
        
        return in_array($user->role->name, $roles);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CardType $CardType): bool
    {

        $roles = [
            RoleEnum::ADMIN->value
        ];
        
        return in_array($user->role->name, $roles);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CardType $idCardType): bool
    {

        $roles = [
            RoleEnum::ADMIN->value,
            RoleEnum::USER->value
        ];
        
        return in_array($user->role->name, $roles);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CardType $idCardType): bool
    {

        $roles = [
            RoleEnum::ADMIN->value,
            RoleEnum::USER->value
        ];
        
        return in_array($user->role->name, $roles);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CardType $idCardType): bool
    {

        $roles = [
            RoleEnum::ADMIN->value,
            RoleEnum::USER->value
        ];
        
        return in_array($user->role->name, $roles);
    }
}
