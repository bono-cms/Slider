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
use Slider\Storage\AttributeValueMapperInterface;
use Krystal\Db\Sql\RawSqlFragment;

final class AttributeValueMapper extends AbstractMapper implements AttributeValueMapperInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return self::getWithPrefix('bono_module_slider_category_attribute_values');
    }

    /**
     * Deletes all data associated with particular image
     * 
     * @param string $id Image ID
     * @return boolean
     */
    public function deleteAllByImageId($id)
    {
        return $this->deleteByColumn('image_id', $id);
    }

    /**
     * Fetches attribute data associated with image ID
     * 
     * @param string $imageId
     * @return array
     */
    public function fetchAll($imageId)
    {
        // Columns to be selected
        $columns = array(
            self::getFullColumnName('value') => 'value',
            AttributeGroupMapper::getFullColumnName('name') => 'group_name',
            AttributeGroupMapper::getFullColumnName('id') => 'group_id',
        );

        return $this->db->select($columns)
                        ->from(self::getTableName())
                        ->innerJoin(AttributeGroupMapper::getTableName())
                        ->on()
                        ->equals(self::getFullColumnName('group_id'), new RawSqlFragment(AttributeGroupMapper::getFullColumnName('id')))
                        ->rawAnd()
                        ->equals(self::getFullColumnName('image_id'), $imageId)
                        ->orderBy(AttributeGroupMapper::getFullColumnName('id'))
                        ->desc()
                        ->queryAll();
    }
}
