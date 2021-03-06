
/* Slider categories */
DROP TABLE IF EXISTS `bono_module_slider_category`; 
CREATE TABLE `bono_module_slider_category` (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
	`class` varchar(255) NOT NULL COMMENT 'Class to simplify rendering',
    `width` FLOAT NOT NULL,
    `height` FLOAT NOT NULL,
    `quality` INT NOT NULL COMMENT 'Quality for images'
) DEFAULT CHARSET=UTF8 ENGINE = InnoDB;

/* Slider images */
DROP TABLE IF EXISTS `bono_module_slider_images`;
CREATE TABLE `bono_module_slider_images` (
	`id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`category_id` INT NOT NULL,
	`order` INT NOT NULL,
	`published` varchar(1) NOT NULL,
	`image` varchar(254) NOT NULL,

    FOREIGN KEY (category_Id) REFERENCES bono_module_slider_category(id) ON DELETE CASCADE
) DEFAULT CHARSET=UTF8 ENGINE = InnoDB;

DROP TABLE IF EXISTS `bono_module_slider_images_translations`;
CREATE TABLE `bono_module_slider_images_translations` (
    `id` INT NOT NULL,
    `lang_id` INT NOT NULL,
    `name` varchar(254) NOT NULL,
    `description` LONGTEXT NOT NULL,
    `link` varchar(255) NOT NULL,

    FOREIGN KEY (id) REFERENCES bono_module_slider_images(id) ON DELETE CASCADE,
    FOREIGN KEY (lang_id) REFERENCES bono_module_cms_languages(id) ON DELETE CASCADE
) DEFAULT CHARSET=UTF8 ENGINE = InnoDB;

/* Slider attributes */
DROP TABLE IF EXISTS `bono_module_slider_category_attribute_groups`;
CREATE TABLE `bono_module_slider_category_attribute_groups` (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT 'Attribute group unique ID',
	`category_Id` INT NOT NULL COMMENT 'Attached category ID',
	`name` varchar(255) NOT NULL,
    `type` varchar(1) NOT NULL COMMENT 'Type constant',

    FOREIGN KEY (category_Id) REFERENCES bono_module_slider_category(id) ON DELETE CASCADE
) DEFAULT CHARSET=UTF8 ENGINE = InnoDB;


DROP TABLE IF EXISTS `bono_module_slider_category_attribute_values`;
CREATE TABLE `bono_module_slider_category_attribute_values` (
	`id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT 'Attribute value unique ID',
	`group_Id` INT NOT NULL COMMENT 'Attached category group ID',
    `image_id` INT NOT NULL COMMENT 'Attached image ID',
    `value` varchar(255) NOT NULL,

    FOREIGN KEY (group_Id) REFERENCES bono_module_slider_category_attribute_groups(id) ON DELETE CASCADE,
    FOREIGN KEY (image_id) REFERENCES bono_module_slider_images(id) ON DELETE CASCADE
) DEFAULT CHARSET=UTF8 ENGINE = InnoDB;
