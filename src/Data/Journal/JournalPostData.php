<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Journal;

/**
 * The body of `journal` POST: the Journal table without the `TaskID` Cin7 assigns and the
 * read-only `JournalNumber`. The PUT body is `JournalPutData`.
 *
 * @see docs/data.md
 */
final class JournalPostData extends AbstractJournalData
{
}
