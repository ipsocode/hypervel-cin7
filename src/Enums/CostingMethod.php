<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * A product's costing method.
 *
 * @see docs/data.md
 */
enum CostingMethod: string
{
    case Fifo = 'FIFO';
    case SpecialBatch = 'Special - Batch';
    case SpecialSerialNumber = 'Special - Serial Number';
    case FifoSerialNumber = 'FIFO - Serial Number';
    case FifoBatch = 'FIFO - Batch';
    case FefoBatch = 'FEFO - Batch';
    case FefoSerialNumber = 'FEFO - Serial Number';
}
