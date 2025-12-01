CREATE DATABASE IF NOT EXISTS sports_shop
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE sports_shop;

CREATE TABLE account (
  id               INT AUTO_INCREMENT PRIMARY KEY,
  username         VARCHAR(50) NOT NULL UNIQUE,
  password         VARCHAR(255) NOT NULL,
  decryptedPassword VARCHAR(255) NULL,
  email            VARCHAR(100) NOT NULL UNIQUE,
  role             ENUM('USER','ADMIN') NOT NULL DEFAULT 'USER',
  created_at       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE user (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  account_id  INT NOT NULL,
  name        VARCHAR(100) NOT NULL,
  address     VARCHAR(255),
  phone       VARCHAR(20),
  created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_user_account FOREIGN KEY (account_id)
    REFERENCES account(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE admin (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  account_id  INT NOT NULL,
  name        VARCHAR(100) NOT NULL,
  address     VARCHAR(255),
  phone       VARCHAR(20),
  created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_admin_account FOREIGN KEY (account_id)
    REFERENCES account(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE brand (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(100) NOT NULL,
  description VARCHAR(255),
  created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE category (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(100) NOT NULL,
  created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE product (
  id              INT AUTO_INCREMENT PRIMARY KEY,
  brand_id        INT NOT NULL,
  category_id     INT NOT NULL,
  title           VARCHAR(255) NOT NULL,
  date_publication DATE,
  description     TEXT,
  image           VARCHAR(255),
  price           DECIMAL(12,2) NOT NULL,
  number_sold     INT NOT NULL DEFAULT 0,
  number_in_stock INT NOT NULL DEFAULT 0,
  created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_product_brand FOREIGN KEY (brand_id)
    REFERENCES brand(id) ON DELETE RESTRICT,
  CONSTRAINT fk_product_category FOREIGN KEY (category_id)
    REFERENCES category(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE comment (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  user_id     INT NOT NULL,
  product_id  INT NOT NULL,
  star        TINYINT NOT NULL CHECK (star BETWEEN 1 AND 5),
  content     TEXT,
  created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_comment_user FOREIGN KEY (user_id)
    REFERENCES user(id) ON DELETE CASCADE,
  CONSTRAINT fk_comment_product FOREIGN KEY (product_id)
    REFERENCES product(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE orders (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  user_id       INT NOT NULL,
  total_cost    DECIMAL(12,2) NOT NULL,
  customer_name VARCHAR(100) NOT NULL,
  address       VARCHAR(255) NOT NULL,
  phone         VARCHAR(20) NOT NULL,
  status        ENUM('PENDING','CONFIRMED','SHIPPING','COMPLETED','CANCELLED')
                  NOT NULL DEFAULT 'PENDING',
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_orders_user FOREIGN KEY (user_id)
    REFERENCES user(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE order_detail (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  order_id    INT NOT NULL,
  product_id  INT NOT NULL,
  number      INT NOT NULL,
  total_cost  DECIMAL(12,2) NOT NULL,
  created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_orderdetail_order FOREIGN KEY (order_id)
    REFERENCES orders(id) ON DELETE CASCADE,
  CONSTRAINT fk_orderdetail_product FOREIGN KEY (product_id)
    REFERENCES product(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Seed basic categories & brands
INSERT INTO category(name) VALUES ('Vợt'), ('Giày'), ('Phụ kiện');
INSERT INTO brand(name, description) VALUES
('Yonex', 'Thương hiệu Nhật chuyên vợt cầu lông'),
('Lining', 'Thương hiệu Trung Quốc'),
('Nike', 'Thương hiệu Mỹ'),
('Adidas', 'Thương hiệu Đức');
