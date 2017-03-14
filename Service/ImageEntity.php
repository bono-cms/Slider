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

use Krystal\Stdlib\VirtualEntity;

final class ImageEntity extends VirtualEntity
{
    /**
     * Gets attribute value by group ID
     * 
     * @param string $groupId
     * @return string
     */
    public function getAttribute($groupId)
    {
        $attributes = $this->getAttributes();

        foreach ($attributes as $attribute) {
            if ($attribute['group_id'] == $groupId) {
                return $attribute['value'];
            }
        }

        return null;
    }

    /**
     * Returns image URL
     * 
     * @param string $size
     * @return string
     */
    public function getImageUrl($size)
    {
        return $this->getImageBag()->getUrl($size);
    }

    /**
     * Tells whether a slide image has a link
     * 
     * @return boolean
     */
    public function hasLink()
    {
        return $this->getLink() !== '';
    }
}
