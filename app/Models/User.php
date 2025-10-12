<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\User\User as UserModel;

class User extends UserModel
{
    // This is just an alias to maintain backward compatibility
    // with existing tokens that reference App\Models\User
}
