<?php

namespace App\Enums\Models;

use App\Enums\Enum;

enum CollectionType: string
{
    use Enum;

    case ARTICLE = 'articles';
    case EVENT = 'events';
}
