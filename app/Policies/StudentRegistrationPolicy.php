<?php
// app/Policies/StudentRegistrationPolicy.php
namespace App\Policies;

use App\Models\User;
use App\Models\StudentRegistration;

class StudentRegistrationPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, StudentRegistration $studentRegistration): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, StudentRegistration $studentRegistration): bool
    {
        return true;
    }

    public function delete(User $user, StudentRegistration $studentRegistration): bool
    {
        return $user->role == 'admin';
    }

}