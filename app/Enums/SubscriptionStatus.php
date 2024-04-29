<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static New()
 * @method static static Active()
 * @method static static Canceled()
 * @method static static NonRenewing()
 */
final class SubscriptionStatus extends Enum
{
    const New = 'new';
    const Active = 'active';
    const Canceled = 'canceled';
    const NonRenewing = 'non_renewing';
}
