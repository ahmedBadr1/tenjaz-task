<?php

namespace App\Enums;

enum UserTypes :int
{
    use EnumHelper ;
    case Normal = 1;
    case Silver = 2;
    case Gold = 3;

}
