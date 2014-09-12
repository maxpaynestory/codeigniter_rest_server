use `bsf_wishlist`;

CREATE TABLE `bsf_wishlist`.`language` (
  `id` INTEGER(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `lang` VARCHAR(3) NOT NULL,
  `currency` VARCHAR(3) NOT NULL,
  `is_defualt` BOOLEAN NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `unq_lang`(`lang`)
)
ENGINE = InnoDB;

CREATE TABLE `bsf_wishlist`.`item_translation` (
  `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  `lang_id` INTEGER UNSIGNED NOT NULL,
  `item_id` INTEGER UNSIGNED NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  `price` decimal(15,2)  NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_item_translation_item` FOREIGN KEY `FK_item_translation_item` (`item_id`)
    REFERENCES `item` (`idItems`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_item_translation_lang` FOREIGN KEY `FK_item_translation_lang` (`lang_id`)
    REFERENCES `lang` (`id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT
)
ENGINE = InnoDB;

INSERT INTO `lang` VALUES (1,'en','USD',1);
INSERT INTO `lang` VALUES (2,'de','EUR',0);


INSERT INTO item_translation (lang_id, item_id,name, price)
SELECT 1, idItems, name, price
FROM item

ALTER TABLE `bsf_wishlist`.`item` DROP COLUMN `name`,
 DROP COLUMN `price`;

