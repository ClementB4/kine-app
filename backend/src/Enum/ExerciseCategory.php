<?php

namespace App\Enum;

enum ExerciseCategory: string
{
    case ANKLE = 'ankle';
    case CERVICAL = 'cervical';
    case KNEE = 'knee';
    case COD = 'cod';
    case CORE = 'core';
    case GAMMES = 'gammes';
    case HIP = 'hip';
    case MB = 'mb';
    case PONCAGE = 'poncage';
    case SHOULDER = 'shoulder';
    case SLED = 'sled';
    case SPRINT = 'sprint';
    case STRETCHING = 'stretching';
    case TAPING = 'taping';
    case WARMUP = 'warmup';
    case TEST = 'test';
    
}