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

use Slider\Storage\AttributeGroupMapperInterface;

final class AttributeGroupManager
{
    /**
     * Any compliatn attribute group mapper
     * 
     * @var \Slider\Storage\AttributeGroupMapperInterface
     */
    private $attributeGroupMapper;

    /**
     * State initialization
     * 
     * @param \Slider\Storage\AttributeGroupMapperInterface $attributeGroupMapper
     * @return void
     */
    public function __construct(AttributeGroupMapperInterface $attributeGroupMapper)
    {
        $this->attributeGroupMapper = $attributeGroupMapper;
    }
}
