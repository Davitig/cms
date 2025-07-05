<?php

namespace App\Enums;

enum FileSize: string
{
    use EnumTrait;

    case BYTE = 'B';
    case KILOBYTE = 'kB';
    case MEGABYTE = 'MB';
    case GIGABYTE = 'GB';
    case TERABYTE = 'TB';
    case PETABYTE = 'PB';
    case EXABYTE = 'EB';
    case ZETTABYTE = 'ZB';
    case YOTTABYTE = 'YB';
}
