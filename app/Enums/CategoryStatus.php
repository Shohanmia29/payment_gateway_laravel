<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static InActive()
 * @method static static Active()
 */
final class CategoryStatus extends Enum
{
    const Active =   1;
    const InActive =   0;

}
