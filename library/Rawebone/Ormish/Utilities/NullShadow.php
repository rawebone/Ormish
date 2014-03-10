<?php

namespace Rawebone\Ormish\Utilities;

/**
 * NullShadow provides an interface consistent with Shadow but which will never
 * track changes. This allows simplistic handling of changes in our Entities
 * while providing performance benefits when in read-only mode for a table.
 */
class NullShadow extends Shadow
{
    public function update(array $data)
    {
        // Do not update.
    }
}
