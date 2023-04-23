<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Slider\View;

use Krystal\Widget\Bootstrap5\Carousel\CarouselMaker;

final class SimpleCarousel
{
    /**
     * Renders accordion
     * 
     * @param array $days An array of slides
     * @param string $size Required size
     * @param string $button Button text
     * @return string Rendered carousel
     */
    public static function render(array $slides, $size, $button)
    {
        $items = [];

        foreach ($slides as $slide) {
            $items[] = [
                'src' => $slide->getImageUrl($size),
                'alt' => $slide->getName(),
                'caption' => [ // Adding optional caption
                    'title' => $slide->getName(),
                    'description' => $slide->getDescription(),
                    'button' => [
                        'href' => $slide->getLink(),
                        'text' => $button
                    ]
                ]
            ];
        }

        $carousel = new CarouselMaker($items);
        return $carousel->render();
    }
}
