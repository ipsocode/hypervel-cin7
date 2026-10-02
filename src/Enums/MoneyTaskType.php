<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * The type of a money task.
 *
 * @see docs/data.md
 */
enum MoneyTaskType: string
{
    case SpendMoney = 'Spend Money';
    case ReceiveMoney = 'Receive Money';
    case TransferMoney = 'Transfer Money';
}
