<?php

namespace App\Services;

use App\Models\User;

/**
 * Class UserService
 * @package App\Services
 */
class UserService
{
  public static function checkUserExists($id) 
  {
    return User::where('id', $id)->exists();
  }
}
