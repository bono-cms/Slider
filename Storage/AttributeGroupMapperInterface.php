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

interface AttributeGroupMapperInterface
{
    /**
     * Deletes a group by its ID
     * 
     * @param string $id
     * @return boolean
     */
    public function deleteById($id);

    /**
     * Fetches group data by its ID
     * 
     * @param string $id
     * @return array
     */
    public function fetchById($id);

    /**
     * Fetches all groups associated with category ID
     * 
     * @param string $id Category ID
     * @return array
     */
    public function fetchAll($id);
}
