<?php
declare(strict_types=1);

namespace App\Constants;

enum AnswerType:string
{
    case OBJECT = 'object';
    case LIST = 'list';
    case ERROR = 'error';
}
