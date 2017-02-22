<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Slider\Storage\MySQL;

use Cms\Storage\MySQL\AbstractMapper;
use Slider\Storage\AttributeGroupMapperInterface;

final class AttributeGroupMapper extends AbstractMapper implements AttributeGroupMapperInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return self::getWithPrefix('bono_module_slider_category_attribute_groups');
    }

    /**
     * Deletes a group by its ID
     * 
     * @param string $id
     * @return boolean
     */
    public function deleteById($id)
    {
        return $this->deleteByPk($id);
    }

    /**
     * Fetches group data by its ID
     * 
     * @param string $id
     * @return array
     */
    public function fetchById($id)
    {
        return $this->findByPk($id);
    }

    /**
     * Fetches all groups associated with category ID
     * 
     * @param string $id Category ID
     * @return array
     */
    public function fetchAll($id)
    {
        return $this->db->select('*')
                        ->from(self::getTableName())
                        ->whereEquals('category_id', $id)
                        ->orderBy('id')
                        ->desc()
                        ->queryAll();
    }
}
