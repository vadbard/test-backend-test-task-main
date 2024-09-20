CREATE TABLE IF NOT EXISTS products
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    uuid VARCHAR(255) NOT NULL COMMENT 'UUID товара',
    category VARCHAR(255) NOT NULL COMMENT 'Категория товара',
    is_active TINYINT(1) DEFAULT TRUE NOT NULL COMMENT 'Флаг активности',
    name TEXT DEFAULT '' NOT NULL COMMENT 'Тип услуги',
    description TEXT NULL COMMENT 'Описание товара',
    thumbnail VARCHAR(255) NULL COMMENT 'Ссылка на картинку',
    price INTEGER NOT NULL COMMENT 'Цена'
) COMMENT='Товары';

CREATE UNIQUE INDEX idx_product_uuid ON products (uuid);
