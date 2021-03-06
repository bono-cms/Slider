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

use Krystal\Image\Tool\AbstractImageManagerFactory;

final class ImageManagerFactory extends AbstractImageManagerFactory
{
    /**
     * Default height
     * 
     * @var integer
     */
    private $height = 0;

    /**
     * Default width
     * 
     * @var integer
     */
    private $width = 0;

    /**
     * Quality for images
     * 
     * @var integer
     */
    private $quality;

    /**
     * Sets new quality
     * 
     * @param integer $quality
     * @return \Slider\Service\ImageManagerFactory
     */
    public function setQuality($quality)
    {
        $this->quality = (int) $quality;
        return $this;
    }

    /**
     * Sets new height
     * 
     * @param integer|float $height
     * @return \Slider\Service\ImageManagerFactory
     */
    public function setHeight($height)
    {
        $this->height = $height;
        return $this;
    }

    /**
     * Sets new width
     * 
     * @param integer|float $width
     * @return \Slider\Service\ImageManagerFactory
     */
    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    protected function getPath()
    {
        return '/data/uploads/module/slider/';
    }

    /**
     * {@inheritDoc}
     */
    protected function getConfig()
    {
        return array(
            'thumb' => array(
                'dimensions' => array(
                    // For administration
                    array(400, 200),
                    // For slides
                    array($this->width, $this->height)
                ),

                'quality' => $this->quality
            )
        );
    }
}
