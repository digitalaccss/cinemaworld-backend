<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Show;
use App\Models\Tag;
use Illuminate\Auth\Access\HandlesAuthorization;

class TagPolicy
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

   public function view(User $user): bool
   {
       return true;
   }

   public function create(User $user): bool
   {
       return true;
   }

   public function delete(User $user): bool
   {
       return true;
   }

   public function update(User $user): bool
   {
       return true;
   }

    public function attachShow(User $user, Tag $tag, Show $shows): bool
    {
        return false;
    }

    public function detachShow(User $user, Tag $tag, Show $shows): bool
    {
        return false;
    }

    public function detachAnyShow(User $user, Tag $tag, Show $shows): bool
    {
        return false;
    }

    public function attachAnyShow(User $user, Tag $tag): bool
    {
        return false;
    }
}
