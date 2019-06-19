<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Slider\Collection;

use Krystal\Stdlib\ArrayCollection;

final class AttributeTypeCollection extends ArrayCollection
{
    const TYPE_TEXT = 1;
    const TYPE_RICH = 2;
    const TYPE_DESCRIPTION = 3;
    const TYPE_NUMBER = 4;

    /**
     * {@inheritDoc}
     */
    protected $collection = array(
        self::TYPE_TEXT => 'Text',
        self::TYPE_RICH => 'Rich editor',
        self::TYPE_DESCRIPTION => 'Description',
        self::TYPE_NUMBER => 'Number'
    );
}
