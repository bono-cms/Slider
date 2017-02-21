<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Slider\Service;

use Slider\Storage\AttributeValueMapperInterface;

final class AttributeValueManager
{
    /**
     * Any compliant value mapper
     * 
     * @var \Slider\Storage\AttributeValueMapperInterface
     */
    private $attributeValueMapper;

    /**
     * State initialization
     * 
     * @param \Slider\Storage\AttributeValueMapperInterface $attributeValueMapper
     * @return void
     */
    public function __construct(AttributeValueMapperInterface $attributeValueMapper)
    {
        $this->attributeValueMapper = $attributeValueMapper;
    }
}
