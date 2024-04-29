<?php

namespace App\Services;

use App\Models\User;

/**
 * Class UserService
 * @package App\Services
 */
class UserService
{
  /**
   * Check if a user with the given ID exists.
   *
   * @param int $id The user ID to check.
   * @return bool True if a user with the given ID exists; otherwise, false.
   */
  public function checkUserExists(int $id): bool
  {
    return User::where('id', $id)->exists();
  }
}
