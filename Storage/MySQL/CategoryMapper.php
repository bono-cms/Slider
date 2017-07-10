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
use Slider\Storage\CategoryMapperInterface;
use Krystal\Db\Sql\RawSqlFragment;

final class CategoryMapper extends AbstractMapper implements CategoryMapperInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return self::getWithPrefix('bono_module_slider_category');
    }

    /**
     * Fetches a list
     * 
     * @return array
     */
    public function fetchList()
    {
        return $this->db->select(array('id', 'name'))
                        ->from(self::getTableName())
                        ->queryAll();
    }

    /**
     * Fetches category's id by its associated class name
     * 
     * @param string $class Category's class name
     * @return string
     */
    public function fetchIdByClass($class)
    {
        return $this->db->select('id')
                        ->from(self::getTableName())
                        ->whereEquals('class', $class)
                        ->queryScalar();
    }

    /**
     * Fetches category's name by its associated id
     * 
     * @param string $id Category id
     * @return string
     */
    public function fetchNameById($id)
    {
        return $this->findColumnByPk($id, 'name');
    }

    /**
     * Fetches category data by its associated id
     * 
     * @param string $id Category id
     * @return array
     */
    public function fetchById($id)
    {
        return $this->findByPk($id);
    }

    /**
     * Fetches all categories
     * 
     * @return array
     */
    public function fetchAll()
    {
        $columns = array(
            self::getFullColumnName('id'),
            self::getFullColumnName('name'),
            self::getFullColumnName('class'),
            self::getFullColumnName('width'),
            self::getFullColumnName('height'),
            self::getFullColumnName('quality')
        );

        return $this->db->select($columns)
                        ->count(ImageMapper::getFullColumnName('id'), 'slides_count')
                        ->from(ImageMapper::getTableName())
                        ->rightJoin(self::getTableName())
                        ->on()
                        ->equals(self::getFullColumnName('id'), new RawSqlFragment(ImageMapper::getFullColumnName('category_id')))
                        ->groupBy(self::getFullColumnName('id'))
                        ->orderBy(self::getFullColumnName('id'))
                        ->desc()
                        ->queryAll();
    }

    /**
     * Deletes a category by its associated id
     * 
     * @param string $id Category id
     * @return boolean
     */
    public function deleteById($id)
    {
        return $this->deleteByPk($id);
    }
}
