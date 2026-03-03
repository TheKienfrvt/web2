SET NAMES utf8mb4;
USE laravel;

-- nhà cung cấp
CREATE TABLE IF NOT EXISTS `supplier`  (
  `supplier_id` INT PRIMARY KEY AUTO_INCREMENT,
  `supplier_name` varchar(255) NOT NULL,
  `supplier_phone` varchar(10) NOT NULL,
  `supplier_address` varchar(255) NOT NULL
) ENGINE=InnoDB;

-- người dùng
-- khi xóa user (user.status = 'đã xóa') thì các đơn hàng của user đó vẫn còn
-- email là duy nhất
CREATE TABLE IF NOT EXISTS `user`  (
  `user_id` INT PRIMARY KEY AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) UNIQUE NOT NULL,
  `sex` enum('nam','nữ') DEFAULT NULL,
  `phone_number` varchar(10) NOT NULL,
  `dob` date DEFAULT NULL,
  `status` enum('mở','khóa','đã xóa') NOT NULL DEFAULT 'mở',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `avatar_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB;

-- Nhân sự
CREATE TABLE IF NOT EXISTS `employee` (
  `employee_id` INT PRIMARY KEY AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone_number` varchar(10) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `status` enum('Active','Inactive','On Leave') NOT NULL DEFAULT 'Active',
  `manager_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB;

-- danh mục sản phẩm
CREATE TABLE IF NOT EXISTS `category`  (
  `category_id` varchar(255) PRIMARY KEY,
  `category_name` varchar(255) NOT NULL,
  `status` enum('hiện','ẩn') NOT NULL DEFAULT 'hiện',
  `description` TEXT NULL
) ENGINE=InnoDB;

-- sản phẩm
CREATE TABLE IF NOT EXISTS `product`  (
  `product_id` INT PRIMARY KEY AUTO_INCREMENT,
  `product_name` varchar(255) NOT NULL,
  `category_id` varchar(255) NOT NULL,
  `stock` INT NOT NULL DEFAULT 0,
  `price` INT NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` enum('hiện','ẩn','đã xóa') NOT NULL DEFAULT 'hiện',
  `image_url` varchar(255) DEFAULT NULL,
  FOREIGN KEY (category_id) REFERENCES category(category_id)
) ENGINE=InnoDB;


-- phiếu nhập hàng
CREATE TABLE IF NOT EXISTS `receipt`  (
  `receipt_id` INT PRIMARY KEY AUTO_INCREMENT,
  `supplier_id` INT,
  `order_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `status` ENUM('đang chờ','đã nhận','đã hủy') DEFAULT 'đang chờ',
  FOREIGN KEY (supplier_id) REFERENCES supplier(supplier_id)
) ENGINE=InnoDB;

-- địa chỉ người dùng
CREATE TABLE IF NOT EXISTS `address`  (
  `address_id` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `address` varchar(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES user(user_id)
) ENGINE=InnoDB;

-- Chi tiết phiếu nhập hàng
CREATE TABLE IF NOT EXISTS `receipt_detail`  (
  `receipt_detail_id` INT PRIMARY KEY AUTO_INCREMENT,
  `receipt_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `quantity` INT NOT NULL,
  `price` INT NOT NULL,
  FOREIGN KEY (receipt_id) REFERENCES receipt(receipt_id),
  FOREIGN KEY (product_id) REFERENCES product(product_id)
);

-- kho/tồn kho
CREATE TABLE IF NOT EXISTS `inventory`  (
  `inventory_id` INT PRIMARY KEY AUTO_INCREMENT,
  `product_id` INT,
  `quantity` INT NOT NULL,
  `type` ENUM('nhập hàng','xuất hàng','điều chỉnh') NOT NULL,
  `reference` VARCHAR(255),              -- mã đơn hàng hoặc phiếu nhập
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (product_id) REFERENCES product(product_id)
) ENGINE=InnoDB;

-- giỏ hàng
CREATE TABLE IF NOT EXISTS `cart`  (
  `cart_id` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES user(user_id)
) ENGINE=InnoDB;

-- chi tiết giỏ hàng
CREATE TABLE IF NOT EXISTS `cart_item`  (
  `item_id` INT PRIMARY KEY AUTO_INCREMENT,
  `cart_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `quantity` INT NOT NULL CHECK (`quantity` > 0),
  FOREIGN KEY (cart_id) REFERENCES cart(cart_id),
  FOREIGN KEY (product_id) REFERENCES product(product_id)
) ENGINE=InnoDB;




-- đơn hàng
CREATE TABLE IF NOT EXISTS `order`  (
  `order_id` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `address` varchar(255) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `delivery_date` timestamp NULL,
  `total_amount` int NOT NULL,
  `status` enum('đã nhận hàng','chờ xác nhận','đang giao','đã xác nhận','đã hủy') NOT NULL DEFAULT 'chờ xác nhận',
  `payment_method` enum('chuyển khoản','tiền mặt') NOT NULL,
  `created_by` enum('customer','admin') NOT NULL DEFAULT 'customer',
  FOREIGN KEY (user_id) REFERENCES user(user_id)
) ENGINE=InnoDB;

-- chi tiết đơn hàng
CREATE TABLE IF NOT EXISTS `order_detail`  (
  `order_detail_id` int PRIMARY KEY AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price` int NOT NULL,
  FOREIGN KEY (order_id) REFERENCES `order`(order_id),
  FOREIGN KEY (product_id) REFERENCES product(product_id)
) ENGINE=InnoDB;





-- các bảng dưới này sẽ sửa lại sau
CREATE TABLE IF NOT EXISTS `screen_detail`  (
  `screen_id` INT PRIMARY KEY AUTO_INCREMENT,
  `product_id` INT NOT NULL,
  `thuong_hieu` varchar(255) NOT NULL,
  `kich_thuoc_man_hinh` varchar(255) NOT NULL,
  `tang_so_quet` varchar(255) NOT NULL,
  `ti_le` varchar(255) NOT NULL,
  `tam_nen` varchar(255) NOT NULL,
  `do_phan_giai` varchar(255) NOT NULL,
  `khoi_luong` varchar(255) NOT NULL,
  `description` TEXT NULL,
  FOREIGN KEY (product_id) REFERENCES product(product_id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `laptop_gaming_detail`  (
  `laptop_gaming_id` INT PRIMARY KEY AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `thuong_hieu` varchar(255) NOT NULL,
  `gpu` varchar(255) NOT NULL,
  `cpu` varchar(255) NOT NULL,
  `ram` varchar(255) NOT NULL,
  `dung_luong` varchar(255) NOT NULL,
  `kich_thuoc_man_hinh` varchar(255) NOT NULL,
  `do_phan_giai` varchar(255) NOT NULL,
  `description` TEXT NULL,
  FOREIGN KEY (product_id) REFERENCES product(product_id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `laptop_detail`  (
  `laptop_id` INT PRIMARY KEY AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `thuong_hieu` varchar(255) NOT NULL,
  `cpu` varchar(255) NOT NULL,
  `gpu` varchar(255) NOT NULL,
  `ram` varchar(255) NOT NULL,
  `dung_luong` varchar(255) NOT NULL,
  `kich_thuoc_man_hinh` varchar(255) NOT NULL,
  `do_phan_giai` varchar(255) NOT NULL,
  `description` TEXT NULL,
  FOREIGN KEY (product_id) REFERENCES product(product_id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `gpu_detail`  (
  `gpu_id` INT PRIMARY KEY AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `thuong_hieu` varchar(255) NOT NULL,
  `gpu` varchar(255) NOT NULL,
  `cuda` varchar(255) NOT NULL,
  `bo_nho` varchar(255) NOT NULL,
  `nguon` varchar(255) NOT NULL,
  `description` TEXT NULL,
  FOREIGN KEY (product_id) REFERENCES product(product_id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `headset_detail`  (
  `headset_id`INT PRIMARY KEY AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `thuong_hieu` varchar(255) NOT NULL,
  `micro` enum('có','không') NOT NULL,
  `pin` varchar(255) NOT NULL,
  `ket_noi` varchar(255) NOT NULL,
  `description` TEXT NULL,
  FOREIGN KEY (product_id) REFERENCES product(product_id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `mouse_detail`  (
  `mouse_id` INT PRIMARY KEY AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `thuong_hieu` varchar(255) NOT NULL,
  `ket_noi` varchar(255) NOT NULL,
  `description` TEXT NULL,
  FOREIGN KEY (product_id) REFERENCES product(product_id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `keyboard_detail`  (
  `keyboard_id` INT PRIMARY KEY AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `thuong_hieu` varchar(255) NOT NULL,
  `ket_noi` varchar(255) NOT NULL,
  `description` TEXT NULL,
  FOREIGN KEY (product_id) REFERENCES product(product_id)
) ENGINE=InnoDB;






INSERT INTO `category` (`category_id`, `category_name`, `status`, `description`) VALUES
('GPU',             'GPU',         'hiện' , NULL),
('Headset',       'Tai Nghe',      'ẩn' , NULL),
('Keyboard',      'Bàn Phím',      'ẩn' , NULL),
('Laptop',        'Laptop',        'hiện' , NULL),
('LaptopGaming',  'Laptop Gaming', 'hiện' , NULL),
('Screen',        'Màn Hình',      'ẩn' , NULL),
('Mouse',         'Chuột',         'ẩn' , NULL);

INSERT INTO `product` (`product_id`, `product_name`, `category_id`, `stock`, `price`, `created_at`, `updated_at`, `status`, `image_url`) VALUES
(1, 'Màn hình MSI PRO MP242L',                                      'Screen',         35,     1890000,   '2024-01-21 11:22:13', '2024-01-21 11:22:13', 'hiện', '50725_m__n_h__nh_msi_pro_mp242l__4_.jpg'),
(2, 'Laptop Asus VivoBook Go 14 E1404FA-NK177W',                    'Laptop',         19,     11490000,  '2024-02-04 14:32:39', '2024-02-04 14:32:39', 'hiện', 'e1404fa-1.png'),
(3, 'Card màn hình MSI GeForce RTX 5090 32G GAMING TRIO OC',        'GPU',            68,     97990000,  '2024-02-05 09:45:29', '2024-02-05 09:45:29', 'hiện', 'bzilxs4m.png'),
(4, 'Laptop Gaming MSI Katana 15 B13UDXK 2270VN',                   'LaptopGaming',   25,     20900000,  '2024-05-23 04:43:37', '2024-05-23 04:43:37', 'hiện', '8qziagrd.png'),
(5, 'Laptop Gaming Lenovo LOQ 15ARP9 83JC003YVN',                   'LaptopGaming',   76,     27790000,  '2024-07-15 09:20:40', '2024-07-15 09:20:40', 'hiện', '48807_laptop_lenovo_loq_15arp9_83jc003yvn__3_.jpg'),
(6, 'Card màn hình Asus Dual GeForce RTX™ 3060 V2 12GB GDDR6',      'GPU',            24,     7790000,   '2024-07-23 12:36:19', '2024-07-23 12:36:19', 'hiện', 'imagertx3060V2_12GB.png'),
(7, 'Laptop Gaming GIGABYTE G5 MF5-52VN383SH',                      'LaptopGaming',   34,     20790000,  '2024-07-23 13:37:34', '2024-07-23 13:37:34', 'hiện', '47728_laptop_gigabyte_g5_mf5_52vn383sh__1_.jpg'),
(8, 'Màn Hình Gaming GIGABYTE GS27F',                               'Screen',         86,     3298000,   '2024-07-26 16:32:14', '2024-07-26 16:32:14', 'hiện', 'man_hinh_gaming_gigabyte_gs27f__5_.jpg'),
(9, 'Laptop Acer Aspire Lite AL14-51M-36MH_NX.KTVSV.001',           'Laptop',         64,     9190000,   '2024-07-26 20:21:16', '2024-07-26 20:21:16', 'hiện', '49837_laptop_acer_aspire_lite_al14_51m_36mh_nx_ktvsv_001__2_.jpg'),
(10, 'Laptop Gaming Asus TUF F15 FX507ZC4-HN095W',                  'LaptopGaming',   49,     19990000,  '2024-09-23 12:42:05', '2024-09-23 12:42:05', 'hiện', '46655_laptop_asus_tuf_gaming_f15_fx507zc4_hn095w__3_.jpg'),
(11, 'Laptop Gaming Lenovo Legion Pro 5 16IRX9 83DF0046VN',         'LaptopGaming',   27,     51990000,  '2024-09-23 12:43:11', '2024-09-23 12:43:11', 'hiện', '47462_laptop_lenovo_legion_pro_5_16irx9_83df0046vn__1_.jpg'),
(12, 'Laptop Gaming Acer Aspire 7 A715-76G-5806 - NH.QMFSV.002',    'LaptopGaming',   50,     18990000,  '2024-09-23 12:45:36', '2024-09-23 12:45:36', 'hiện', '45836_ap7.jpg'),
(13, 'Laptop Gaming Acer Nitro 5 Tiger AN515-58-5935 NH.QLZSV.001', 'LaptopGaming',   80,     22290000,  '2024-09-23 12:46:57', '2024-09-23 12:46:57', 'hiện', '45837_bnfg.jpg'),
(14, 'Laptop Acer Aspire 3 A315-44P-R5QG NX.KSJSV.001',             'Laptop',         99,     12900000,  '2024-09-23 12:46:57', '2024-09-23 12:46:57', 'hiện', '50618_laptop_acer_aspire_3_a315_44p_r5qg_nx_ksjsv_001__4_.jpg'),
(15, 'Laptop Asus Vivobook 14 OLED A1405VA-KM095W',                 'Laptop',         29,     16990000,  '2024-09-23 13:24:38', '2024-09-23 13:24:38', 'hiện', '44758_laptop_asus_vivobook_14_oled_a1405va_km095w__7_.jpg'),
(16, 'Laptop Gaming HP VICTUS 15-fa1155TX 952R1PA_16G',             'LaptopGaming',  100,     17990000,  '2024-09-23 13:27:44', '2024-09-23 13:27:44', 'ẩn',   '49855_laptop_hp_victus_15_fa1155tx_952r1pa_16g__2_.jpg'),
(17, 'Laptop Asus Vivobook S 16 OLED S5606MA-MX051W',               'Laptop',          30,     25490000,  '2024-09-23 13:27:44', '2024-09-23 13:27:44', 'hiện', 'g8gdssys.png'),
(18, 'Laptop HP ProBook 440 G11 A74B4PT',                           'Laptop',          30,     21490000,  '2024-09-23 13:27:44', '2024-09-23 13:27:44', 'hiện', '49741_laptop_hp_probook_440_g11_a74b4pt__1_.jpg'),
(19, 'Card màn hình Asus ROG Strix GeForce RTX 4090 OC Edition 24GB GDDR6X','GPU',     30,     64990000,  '2024-09-23 13:27:44', '2024-09-23 13:27:44', 'hiện', 'tn9pvbdr.png'),
(20, 'VGA Gigabyte RTX 4060 Windforce OC 8GB',                      'GPU',             29,     8299000,   '2024-09-23 13:27:44', '2024-09-23 13:27:44', 'hiện', '45659_vga_gigabyte_rtx_4060_windforce_oc_8gb_anphat88.jpg'),
(21, 'VGA Gigabyte GeForce RTX 3050 WINDFORCE OC V2 8GB',           'GPU',             30,     5599000,   '2024-09-23 13:27:44', '2024-09-23 13:27:44', 'hiện', '46200_vga_gigabyte_geforce_rtx_3050_windforce___2_.jpg'),
(22, 'Laptop Dell Latitude 3450 71058806',                          'Laptop',          30,     24990000,  '2024-09-23 13:27:44', '2024-09-23 13:27:44', 'hiện', '51342_laptop_dell_latitude_3450_71058806__1_.jpg'),
(23, 'Laptop gaming Acer Predator Helios Neo 16 PHN16 72 78L4',     'LaptopGaming',    30,     38490000,  '2024-09-23 13:27:44', '2024-09-23 13:27:44', 'hiện', '1746952951_acer_predator_helios_neo_16_2024__2__3ffd04967bc44b82b78f3e0cee408665_1024x1024.jpg'),
(24, 'Laptop gaming Lenovo Legion 5 16IRX9 83DG004YVN',             'LaptopGaming',    30,     37990000,  '2024-09-23 13:27:44', '2024-09-23 13:27:44', 'hiện', '1746953416_legion_5_16irx9_ct1_01_6639fb2c9ce446439a36578865a5c7d0_1024x1024.jpg'),
(25, 'Laptop gaming Acer Predator Helios Neo 16 PHN16 71 74QR',     'LaptopGaming',    30,     41990000,  '2024-09-23 13:27:44', '2024-09-23 13:27:44', 'hiện', '1746953641_468f7a1472eb8f563424b86621d_c04206638f4643d58ab3079d6dc42ec0_1024x1024_18da0285e1e848248921bbcfc8c80c53_grande.jpg'),
(26, 'Laptop gaming Asus TUF Gaming FA401WV RG062WS',               'LaptopGaming',    30,     39990000,  '2024-09-23 13:27:44', '2024-09-23 13:27:44', 'hiện', '1746954077_ava_dea980b662854ab8a4dd359d3bd8d2b4_grande.jpg'),
(27, 'Laptop gaming HP Pavilion Gaming 15 EC2158AX',                'LaptopGaming',    30,     38600000,  '2024-09-23 13:27:44', '2024-09-23 13:27:44', 'hiện', '5qpotnzp.png'),
(28, 'Laptop gaming HP OMEN 16-wf1137TX A2NR9PA',                   'LaptopGaming',    30,     52490000,  '2024-09-23 13:27:44', '2024-09-23 13:27:44', 'hiện', '1746954618_1_a4de5185d81a4d8e851974281003b6d4_grande.jpg'),
(29, 'Laptop gaming MSI Sword 16 HX B14VEKG 856VN',                 'LaptopGaming',    30,     31490000,  '2024-09-23 13:27:44', '2024-09-23 13:27:44', 'hiện', '1746954844_ava_ecb79fdbde454bfd87bf7ccd8675e972_grande.jpg'),
(30, 'Laptop gaming MSI Vector 16 HX AI A2XWHG 010VN',              'LaptopGaming',    30,     54990000,  '2024-09-23 13:27:44', '2024-09-23 13:27:44', 'hiện', '1746955011_1024__1__035bd6ee5a8246078c525b4bc8d2e55b_grande.jpg'),
(31, 'Laptop gaming Lenovo Legion 7 16IRX9 83FD004MVN',             'LaptopGaming',    30,     59990000,  '2024-09-23 13:27:44', '2024-09-23 13:27:44', 'hiện', '1746955134_ava_1388feab03cd40a2ad5b495d909a0a60_grande.jpg'),
(32, 'Laptop gaming MSI Thin 15 B13UC 2044VN',                      'LaptopGaming',    30,     19290000,  '2024-09-23 13:27:44', '2024-09-23 13:27:44', 'hiện', '1746955252_thin-new_d31ff3b88e7f40e7ac88acc624e03d4f_grande.jpg'),
(33, 'Card màn hình GIGABYTE GeForce RTX 4070 SUPER WINDFORCE OC 12G','GPU',           30,     19490000,  '2024-09-23 13:27:44', '2024-09-23 13:27:44', 'hiện', '1747011522_Screenshot 2025-05-12 075833.png'),
(34, 'Card màn hình GIGABYTE Radeon RX 9070 GAMING OC 16G',         'GPU',             30,     19990000,  '2024-09-23 13:27:44', '2024-09-23 13:27:44', 'hiện', '1747011767_Screenshot 2025-05-12 080240.png'),
(35, 'Card màn hình Gigabyte GeForce RTX 3060 WINDFORCE OC 12G',    'GPU',             30,     7690000,   '2024-09-23 13:27:44', '2024-09-23 13:27:44', 'hiện', '1747011962_Screenshot 2025-05-12 080557.png'),
(36, 'Card màn hình Asus TUF Gaming GeForce RTX 5090 32GB',         'GPU',             30,     105000000, '2024-09-23 13:27:44', '2024-09-23 13:27:44', 'hiện', '1747012087_Screenshot 2025-05-12 080801.png'),
(37, 'Card màn hình Asus Dual Radeon RX 6500 XT OC 4GB',            'GPU',             30,     3490000,   '2024-09-23 13:27:44', '2024-09-23 13:27:44', 'hiện', '1747012258_Screenshot 2025-05-12 081013.png'),
(38, 'Laptop HP 15-fd0235TU - 9Q970PA',                             'Laptop',          30,     14990000,  '2024-09-23 13:27:44', '2024-09-23 13:27:44', 'hiện', '1231image.png'),
(39, 'Laptop HP 14-EP0112TU 8C5L1PA', 'Laptop', 0, 15290000, '2024-10-22 20:21:14', '2024-10-22 20:21:14', 'hiện', '1761189674_text_ng_n_26__1_2[1].png'),
(40, 'Laptop Lenovo IdeaPad Slim 3 14IRH10', 'Laptop', 0, 14990000, '2024-10-22 21:41:36', '2024-10-22 21:41:36', 'hiện', '1761194496_iw8632w8.png'),
(41, 'Laptop Dell Inspiron 15 3530 J9XFD', 'Laptop', 0, 14990000, '2024-10-22 21:47:16', '2024-10-22 21:47:16', 'hiện', '1761194836_9iffk65a.png'),
(42, 'Laptop Dell Vostro 3530 2H1TPI7', 'Laptop', 0, 17490000, '2024-10-22 22:40:22', '2024-10-22 22:40:22', 'hiện', '1761198022_hhcxzxy8.png'),
(43, 'Laptop Dell 15 DC15255 DC5R5802W1', 'Laptop', 0, 15990000, '2024-10-22 22:44:48', '2024-10-22 22:44:48', 'hiện', '1761198288_5ws50ead.png');



INSERT INTO `laptop_detail` (`laptop_id`, `product_id`, `thuong_hieu`, `cpu`, `gpu`, `ram`, `dung_luong`, `kich_thuoc_man_hinh`, `do_phan_giai`, `description`) VALUES
(1, 2, 'Asus', 'AMD Ryzen 5 7520U', 'AMD Radeon Graphics', '16GB', '512GB', '14 inch', '1920x1080', NULL),
(2, 9, 'Acer', 'Intel Core i3-1215U', 'Intel UHD Graphics', '8GB', '256GB', '14 inch', '1920x1080', NULL),
(3, 14, 'Acer', 'AMD Ryzen 7 5700U', 'AMD Radeon Graphics', '16GB', '512GB', '15.6 inch', '1920x1080', NULL),
(4, 15, 'Asus', 'Intel Core i5-13500H', 'Intel Iris X Graphics', '16GB', '512GB', '14 inch', '2880x1800', NULL),
(5, 17, 'Asus', 'Intel Core Ultra 7 155H', 'Intel Arc Graphics', '16GB', '512GB', '16 inch', '3200x2000', NULL),
(6, 18, 'HP', 'Intel Core Ultra 5 125U', 'Intel Graphics', '8GB', '512GB', '14 inch', '1920x1200', NULL),
(7, 22, 'Dell', 'Intel Core i7-1355U', 'Intel Iris Xe Graphics', '16GB', '512GB', '14 inch', '1920x1080', NULL),
(8, 38, 'HP', 'Intel Core i5-1334U', 'Intel Graphics', '16GB', '512GB', '15.6 inch', '1920x1080', NULL),
(9, 39, 'HP', 'Intel Core i5 - 1335U', 'Intel Iris Xe Graphics', '16GB', '512GB', '14 inch', '1920x1080', NULL),
(10, 40, 'Lenovo', 'Intel Core i5-13420H', 'Intel UHD Graphics', '16GB', '512GB', '14 inch', '1920x1200', NULL),
(11, 41, 'Dell', 'Intel Core i5-1334U', 'Intel Iris Xe Graphics', '16GB', '512GB', '15.6 inch', '1920x1080', NULL),
(12, 42, 'Dell', 'Intel Core i7-1355U', 'Intel Iris Xe Graphics', '16GB', '512GB', '15.6 inch', '1920x1080', NULL),
(13, 43, 'Dell', 'Ryzen 5 7530U', 'AMD Radeon Graphics', '16GB', '512GB', '15.6 inch', '1920x1080', NULL);

INSERT INTO `laptop_gaming_detail` (`laptop_gaming_id`, `product_id`, `thuong_hieu`, `gpu`, `cpu`, `ram`, `dung_luong`, `kich_thuoc_man_hinh`, `do_phan_giai`, `description`) VALUES
(1, 4, 'MSI', 'NVIDIA GeForce RTX 3050 6GB', 'Intel Core i5-13500H', '8GB', '1TB', '15.6 inch', '1920x1080', NULL),
(2, 5, 'Lenovo', 'NVIDIA GeForce RTX 4060 8GB', 'AMD Ryzen 7 7435HS', '24GB', '512GB', '15.6 inch', '1920x1080', NULL),
(3, 7, 'Gigabyte', 'NVIDIA GeForce RTX 4050 6GB', 'Intel Core i5-13500H', '8GB', '512GB', '15.6 inch', '1920x1080', NULL),
(4, 10, 'Asus', 'NVIDIA GeForce RTX 3050 4GB', 'Intel Core i5-12500H', '16GB', '512GB', '15.6 inch', '1920x1080', NULL),
(5, 11, 'Lenovo', 'NVIDIA GeForce RTX 4070 8GB', 'Intel Core i9-14900HX', '32GB', '1TB', '16 inch', '2560x1600', NULL),
(6, 12, 'Acer', 'NVIDIA GeForce RTX 3050 4GB', 'Intel Core i5-12450H', '16GB', '512GB', '15.6 inch', '1920x1080', NULL),
(7, 13, 'Acer', 'NVIDIA GeForce RTX 4050 6GB', 'Intel Core i5-12450H', '8GB', '512GB', '15.6 inch', '1920x1080', NULL),
(8, 16, 'HP', 'NVIDIA GeForce RTX 2050 4GB', 'Intel Core i5-12450H', '16GB', '512GB', '15.6 inch', '1920x1080', NULL),
(9, 23, 'Acer', 'NVIDIA GeForce RTX 4050 6GB', 'Intel Core i7-14700HX', '32GB', '512GB', '16 inch', '2560x1600', NULL),
(10, 24, 'Lenovo', 'NVIDIA GeForce RTX 4060 8GB', 'Intel Core i7-14650HX', '16GB', '512GB', '16 inch', '2560x1600', NULL),
(11, 25, 'Acer', 'NVIDIA GeForce RTX 4070 8GB', 'Intel Core i7-13700HX', '16GB', '512GB', '16 inch', '2560x1600', NULL),
(12, 26, 'Asus', 'NVIDIA GeForce RTX 4060 8GB', 'AMD Ryzen AI 9 HX 370', '16GB', '1TB', '14 inch', '2560x1600', NULL),
(13, 28, 'HP', 'NVIDIA GeForce RTX 4070 8GB', 'Intel Core i9-14900HX', '32GB', '1TB', '16 inch', '2560x1440', NULL),
(14, 29, 'MSI', 'NVIDIA GeForce RTX 4050 6GB', 'Intel Core i7-14700HX', '16GB', '1TB', '16 inch', '1920x1200', NULL),
(15, 30, 'MSI', 'NVIDIA GeForce RTX 5070 Ti 12GB', 'Intel Core Ultra 7 255HX', '16GB', '512GB', '16 inch', '2560x1600', NULL),
(16, 31, 'Lenovo', 'NVIDIA GeForce RTX 4070 8GB', 'Intel Core i9-14900HX', '32GB', '1TB', '16 inch', '3200x3200', NULL),
(17, 32, 'MSI', 'NVIDIA GeForce RTX 3050 4GB', 'Intel Core i7-13620H', '16GB', '512GB', '15.6 inch', '1920x1080', NULL);





-- user id 1 password: password
-- INSERT INTO `user` (`user_id`, `username`, `password`, `email`, `sex`, `phone_number`, `dob`, `status`, `created_at`, `updated_at`, `avatar_url`) VALUES
-- (1, 'nhat nguyen', '$2y$12$OB3IEwceg7PKw5P/dNyXtOWYfT.f9rUEYn8YXusghmUQ3H9KMKRIG', 'password@gmail.com', 'nam', '0963944370', '2005-04-27', 'mở', '2025-09-27 12:34:21', '2025-09-27 19:34:43', NULL),
-- (2, 'admin', '$2y$12$CgkYyUuY5Z7aNDqkcvPFc.XpxUayCPwx1p3p9xrA/mZ3z5GVqoIn2', 'admin@gmail.com', 'nam', '0123456789', NULL, 'mở', '2025-09-27 12:34:21', '2025-09-27 19:34:43', NULL);

DELIMITER //

CREATE TRIGGER trg_after_user_insert
AFTER INSERT ON `user`
FOR EACH ROW
BEGIN
  INSERT INTO cart (user_id)
  VALUES (NEW.user_id);
END;
//

DELIMITER ;



INSERT INTO `cart` (`cart_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2024-09-10 12:34:21', '2024-09-10 12:34:21'),
(2, 2, '2024-09-5 12:34:21', '2024-09-5 12:34:21'),
(3, 3, '2024-09-10 12:34:21', '2024-09-10 12:34:21'),
(4, 4, '2024-09-10 12:34:21', '2024-09-10 12:34:21'),
(5, 5, '2024-09-10 12:34:21', '2024-09-10 12:34:21'),
(6, 6, '2024-09-10 12:34:21', '2024-09-10 12:34:21'),
(7, 7, '2024-09-10 12:34:21', '2024-09-10 12:34:21'),
(8, 8, '2024-09-10 12:34:21', '2024-09-10 12:34:21'),
(9, 9, '2024-09-10 12:34:21', '2024-09-10 12:34:21'),
(10, 10, '2024-09-10 12:34:21', '2024-09-10 12:34:21'),
(11, 11, '2024-09-10 12:34:21', '2024-09-10 12:34:21'),
(12, 12, '2024-09-10 12:34:21', '2024-09-10 12:34:21'),
(13, 13, '2024-09-10 12:34:21', '2024-09-10 12:34:21'),
(14, 14, '2024-09-10 12:34:21', '2024-09-10 12:34:21'),
(15, 15, '2024-09-10 12:34:21', '2024-09-10 12:34:21');

INSERT INTO `cart_item` (`item_id`, `cart_id`, `product_id`, `quantity`) VALUES
(1, 1, 1, 2),
(2, 1, 5, 1);





INSERT INTO `address` (`address_id`, `user_id`, `address`, `created_at`, `updated_at`) VALUES
(1, 1, '229 cao thăng, p13, q4, hcm', '2025-09-28 03:44:08', '2025-09-28 03:44:08'),
(2, 1, 'phường 13, quận 5, tphcm', '2025-09-28 04:12:28', '2025-09-28 04:12:40');





-- ---------------------------------------------------
INSERT INTO `order` (`order_id`, `user_id`, `address`, `order_date`, `delivery_date`, `total_amount`, `status`, `payment_method`, `created_by`) VALUES
(1, 1, 'phường 13, quận 5, tphcm', '2025-10-04 07:22:50', '2025-10-06 11:08:10', 12900000, 'đã nhận hàng', 'chuyển khoản', 'customer'),
(2, 1, 'phường 13, quận 5, tphcm', '2025-10-04 09:32:07', NULL, 1890000, 'chờ xác nhận', 'tiền mặt', 'customer'),
(3, 1, 'phường 13, quận 5, tphcm', '2025-10-04 14:33:26', NULL, 23770000, 'đã hủy', 'tiền mặt', 'customer'),
(4, 1, 'phường 13, quận 5, tphcm', '2025-10-04 14:57:34', NULL, 9190000, 'đã xác nhận', 'chuyển khoản', 'customer'),
(5, 1, 'phường 13, quận 5, tphcm', '2025-10-04 15:04:24', NULL, 11490000, 'đang giao', 'chuyển khoản', 'customer'),
(6, 3, '111 CMT8, quận 10, TPHCM', '2025-10-04 15:04:24', NULL, 274990000, 'chờ xác nhận', 'chuyển khoản', 'customer'),
(7, 3, 'số 342B, quận 7, TPHCM', '2025-10-04 15:04:24',   NULL, 297228000, 'chờ xác nhận', 'chuyển khoản', 'customer'),
(8, 3, 'số 342B, quận 7, TPHCM', '2025-09-11 11:34:44',   '2025-09-11 11:45:44', 16990000, 'đã nhận hàng', 'chuyển khoản', 'customer'),
(9, 3, 'số 342B, quận 7, TPHCM', '2025-08-03 11:34:44',   '2025-08-05 11:45:44', 91669000, 'đã nhận hàng', 'chuyển khoản', 'customer');

DELIMITER //

CREATE TRIGGER after_order_insert
AFTER INSERT ON `order`
FOR EACH ROW
BEGIN
  -- Chỉ chạy nếu created_by KHÁC 'admin'
  IF NEW.created_by != 'admin' AND NEW.created_by = 'customer' THEN
  
    -- Chèn dữ liệu từ cart_item vào order_detail
    INSERT INTO order_detail (order_id, product_id, quantity, price)
    SELECT 
      NEW.order_id, 
      ci.product_id, 
      ci.quantity, 
      p.price
    FROM cart_item ci
    INNER JOIN cart c ON ci.cart_id = c.cart_id
    INNER JOIN product p ON ci.product_id = p.product_id
    WHERE c.user_id = NEW.user_id;
    
    -- Xóa các mục trong cart_item sau khi đã chuyển sang order_detail
    DELETE ci FROM cart_item ci
    INNER JOIN cart c ON ci.cart_id = c.cart_id
    WHERE c.user_id = NEW.user_id;
  
  END IF;
END;
//

DELIMITER ;


-- ---------
DELIMITER //

CREATE TRIGGER after_order_update
AFTER UPDATE ON `order`
FOR EACH ROW
BEGIN
  -- Chỉ xử lý khi status thay đổi từ `chờ xác nhận` thành 'đã xác nhận'
  IF NEW.status = 'đã xác nhận' AND OLD.status != 'đã xác nhận' AND OLD.status = 'chờ xác nhận' THEN
    -- Chèn dữ liệu từ order_detail vào inventory với type là 'xuất hàng'
    INSERT INTO inventory (product_id, quantity, type, reference)
    SELECT 
      od.product_id,
      od.quantity * -1,
      'xuất hàng',
      CONCAT('order ', NEW.order_id)
    FROM order_detail od
    WHERE od.order_id = NEW.order_id;
  END IF;
END;
//

DELIMITER ;

-- ---------
DELIMITER $$

CREATE TRIGGER after_order_cancelled
AFTER UPDATE ON `order`
FOR EACH ROW
BEGIN
  -- Chỉ xử lý khi status thay đổi thành 'đã hủy' và trạng thái trước đó là 'đã xác nhận' hoặc 'đang giao'
  IF NEW.status = 'đã hủy' AND OLD.status != 'đã hủy' AND OLD.status IN ('đã xác nhận', 'đang giao') THEN
    -- Chèn dữ liệu từ order_detail vào inventory với type là 'điều chỉnh'
    -- Số lượng dương để bù lại hàng đã xuất trước đó
    INSERT INTO inventory (product_id, quantity, type, reference)
    SELECT 
      od.product_id,
      od.quantity,  -- Số lượng dương để thêm lại vào kho
      'điều chỉnh',
      CONCAT('order ', NEW.order_id, ' - hủy đơn')
    FROM order_detail od
    WHERE od.order_id = NEW.order_id;
  END IF;
END$$

DELIMITER ;


-- ---------
DELIMITER //

CREATE TRIGGER before_order_received
BEFORE UPDATE ON `order`
FOR EACH ROW
BEGIN
    -- Chỉ xử lý khi status thay đổi thành 'đã nhận hàng'
    IF NEW.status = 'đã nhận hàng' AND OLD.status != 'đã nhận hàng' THEN
        -- Cập nhật delivery_date thành thời gian hiện tại
        SET NEW.delivery_date = CURRENT_TIMESTAMP;
    END IF;
END;
//

DELIMITER ;


-- ---------------------------------------------------
INSERT INTO `order_detail` (`order_detail_id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 14, 1, 12900000),
(2, 2, 1, 1, 1890000),
(3, 3, 1, 2, 1890000),
(4, 3, 10, 1, 19990000),
(5, 4, 9, 1, 9190000),
(6, 5, 2, 1, 11490000),
(7, 6, 19, 1, 64990000),
(8, 6, 36, 2, 105000000),
(9, 7, 28, 1, 52490000),
(10, 7, 21, 2, 5599000),
(11, 7, 34, 4, 19990000),
(12, 7, 7, 1, 20790000),
(13, 7, 27, 3, 38600000),
(14, 7, 15, 1, 16990000),
(15, 8, 15, 1, 16990000),
(16, 9, 20, 1, 8299000),
(17, 9, 5, 3, 27790000);







-- ---------------------------------------------------
INSERT INTO `supplier` (`supplier_id`, `supplier_name`, `supplier_phone`, `supplier_address`) VALUES
(1, 'Công Ty TNHH Nhập Khẩu Điện Tử', '0933224455', 'Số 654, Quận 1, TPHCM'),
(2, 'Công Ty TNHH Hòa Đông', '0267876543', 'Số 23, Đường Điện Biên Phủ, Tỉnh Bình Dương'),
(3, 'Công Ty TNHH Nhập Khẩu Mỹ', '0786767677', 'Số 356, Xa Lộ Hà Nội, TP Thủ Đức, TPHCM');




INSERT INTO `receipt` (`receipt_id`, `supplier_id`, `order_date`, `status`) VALUES
(1, 1, '2025-01-28 13:18:31', 'đã nhận'),
(2, 3, '2025-01-28 14:22:56', 'đang chờ'),
(3, 1, '2025-01-28 17:54:26', 'đã nhận'),
(4, 2, '2025-01-28 18:52:26', 'đã nhận');

DELIMITER $$
CREATE TRIGGER `trg_after_receipt_update` AFTER UPDATE ON `receipt` FOR EACH ROW BEGIN
  -- Chỉ xử lý khi status đổi từ 'đang chờ' sang 'đã nhận'
  IF OLD.status = 'đang chờ' AND NEW.status = 'đã nhận' THEN
    INSERT INTO inventory (product_id, quantity, `type`, reference, created_at)
    SELECT 
      rd.product_id,
      rd.quantity,
      'nhập hàng',
      CONCAT('PN', NEW.receipt_id),  -- reference có thể là mã phiếu nhập
      NOW()
    FROM receipt_detail rd
    WHERE rd.receipt_id = NEW.receipt_id;
  END IF;
END
$$
DELIMITER ;






INSERT INTO `receipt_detail` (`receipt_detail_id`, `receipt_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 1, 35, 1530000),
(2, 1, 2, 20, 9490000),
(3, 1, 3, 68, 95900000),
(4, 1, 4, 25, 18840000),
(5, 1, 5, 79, 25500000),
(6, 1, 6, 24, 18380000),
(7, 1, 7, 34, 17580000),
(8, 1, 8, 86, 2990000),
(9, 1, 9, 65, 8900000),
(10, 1, 10, 39, 16820000),
(11, 1, 11, 17, 45330000),
(12, 1, 12, 50, 17100000),
(13, 1, 13, 80, 20000000),
(14, 1, 14, 100, 11800000),
(15, 1, 16, 100, 16800000),
(16, 2, 5, 40, 25350000),
(17, 2, 6, 60, 18400000),
(18, 2, 7, 45, 17550000),
(19, 3, 10, 10, 15750000),
(20, 3, 11, 10, 45000000),
(21, 4, 15, 30, 15990000),
(22, 4, 17, 30, 24490000),
(23, 4, 18, 30, 20490000),
(24, 4, 19, 30, 61990000),
(25, 4, 20, 30, 7299000),
(26, 4, 21, 30, 4599000),
(27, 4, 22, 30, 21990000),
(28, 4, 23, 30, 33490000),
(29, 4, 24, 30, 35990000),
(30, 4, 25, 30, 39990000),
(31, 4, 26, 30, 37990000),
(32, 4, 27, 30, 36600000),
(33, 4, 28, 30, 50490000),
(34, 4, 29, 30, 30490000),
(35, 4, 30, 30, 52990000),
(36, 4, 31, 30, 57990000),
(37, 4, 32, 30, 17290000),
(38, 4, 33, 30, 18490000),
(39, 4, 34, 30, 18990000),
(40, 4, 35, 30, 6690000),
(41, 4, 36, 30, 100000000),
(42, 4, 37, 30, 3290000),
(43, 4, 38, 30, 13990000);





INSERT INTO `inventory` (`inventory_id`, `product_id`, `quantity`, `type`, `reference`, `created_at`) VALUES
(1, 1, 35, 'nhập hàng', 'PN1', '2025-01-28 13:21:23'),
(2, 2, 20, 'nhập hàng', 'PN1', '2025-01-28 13:21:23'),
(3, 3, 68, 'nhập hàng', 'PN1', '2025-01-28 13:21:23'),
(4, 4, 25, 'nhập hàng', 'PN1', '2025-01-28 13:21:23'),
(5, 5, 79, 'nhập hàng', 'PN1', '2025-01-28 13:21:23'),
(6, 6, 24, 'nhập hàng', 'PN1', '2025-01-28 13:21:23'),
(7, 7, 34, 'nhập hàng', 'PN1', '2025-01-28 13:21:23'),
(8, 8, 86, 'nhập hàng', 'PN1', '2025-01-28 13:21:23'),
(9, 9, 65, 'nhập hàng', 'PN1', '2025-01-28 13:21:23'),
(10, 10, 39, 'nhập hàng', 'PN1', '2025-01-28 13:21:23'),
(11, 11, 17, 'nhập hàng', 'PN1', '2025-01-28 13:21:23'),
(12, 12, 50, 'nhập hàng', 'PN1', '2025-01-28 13:21:23'),
(13, 13, 80, 'nhập hàng', 'PN1', '2025-01-28 13:21:23'),
(14, 14, 100, 'nhập hàng', 'PN1', '2025-01-28 13:21:23'),
(15, 16, 100, 'nhập hàng', 'PN1', '2025-01-28 13:21:23'),
(16, 10, 10, 'nhập hàng', 'PN3', '2025-01-28 20:03:18'),
(17, 11, 10, 'nhập hàng', 'PN3', '2025-01-28 20:03:18'),
(18, 14, -1, 'xuất hàng', 'order 1', '2025-10-04 12:07:55'),
(19, 9, -1, 'xuất hàng', 'order 4', '2025-10-04 16:33:46'),
(20, 2, -1, 'xuất hàng', 'order 5', '2025-10-04 16:35:23'),
(21, 15, 30, 'nhập hàng', 'PN4', '2025-01-29 18:52:26'),
(22, 17, 30, 'nhập hàng', 'PN4', '2025-01-29 18:52:26'),
(23, 18, 30, 'nhập hàng', 'PN4', '2025-01-29 18:52:26'),
(24, 19, 30, 'nhập hàng', 'PN4', '2025-01-29 18:52:26'),
(25, 20, 30, 'nhập hàng', 'PN4', '2025-01-29 18:52:26'),
(26, 21, 30, 'nhập hàng', 'PN4', '2025-01-29 18:52:26'),
(27, 22, 30, 'nhập hàng', 'PN4', '2025-01-29 18:52:26'),
(28, 23, 30, 'nhập hàng', 'PN4', '2025-01-29 18:52:26'),
(29, 24, 30, 'nhập hàng', 'PN4', '2025-01-29 18:52:26'),
(30, 25, 30, 'nhập hàng', 'PN4', '2025-01-29 18:52:26'),
(31, 26, 30, 'nhập hàng', 'PN4', '2025-01-29 18:52:26'),
(32, 27, 30, 'nhập hàng', 'PN4', '2025-01-29 18:52:26'),
(33, 28, 30, 'nhập hàng', 'PN4', '2025-01-29 18:52:26'),
(34, 29, 30, 'nhập hàng', 'PN4', '2025-01-29 18:52:26'),
(35, 30, 30, 'nhập hàng', 'PN4', '2025-01-29 18:52:26'),
(36, 31, 30, 'nhập hàng', 'PN4', '2025-01-29 18:52:26'),
(37, 32, 30, 'nhập hàng', 'PN4', '2025-01-29 18:52:26'),
(38, 33, 30, 'nhập hàng', 'PN4', '2025-01-29 18:52:26'),
(39, 34, 30, 'nhập hàng', 'PN4', '2025-01-29 18:52:26'),
(40, 35, 30, 'nhập hàng', 'PN4', '2025-01-29 18:52:26'),
(41, 36, 30, 'nhập hàng', 'PN4', '2025-01-29 18:52:26'),
(42, 37, 30, 'nhập hàng', 'PN4', '2025-01-29 18:52:26'),
(43, 38, 30, 'nhập hàng', 'PN4', '2025-01-29 18:52:26'),
(44, 15, -1, 'xuất hàng', 'order 8', '2025-09-11 11:36:14'),
(45, 20, -1, 'xuất hàng', 'order 9', '2025-08-04 11:36:14'),
(46, 5, -3, 'xuất hàng', 'order 9', '2025-08-04 11:36:14');

DELIMITER $$
CREATE TRIGGER `trg_after_inventory_insert` AFTER INSERT ON `inventory` FOR EACH ROW BEGIN
  IF NEW.type = 'nhập hàng' THEN
    UPDATE product
    SET stock = stock + NEW.quantity
    WHERE product_id = NEW.product_id;
  ELSEIF NEW.type = 'xuất hàng' THEN
    UPDATE product
    SET stock = stock + NEW.quantity
    WHERE product_id = NEW.product_id;
  ELSEIF NEW.type = 'điều chỉnh' THEN
    UPDATE product
    SET stock = stock + NEW.quantity
    WHERE product_id = NEW.product_id;
  END IF;
END
$$
DELIMITER ;







CREATE TABLE IF NOT EXISTS `sessions`  (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- CREATE TABLE IF NOT EXISTS `migrations`  (
--   `id` int(10) UNSIGNED NOT NULL,
--   `migration` varchar(255) NOT NULL,
--   `batch` int(11) NOT NULL
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
-- (1, '2025_09_19_013440_create_address_table', 0),
-- (2, '2025_09_19_013440_create_cart_table', 0),
-- (3, '2025_09_19_013440_create_cart_item_table', 0),
-- (4, '2025_09_19_013440_create_category_table', 0),
-- (5, '2025_09_19_013440_create_gpu_detail_table', 0),
-- (6, '2025_09_19_013440_create_headset_detail_table', 0),
-- (7, '2025_09_19_013440_create_inventory_table', 0),
-- (8, '2025_09_19_013440_create_keyboard_detail_table', 0),
-- (9, '2025_09_19_013440_create_laptop_detail_table', 0),
-- (10, '2025_09_19_013440_create_laptop_gaming_detail_table', 0),
-- (11, '2025_09_19_013440_create_mouse_detail_table', 0),
-- (12, '2025_09_19_013440_create_order_table', 0),
-- (13, '2025_09_19_013440_create_order_detail_table', 0),
-- (14, '2025_09_19_013440_create_product_table', 0),
-- (15, '2025_09_19_013440_create_receipt_table', 0),
-- (16, '2025_09_19_013440_create_receipt_detail_table', 0),
-- (17, '2025_09_19_013440_create_screen_detail_table', 0),
-- (18, '2025_09_19_013440_create_supplier_table', 0),
-- (19, '2025_09_19_013440_create_user_table', 0),
-- (20, '2025_09_19_013443_add_foreign_keys_to_address_table', 0),
-- (21, '2025_09_19_013443_add_foreign_keys_to_cart_table', 0),
-- (22, '2025_09_19_013443_add_foreign_keys_to_cart_item_table', 0),
-- (23, '2025_09_19_013443_add_foreign_keys_to_gpu_detail_table', 0),
-- (24, '2025_09_19_013443_add_foreign_keys_to_headset_detail_table', 0),
-- (25, '2025_09_19_013443_add_foreign_keys_to_inventory_table', 0),
-- (26, '2025_09_19_013443_add_foreign_keys_to_keyboard_detail_table', 0),
-- (27, '2025_09_19_013443_add_foreign_keys_to_laptop_detail_table', 0),
-- (28, '2025_09_19_013443_add_foreign_keys_to_laptop_gaming_detail_table', 0),
-- (29, '2025_09_19_013443_add_foreign_keys_to_mouse_detail_table', 0),
-- (30, '2025_09_19_013443_add_foreign_keys_to_order_table', 0),
-- (31, '2025_09_19_013443_add_foreign_keys_to_order_detail_table', 0),
-- (32, '2025_09_19_013443_add_foreign_keys_to_receipt_table', 0),
-- (33, '2025_09_19_013443_add_foreign_keys_to_receipt_detail_table', 0),
-- (34, '2025_09_19_013443_add_foreign_keys_to_screen_detail_table', 0),
-- (35, '2025_09_19_013443_add_foreign_keys_to_product_table', 0),
-- (36, '2025_09_19_080126_create_sessions_table', 0);