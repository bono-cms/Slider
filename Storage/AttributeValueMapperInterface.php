<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Slider\Storage;

interface AttributeValueMapperInterface
{
    /**
     * Deletes all data associated with particular image
     * 
     * @param string $id Image ID
     * @return boolean
     */
    public function deleteAllByImageId($id);

    /**
     * Fetches attribute data associated with image ID
     * 
     * @param string $imageId
     * @return array
     */
    public function fetchAll($imageId);
}
