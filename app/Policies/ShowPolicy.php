<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Show;
use App\Nova\Film;
use App\Models\Tag;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShowPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function viewAny(User $user): bool
   {
       return true;
   }

   public function view(User $user, Show $show): bool
   {
        return false;
        //return true;
   }

   public function create(User $user): bool
   {
        return false;
   }

   public function delete(User $user, Show $show): bool
   {
        return false;
       
   }

   public function update(User $user, Show $show): bool
   {
        return false;
   }
}
