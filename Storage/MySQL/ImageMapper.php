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
use Slider\Storage\ImageMapperInterface;
use Krystal\Db\Sql\RawSqlFragment;

final class ImageMapper extends AbstractMapper implements ImageMapperInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return self::getWithPrefix('bono_module_slider_images');
    }

    /**
     * {@inheritDoc}
     */
    public static function getTranslationTable()
    {
        return self::getWithPrefix('bono_module_slider_images_translations');
    }

    /**
     * Returns shared columns to be selected
     * 
     * @return array
     */
    private function getColumns()
    {
        return array(
            self::getFullColumnName('id'),
            self::getFullColumnName('lang_id', self::getTranslationTable()),
            self::getFullColumnName('category_id'),
            self::getFullColumnName('name', self::getTranslationTable()),
            self::getFullColumnName('description', self::getTranslationTable()),
            self::getFullColumnName('order'),
            self::getFullColumnName('published'),
            self::getFullColumnName('link', self::getTranslationTable()),
            self::getFullColumnName('image'),
        );
    }
    
    /**
     * Fetches image's name by its associated id
     * 
     * @param string $id Image id
     * @return string
     */
    public function fetchNameById($id)
    {
    }

    /**
     * Update settings
     * 
     * @param array $settings
     * @return boolean
     */
    public function updateSettings(array $settings)
    {
        return $this->updateColumns($settings, array('order', 'published'));
    }

    /**
     * Fetches all associated image ids with their associated category id
     * 
     * @param string $categoryId
     * @return array
     */
    public function fetchIdsByCategoryId($categoryId)
    {
        return $this->db->select('id')
                        ->from(static::getTableName())
                        ->whereEquals('category_id', $categoryId)
                        ->queryAll('id');
    }

    /**
     * Fetches image id by its associated category id
     * 
     * @param string $id
     * @return string
     */
    public function fetchCategoryIdById($id)
    {
        return $this->findColumnByPk($id, 'category_id');
    }

    /**
     * Fetches random published slide image associated with provided category id
     * 
     * @param string $categoryId
     * @return array
     */
    public function fetchRandomPublishedByCategoryId($categoryId)
    {
        return $this->createEntitySelect($this->getColumns())
                    ->whereEquals(self::getFullColumnName('category_id'), $categoryId)
                    ->andWhereEquals(self::getFullColumnName('published'), '1')
                    ->andWhereEquals(self::getFullColumnName('lang_id', self::getTranslationTable()), $this->getLangId())
                    ->orderBy()
                    ->rand()
                    ->query();
    }

    /**
     * Fetch all images
     * 
     * @param boolean $published Whether to fetch only published records
     * @param string $categoryId Optional category ID filter
     * @param integer $page Current page number
     * @param integer $itemsPerPage Per page count
     * @return array
     */
    public function fetchAll($published, $categoryId, $page, $itemsPerPage)
    {
        // Columns to be selected
        $columns = array_merge(
            $this->getColumns(), 
            array(CategoryMapper::getFullColumnName('name') => 'category_name')
        );

        $db = $this->createEntitySelect($columns)
                    // Category relation
                   ->leftJoin(CategoryMapper::getTableName())
                   ->on()
                   ->equals(CategoryMapper::getFullColumnName('id'), new RawSqlFragment(self::getFullColumnName('category_id')))
                   // Filtering condition
                   ->whereEquals(self::getFullColumnName('lang_id', self::getTranslationTable()), $this->getLangId());

        if ($categoryId !== null) {
            $db->andWhereEquals(self::getFullColumnName('category_id'), $categoryId);
        }

        if ($published === true) {
            $db->andWhereEquals(self::getFullColumnName('published'), '1')
               ->orderBy(new RawSqlFragment(sprintf('`order`, CASE WHEN `order` = 0 THEN %s END DESC', self::getFullColumnName('id'))));
        } else {
            $db->orderBy(self::getFullColumnName('id'))
               ->desc();
        }

        if ($page !== null && $itemsPerPage !== null) {
            $db->paginate($page,$itemsPerPage);
        }

        return $db->queryAll();
    }

    /**
     * Fetches an image by its associated id
     * 
     * @param string $id
     * @return array
     */
    public function fetchById($id)
    {
        return $this->findEntity($this->getColumns(), $id, true);
    }
}
