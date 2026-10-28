-- ==============================================================================
-- FEATHER FITS E-COMMERCE — HOSTINGER MYSQL DATABASE SCHEMA & INITIAL SEED DUMP
-- Character Set: utf8mb4 (Full Emoji & Unicode Support)
-- Compatible with: MySQL 5.7+, MySQL 8.0+, MariaDB 10.3+ (Hostinger Default)
-- ==============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------------------------------
-- 1. Table: admins
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `admins` (
  `id` varchar(64) NOT NULL,
  `username` varchar(100) NOT NULL UNIQUE,
  `email` varchar(191) NOT NULL UNIQUE,
  `name` varchar(150) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'Super Admin',
  `password` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `admins` (`id`, `username`, `email`, `name`, `role`, `password`, `created_at`)
VALUES ('admin_01', 'admin', 'admin@featherfits.com', 'Arun Kumar', 'Super Admin', 'admin123', '2026-10-28 09:00:00')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- ------------------------------------------------------------------------------
-- 2. Table: users (Customers & VIP Members)
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` varchar(64) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) DEFAULT '',
  `name` varchar(150) NOT NULL,
  `email` varchar(191) NOT NULL UNIQUE,
  `phone` varchar(20) NOT NULL UNIQUE,
  `password` varchar(255) DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 1,
  `member_since` varchar(10) DEFAULT '2024',
  `tier` varchar(50) DEFAULT 'VIP Member',
  `coins` int(11) NOT NULL DEFAULT 100,
  `preferred_size` varchar(10) DEFAULT 'L',
  `addresses` json DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_phone` (`phone`),
  KEY `idx_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `first_name`, `last_name`, `name`, `email`, `phone`, `password`, `is_verified`, `member_since`, `tier`, `coins`, `preferred_size`, `addresses`)
VALUES ('usr_9876543210', 'Arun', 'Kumar', 'Arun Kumar', 'arun.kumar@featherfits.com', '9876543210', 'user123', 1, '2024', 'VIP Member', 450, 'L', '[{"id":"addr_1","tag":"Home","isDefault":true,"name":"Arun Kumar","phone":"+91 98765 43210","line1":"42, Gandhi Road, T. Nagar","city":"Chennai","state":"Tamil Nadu","postalCode":"600017"}]')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- ------------------------------------------------------------------------------
-- 3. Table: carriers (Courier Services & Tracking URL Patterns)
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `carriers` (
  `id` varchar(64) NOT NULL,
  `name` varchar(100) NOT NULL,
  `url_template` varchar(255) DEFAULT '',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `carriers` (`id`, `name`, `url_template`, `is_active`)
VALUES ('bluedart', 'Blue Dart Express', 'https://www.bluedart.com/tracking?trackNumber={TRACKING}', 1)
ON DUPLICATE KEY UPDATE `url_template`=VALUES(`url_template`);
INSERT INTO `carriers` (`id`, `name`, `url_template`, `is_active`)
VALUES ('delhivery', 'Delhivery', 'https://www.delhivery.com/track/package/{TRACKING}', 1)
ON DUPLICATE KEY UPDATE `url_template`=VALUES(`url_template`);
INSERT INTO `carriers` (`id`, `name`, `url_template`, `is_active`)
VALUES ('indiapost', 'India Post Speed', 'https://www.indiapost.gov.in/_layouts/15/dop.portal.tracking/trackconsignment.aspx?consignmentNo={TRACKING}', 1)
ON DUPLICATE KEY UPDATE `url_template`=VALUES(`url_template`);
INSERT INTO `carriers` (`id`, `name`, `url_template`, `is_active`)
VALUES ('dtdc', 'DTDC Express', 'https://www.dtdc.in/tracking/shipment-tracking.asp?tracking_no={TRACKING}', 1)
ON DUPLICATE KEY UPDATE `url_template`=VALUES(`url_template`);
INSERT INTO `carriers` (`id`, `name`, `url_template`, `is_active`)
VALUES ('shadowfax', 'Shadowfax', 'https://tracker.shadowfax.in/#/track/{TRACKING}', 1)
ON DUPLICATE KEY UPDATE `url_template`=VALUES(`url_template`);
INSERT INTO `carriers` (`id`, `name`, `url_template`, `is_active`)
VALUES ('xpressbees', 'Xpressbees', 'https://www.xpressbees.com/track?isawb=true&trackid={TRACKING}', 1)
ON DUPLICATE KEY UPDATE `url_template`=VALUES(`url_template`);
INSERT INTO `carriers` (`id`, `name`, `url_template`, `is_active`)
VALUES ('other', 'Other Courier', '', 1)
ON DUPLICATE KEY UPDATE `url_template`=VALUES(`url_template`);

-- ------------------------------------------------------------------------------
-- 4. Table: products (Apparel Catalog)
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `products` (
  `id` varchar(64) NOT NULL,
  `department` varchar(50) NOT NULL,
  `category` varchar(100) NOT NULL,
  `category_name` varchar(150) NOT NULL,
  `name` varchar(255) NOT NULL,
  `base_price` decimal(10,2) NOT NULL,
  `original_price` decimal(10,2) NOT NULL,
  `rating` decimal(3,2) DEFAULT 4.80,
  `reviews_count` int(11) DEFAULT 50,
  `badge` varchar(50) DEFAULT '',
  `description` text DEFAULT NULL,
  `images` json DEFAULT NULL,
  `colors` json DEFAULT NULL,
  `sizes` json DEFAULT NULL,
  `stock` json DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_dept` (`department`),
  KEY `idx_cat` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `products` (`id`, `department`, `category`, `category_name`, `name`, `base_price`, `original_price`, `rating`, `reviews_count`, `badge`, `description`, `images`, `colors`, `sizes`, `stock`)
VALUES ('ff-anime-01', 'men', 'men-tshirts', 'T-Shirts & Anime', 'Demon Slayer Tanjiro Oversized Graphic Tee ARun', 12999, 1699, 4.9, 142, 'Bestseller', 'Premium heavy-weight 240 GSM combed cotton. Features front-chest \'Metsu\' insignia print and a dynamic full-back Tanjiro Kamado water & flame breathing anime graphic with traditional calligraphy.', '["assets/images/anime-tee-model-front.jpg"]', '[{"name":"Obsidian Black","hex":"#121214"},{"name":"Neon Cyber Violet","hex":"#8E1658"},{"name":"Washed Vintage Grey","hex":"#3D3A40"}]', '["S","M","L","XL","XXL"]', '{"Obsidian Black-S":12,"Obsidian Black-M":24,"Obsidian Black-L":18,"Obsidian Black-XL":15,"Obsidian Black-XXL":5,"Neon Cyber Violet-S":8,"Neon Cyber Violet-M":14,"Neon Cyber Violet-L":10,"Neon Cyber Violet-XL":6,"Neon Cyber Violet-XXL":0,"Washed Vintage Grey-S":15,"Washed Vintage Grey-M":20,"Washed Vintage Grey-L":12,"Washed Vintage Grey-XL":8,"Washed Vintage Grey-XXL":4}')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `base_price`=VALUES(`base_price`);
INSERT INTO `products` (`id`, `department`, `category`, `category_name`, `name`, `base_price`, `original_price`, `rating`, `reviews_count`, `badge`, `description`, `images`, `colors`, `sizes`, `stock`)
VALUES ('ff-anime-02', 'men', 'men-tshirts', 'Men\'s Anime Design T-shirt', 'Spirit Guardian Kitsune Streetwear Tee', 1199, 1499, 4.8, 98, 'Trending', 'Intricately detailed Japanese Kitsune spirit mask artwork blended with celestial cherry blossom typography across the back.', '["assets/images/anime-tee.jpg"]', '[{"name":"Mulberry Wine","hex":"#63103D"},{"name":"Midnight Black","hex":"#1A1A1A"},{"name":"Eggshell White","hex":"#F3EDE8"}]', '["XS","S","M","L","XL"]', '{"Mulberry Wine-S":10,"Mulberry Wine-M":15,"Mulberry Wine-L":8,"Mulberry Wine-XL":4,"Midnight Black-S":14,"Midnight Black-M":22,"Midnight Black-L":16,"Midnight Black-XL":9,"Eggshell White-S":6,"Eggshell White-M":11,"Eggshell White-L":5,"Eggshell White-XL":2}')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `base_price`=VALUES(`base_price`);
INSERT INTO `products` (`id`, `department`, `category`, `category_name`, `name`, `base_price`, `original_price`, `rating`, `reviews_count`, `badge`, `description`, `images`, `colors`, `sizes`, `stock`)
VALUES ('ff-movie-01', 'men', 'men-tshirts', 'Men\'s Vintage Graphic T-shirt', 'Galactic Starship 1982 Vintage Movie Tee', 1399, 1799, 5, 88, 'Collector\'s Pick', 'Authentic mineral-washed vintage look inspired by early 80s sci-fi cinema adventures. Silkscreen distressed print on ultra-soft organic cotton.', '["assets/images/movie-tee.jpg","assets/images/hero-banner.jpg"]', '[{"name":"Mineral Washed Charcoal","hex":"#323336"},{"name":"Deep Plum","hex":"#2D0B34"},{"name":"Desert Sand","hex":"#B8A793"}]', '["S","M","L","XL","XXL"]', '{"Mineral Washed Charcoal-S":10,"Mineral Washed Charcoal-M":19,"Mineral Washed Charcoal-L":15,"Mineral Washed Charcoal-XL":7,"Mineral Washed Charcoal-XXL":3,"Deep Plum-S":12,"Deep Plum-M":18,"Deep Plum-L":14,"Deep Plum-XL":8,"Deep Plum-XXL":4,"Desert Sand-S":5,"Desert Sand-M":9,"Desert Sand-L":6,"Desert Sand-XL":3,"Desert Sand-XXL":0}')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `base_price`=VALUES(`base_price`);
INSERT INTO `products` (`id`, `department`, `category`, `category_name`, `name`, `base_price`, `original_price`, `rating`, `reviews_count`, `badge`, `description`, `images`, `colors`, `sizes`, `stock`)
VALUES ('ff-movie-02', 'men', 'men-tshirts', 'Men\'s Vintage Graphic T-shirt', 'Neo Noir Cinema Club Graphic T-Shirt', 1249, 1599, 4.7, 64, 'New', 'Minimalist silhouette framing homage to iconic crime noir cinema with high-density typographic typography along the spine.', '["assets/images/movie-tee.jpg"]', '[{"name":"Pure Midnight","hex":"#0E0E10"},{"name":"Heather Slate","hex":"#565860"}]', '["XS","S","M","L","XL"]', '{"Pure Midnight-S":11,"Pure Midnight-M":20,"Pure Midnight-L":14,"Pure Midnight-XL":9,"Heather Slate-S":8,"Heather Slate-M":14,"Heather Slate-L":10,"Heather Slate-XL":5}')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `base_price`=VALUES(`base_price`);
INSERT INTO `products` (`id`, `department`, `category`, `category_name`, `name`, `base_price`, `original_price`, `rating`, `reviews_count`, `badge`, `description`, `images`, `colors`, `sizes`, `stock`)
VALUES ('ff-plain-01', 'men', 'men-tshirts', 'Men\'s Minimalist T-shirt', 'Feather Signature Heavyweight Plain Tee', 899, 1199, 4.9, 310, 'Wardrobe Essential', 'The quintessential minimalist T-shirt. Crafted from 100% Peruvian Pima cotton with double-stitched reinforced collar and tailored relaxed drape.', '["assets/images/plain-tee.jpg","assets/images/hero-banner.jpg"]', '[{"name":"Burgundy Wine","hex":"#681D39"},{"name":"Deep Plum","hex":"#2D0B34"},{"name":"Crisp Chalk White","hex":"#FDFDFD"},{"name":"Midnight Onyx","hex":"#161618"}]', '["XS","S","M","L","XL","XXL"]', '{"Burgundy Wine-S":25,"Burgundy Wine-M":40,"Burgundy Wine-L":35,"Burgundy Wine-XL":20,"Burgundy Wine-XXL":12,"Deep Plum-S":18,"Deep Plum-M":32,"Deep Plum-L":28,"Deep Plum-XL":14,"Deep Plum-XXL":8,"Crisp Chalk White-S":30,"Crisp Chalk White-M":50,"Crisp Chalk White-L":45,"Crisp Chalk White-XL":25,"Crisp Chalk White-XXL":15,"Midnight Onyx-S":28,"Midnight Onyx-M":48,"Midnight Onyx-L":40,"Midnight Onyx-XL":22,"Midnight Onyx-XXL":10}')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `base_price`=VALUES(`base_price`);
INSERT INTO `products` (`id`, `department`, `category`, `category_name`, `name`, `base_price`, `original_price`, `rating`, `reviews_count`, `badge`, `description`, `images`, `colors`, `sizes`, `stock`)
VALUES ('ff-plain-02', 'men', 'men-tshirts', 'Men\'s Minimalist T-shirt', 'Supima Cotton Minimalist Drop-Shoulder Tee', 999, 1299, 4.8, 175, 'Popular', 'Effortless boxy cut featuring raw seam finishing and pre-shrunk organic jersey fabric engineered for daily luxury comfort.', '["assets/images/plain-tee.jpg"]', '[{"name":"Soft Coral Rose","hex":"#D62568"},{"name":"Charcoal Heather","hex":"#3A383E"},{"name":"Sage Mist","hex":"#7E8D85"}]', '["S","M","L","XL"]', '{"Soft Coral Rose-S":12,"Soft Coral Rose-M":20,"Soft Coral Rose-L":15,"Soft Coral Rose-XL":8,"Charcoal Heather-S":16,"Charcoal Heather-M":28,"Charcoal Heather-L":20,"Charcoal Heather-XL":10,"Sage Mist-S":10,"Sage Mist-M":14,"Sage Mist-L":9,"Sage Mist-XL":4}')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `base_price`=VALUES(`base_price`);
INSERT INTO `products` (`id`, `department`, `category`, `category_name`, `name`, `base_price`, `original_price`, `rating`, `reviews_count`, `badge`, `description`, `images`, `colors`, `sizes`, `stock`)
VALUES ('ff-men-shirt-01', 'men', 'men-shirts', 'Men\'s Casual Shirt', 'Men\'s Relaxed Linen Blend Streetwear Overshirt', 1699, 2299, 4.9, 92, 'Hot Poster Pick', 'Tailored from a breathable French flax linen & combed cotton blend. Features a modern cuban collar, tortoiseshell buttons, and double chest utility pockets perfect for layering over graphic tees.', '["assets/images/men-shirts-poster.jpg","assets/images/mens-apparel.jpg"]', '[{"name":"Natural Flax Linen","hex":"#D8C7B5"},{"name":"Midnight Navy","hex":"#1B2430"},{"name":"Olive Moss","hex":"#4E5340"}]', '["S","M","L","XL","XXL"]', '{"Natural Flax Linen-S":10,"Natural Flax Linen-M":22,"Natural Flax Linen-L":18,"Natural Flax Linen-XL":9,"Natural Flax Linen-XXL":4,"Midnight Navy-S":8,"Midnight Navy-M":16,"Midnight Navy-L":12,"Midnight Navy-XL":6,"Midnight Navy-XXL":2,"Olive Moss-S":6,"Olive Moss-M":14,"Olive Moss-L":10,"Olive Moss-XL":5,"Olive Moss-XXL":1}')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `base_price`=VALUES(`base_price`);
INSERT INTO `products` (`id`, `department`, `category`, `category_name`, `name`, `base_price`, `original_price`, `rating`, `reviews_count`, `badge`, `description`, `images`, `colors`, `sizes`, `stock`)
VALUES ('ff-men-01', 'men', 'men-shirts', 'Men\'s Casual Shirt', 'Tailored Indigo Denim Overshirt & Tee Set', 2499, 3299, 4.9, 119, 'Trending', 'Rugged yet refined raw indigo denim layer paired seamlessly with our signature graphic plum crewneck for effortlessly elevated streetwear.', '["assets/images/mens-apparel.jpg","assets/images/hero-banner.jpg"]', '[{"name":"Raw Indigo & Plum","hex":"#1E2A4A"},{"name":"Washed Black & Wine","hex":"#2B1B26"}]', '["S","M","L","XL","XXL"]', '{"Raw Indigo & Plum-S":8,"Raw Indigo & Plum-M":18,"Raw Indigo & Plum-L":14,"Raw Indigo & Plum-XL":7,"Raw Indigo & Plum-XXL":3,"Washed Black & Wine-S":6,"Washed Black & Wine-M":12,"Washed Black & Wine-L":10,"Washed Black & Wine-XL":5,"Washed Black & Wine-XXL":2}')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `base_price`=VALUES(`base_price`);
INSERT INTO `products` (`id`, `department`, `category`, `category_name`, `name`, `base_price`, `original_price`, `rating`, `reviews_count`, `badge`, `description`, `images`, `colors`, `sizes`, `stock`)
VALUES ('ff-men-02', 'men', 'men-bottoms', 'Men\'s Bottomwear', 'Urban Explorer Cargo Pants & Fitted Graphic Tee', 1899, 2499, 4.7, 84, 'New Arrival', 'Constructed with ripstop cotton twill and tailored silhouette for all-day comfort, multi-pocket utility, and bold street aesthetics.', '["assets/images/mens-apparel.jpg"]', '[{"name":"Midnight Plum","hex":"#2D0B34"},{"name":"Dark Carbon","hex":"#202124"}]', '["S","M","L","XL"]', '{"Midnight Plum-S":10,"Midnight Plum-M":15,"Midnight Plum-L":12,"Midnight Plum-XL":6,"Dark Carbon-S":14,"Dark Carbon-M":20,"Dark Carbon-L":18,"Dark Carbon-XL":8}')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `base_price`=VALUES(`base_price`);
INSERT INTO `products` (`id`, `department`, `category`, `category_name`, `name`, `base_price`, `original_price`, `rating`, `reviews_count`, `badge`, `description`, `images`, `colors`, `sizes`, `stock`)
VALUES ('ff-women-02', 'women', 'women-tshirts', 'Women\'s Graphic Top', 'Confidence Cropped Luxe Cotton T-Shirt', 1099, 1499, 4.8, 104, 'Trending', 'Boxy cropped silhouette flattering every shape with subtle embroidered feather insignia on the cuff and luxurious softness.', '["assets/images/womens-apparel.jpg"]', '[{"name":"Mulberry Wine","hex":"#8E1658"},{"name":"Pure White","hex":"#FFFFFF"},{"name":"Charcoal Noir","hex":"#1C1B1F"}]', '["XS","S","M","L"]', '{"Mulberry Wine-XS":12,"Mulberry Wine-S":22,"Mulberry Wine-M":18,"Mulberry Wine-L":8,"Pure White-XS":15,"Pure White-S":30,"Pure White-M":24,"Pure White-L":10,"Charcoal Noir-XS":10,"Charcoal Noir-S":19,"Charcoal Noir-M":15,"Charcoal Noir-L":6}')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `base_price`=VALUES(`base_price`);
INSERT INTO `products` (`id`, `department`, `category`, `category_name`, `name`, `base_price`, `original_price`, `rating`, `reviews_count`, `badge`, `description`, `images`, `colors`, `sizes`, `stock`)
VALUES ('ff-women-shirt-01', 'women', 'women-shirts', 'Women\'s Shirt', 'Women\'s Relaxed Pastel Poplin Oversized Shirt', 1599, 2199, 4.9, 76, 'Poster Spotlight', 'Feather-light woven cotton poplin shirt with a modern dropped shoulder, mother-of-pearl buttons, and airy breezy silhouette. Effortlessly styled tucked or unbuttoned over a bralette.', '["assets/images/women-shirts-poster.jpg","assets/images/womens-apparel.jpg"]', '[{"name":"Dusty Rose Pink","hex":"#D49B9E"},{"name":"Crisp Pure White","hex":"#FFFFFF"},{"name":"Sky Blue Breeze","hex":"#9EC2E8"}]', '["XS","S","M","L","XL"]', '{"Dusty Rose Pink-XS":8,"Dusty Rose Pink-S":18,"Dusty Rose Pink-M":20,"Dusty Rose Pink-L":10,"Dusty Rose Pink-XL":4,"Crisp Pure White-XS":12,"Crisp Pure White-S":25,"Crisp Pure White-M":28,"Crisp Pure White-L":14,"Crisp Pure White-XL":6,"Sky Blue Breeze-XS":6,"Sky Blue Breeze-S":15,"Sky Blue Breeze-M":16,"Sky Blue Breeze-L":8,"Sky Blue Breeze-XL":3}')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `base_price`=VALUES(`base_price`);
INSERT INTO `products` (`id`, `department`, `category`, `category_name`, `name`, `base_price`, `original_price`, `rating`, `reviews_count`, `badge`, `description`, `images`, `colors`, `sizes`, `stock`)
VALUES ('ff-women-shirt-02', 'women', 'women-shirts', 'Women\'s Shirt', 'Women\'s Vintage Striped Boxy Resort Shirt', 1449, 1999, 4.7, 52, 'Summer Chic', 'Breathable rayon weave featuring vertical vintage resort stripes, relaxed notched collar, and easy vacation drape.', '["assets/images/women-shirts-poster.jpg"]', '[{"name":"Sand & Plum Stripe","hex":"#BCA37F"},{"name":"Noir Monochrome","hex":"#222222"}]', '["XS","S","M","L"]', '{"Sand & Plum Stripe-XS":7,"Sand & Plum Stripe-S":14,"Sand & Plum Stripe-M":16,"Sand & Plum Stripe-L":8,"Noir Monochrome-XS":9,"Noir Monochrome-S":15,"Noir Monochrome-M":18,"Noir Monochrome-L":7}')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `base_price`=VALUES(`base_price`);
INSERT INTO `products` (`id`, `department`, `category`, `category_name`, `name`, `base_price`, `original_price`, `rating`, `reviews_count`, `badge`, `description`, `images`, `colors`, `sizes`, `stock`)
VALUES ('ff-women-01', 'women', 'women-bottoms', 'Women\'s Blazer & Set', 'Metropolitan Fuchsia Blazer & Fitted Modal Tee', 2799, 3599, 5, 167, 'Editor\'s Choice', 'Sharp contemporary tailoring meeting fluid luxury comfort. Deep fuchsia unstructured blazer with matching silk-blend modal inner tee.', '["assets/images/womens-apparel.jpg","assets/images/hero-banner.jpg"]', '[{"name":"Vivid Magenta","hex":"#D62568"},{"name":"Deep Royal Plum","hex":"#2D0B34"},{"name":"Blush Champagne","hex":"#E8C2CA"}]', '["XS","S","M","L","XL"]', '{"Vivid Magenta-XS":9,"Vivid Magenta-S":18,"Vivid Magenta-M":22,"Vivid Magenta-L":12,"Vivid Magenta-XL":4,"Deep Royal Plum-XS":7,"Deep Royal Plum-S":14,"Deep Royal Plum-M":19,"Deep Royal Plum-L":9,"Deep Royal Plum-XL":3,"Blush Champagne-XS":5,"Blush Champagne-S":10,"Blush Champagne-M":12,"Blush Champagne-L":6,"Blush Champagne-XL":2}')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `base_price`=VALUES(`base_price`);
INSERT INTO `products` (`id`, `department`, `category`, `category_name`, `name`, `base_price`, `original_price`, `rating`, `reviews_count`, `badge`, `description`, `images`, `colors`, `sizes`, `stock`)
VALUES ('ff-kids-01', 'kids', 'kids-tshirts', 'Kids\' Anime Graphic Tee', 'Kids\' Naruto Spirit Anime Streetwear Tee', 799, 1099, 4.9, 88, 'Kids Bestseller', '100% hypoallergenic organic cotton for young anime fans. Vibrant screen-printed shinobi artwork with skin-safe dyes and tear-away tags for total comfort.', '["assets/images/kids-anime-poster.jpg","assets/images/kids-hero-banner.jpg"]', '[{"name":"Ninja Black","hex":"#111113"},{"name":"Fire Orange","hex":"#E05A1C"},{"name":"Cloud White","hex":"#FAFAFA"}]', '["4-5Y","6-7Y","8-9Y","10-11Y","12-13Y"]', '{"Ninja Black-4-5Y":15,"Ninja Black-6-7Y":25,"Ninja Black-8-9Y":20,"Ninja Black-10-11Y":15,"Ninja Black-12-13Y":10,"Fire Orange-4-5Y":10,"Fire Orange-6-7Y":18,"Fire Orange-8-9Y":15,"Fire Orange-10-11Y":10,"Fire Orange-12-13Y":5,"Cloud White-4-5Y":12,"Cloud White-6-7Y":20,"Cloud White-8-9Y":18,"Cloud White-10-11Y":12,"Cloud White-12-13Y":8}')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `base_price`=VALUES(`base_price`);
INSERT INTO `products` (`id`, `department`, `category`, `category_name`, `name`, `base_price`, `original_price`, `rating`, `reviews_count`, `badge`, `description`, `images`, `colors`, `sizes`, `stock`)
VALUES ('ff-kids-02', 'kids', 'kids-shirts', 'Kids\' Casual Shirt', 'Kids\' Urban Explorer Cotton Twill Overshirt', 1199, 1599, 4.8, 45, 'Smart Street', 'Durable and soft brushed cotton twill overshirt with chest flap pockets, sturdy snap buttons, and relaxed layering cut for school or weekend fun.', '["assets/images/kids-shirt-poster.jpg","assets/images/kids-hero-banner.jpg"]', '[{"name":"Olive Green","hex":"#526046"},{"name":"Mustard Gold","hex":"#CF9B28"},{"name":"Navy Blue","hex":"#1D2838"}]', '["4-5Y","6-7Y","8-9Y","10-11Y","12-13Y"]', '{"Olive Green-4-5Y":8,"Olive Green-6-7Y":15,"Olive Green-8-9Y":12,"Olive Green-10-11Y":8,"Olive Green-12-13Y":4,"Mustard Gold-4-5Y":6,"Mustard Gold-6-7Y":12,"Mustard Gold-8-9Y":10,"Mustard Gold-10-11Y":6,"Mustard Gold-12-13Y":3,"Navy Blue-4-5Y":10,"Navy Blue-6-7Y":18,"Navy Blue-8-9Y":14,"Navy Blue-10-11Y":9,"Navy Blue-12-13Y":5}')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `base_price`=VALUES(`base_price`);
INSERT INTO `products` (`id`, `department`, `category`, `category_name`, `name`, `base_price`, `original_price`, `rating`, `reviews_count`, `badge`, `description`, `images`, `colors`, `sizes`, `stock`)
VALUES ('ff-kids-04', 'kids', 'kids-shirts', 'Kids\' Casual Shirt', 'Kids\' Tokyo Kanji Button-Down Oxford Shirt', 899, 1299, 4.7, 38, 'New Arrival', 'Crisp breathable oxford weave featuring subtle kanji graphic embroidery on the pocket and soft curved hem.', '["assets/images/kids-shirt-poster.jpg"]', '[{"name":"Chalk White","hex":"#FFFFFF"},{"name":"Sky Pastel Blue","hex":"#A8C7EC"}]', '["4-5Y","6-7Y","8-9Y","10-11Y"]', '{"Chalk White-4-5Y":10,"Chalk White-6-7Y":16,"Chalk White-8-9Y":12,"Chalk White-10-11Y":7,"Sky Pastel Blue-4-5Y":8,"Sky Pastel Blue-6-7Y":14,"Sky Pastel Blue-8-9Y":10,"Sky Pastel Blue-10-11Y":5}')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `base_price`=VALUES(`base_price`);

-- ------------------------------------------------------------------------------
-- 5. Table: orders (Customer Orders & Live Dispatch)
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `orders` (
  `id` varchar(64) NOT NULL,
  `customer_name` varchar(150) NOT NULL,
  `customer_email` varchar(191) NOT NULL,
  `customer_phone` varchar(30) NOT NULL,
  `customer_city` varchar(100) DEFAULT 'Chennai',
  `items` json NOT NULL,
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `shipping_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Pending',
  `carrier` varchar(100) DEFAULT 'Blue Dart Express',
  `tracking_number` varchar(100) DEFAULT NULL,
  `tracking_url` varchar(255) DEFAULT NULL,
  `estimated_delivery` varchar(50) DEFAULT NULL,
  `shipped_at` datetime DEFAULT NULL,
  `delivered_at` datetime DEFAULT NULL,
  `timeline` json DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`),
  KEY `idx_phone` (`customer_phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `orders` (`id`, `customer_name`, `customer_email`, `customer_phone`, `customer_city`, `items`, `subtotal`, `tax`, `shipping_fee`, `total`, `status`, `carrier`, `tracking_number`, `tracking_url`, `estimated_delivery`, `shipped_at`, `delivered_at`, `timeline`, `created_at`)
VALUES ('FF-90214', 'Arun Kumar', 'arun.kumar@featherfits.com', '+91 98765 43210', 'Chennai', '[{"name":"Cyberpunk Ronin Oversized Graphic Tee","size":"L","qty":1,"price":1299,"image":"assets/images/anime-tee-model-front.jpg"}]', 1363.95, 0, 0, 1363.95, 'Shipped', 'Blue Dart Express', 'BD-9402849182', 'https://www.bluedart.com/tracking?trackNumber=BD-9402849182', '2026-10-29', '2026-10-27 14:30:00', NULL, '[{"status":"Order Placed","time":"2026-10-27 09:15 AM","note":"Payment confirmed via Razorpay / UPI","updatedBy":"System"},{"status":"Packed","time":"2026-10-27 12:45 PM","note":"Quality passed and packed in luxury bio-mailer","updatedBy":"Warehouse Lead"},{"status":"Shipped","time":"2026-10-27 02:30 PM","note":"Handed over to Blue Dart Express (AWB: BD-9402849182)","updatedBy":"Arun Kumar"}]', '2026-10-27 09:15:00')
ON DUPLICATE KEY UPDATE `status`=VALUES(`status`);
INSERT INTO `orders` (`id`, `customer_name`, `customer_email`, `customer_phone`, `customer_city`, `items`, `subtotal`, `tax`, `shipping_fee`, `total`, `status`, `carrier`, `tracking_number`, `tracking_url`, `estimated_delivery`, `shipped_at`, `delivered_at`, `timeline`, `created_at`)
VALUES ('FF-90213', 'Priya Sundaram', 'priya.s@gmail.com', '+91 98401 23456', 'Bengaluru', '[{"name":"Confidence Cropped Luxe Cotton T-Shirt","size":"M","qty":2,"price":999,"image":"assets/images/plain-tee.jpg"}]', 2097.9, 0, 0, 2097.9, 'Shipped', 'Blue Dart Express', 'BD-8888111122', 'https://www.bluedart.com/tracking?trackNumber=BD-8888111122', '2026-10-31', '2026-10-28 10:21:24+01:00', NULL, '[{"status":"Order Placed","time":"2026-10-28 08:30 AM","note":"Order verified via VIP checkout","updatedBy":"System"},{"status":"Processing","time":"2026-10-28 09:00 AM","note":"Inventory reserved and allocated to packing station","updatedBy":"Admin"},{"status":"Shipped","time":"2026-10-28 10:21 AM","note":"Bulk updated via CSV: Blue Dart Express (AWB: BD-8888111122)","updatedBy":"Bulk CSV"}]', '2026-10-28 08:30:00')
ON DUPLICATE KEY UPDATE `status`=VALUES(`status`);
INSERT INTO `orders` (`id`, `customer_name`, `customer_email`, `customer_phone`, `customer_city`, `items`, `subtotal`, `tax`, `shipping_fee`, `total`, `status`, `carrier`, `tracking_number`, `tracking_url`, `estimated_delivery`, `shipped_at`, `delivered_at`, `timeline`, `created_at`)
VALUES ('FF-90212', 'Vikram Rathore', 'vikram.r@outlook.com', '+91 97112 34567', 'Mumbai', '[{"name":"Demon Slayer Tanjiro Graphic Tee","size":"XL","qty":1,"price":1299,"image":"assets/images/anime-tee.jpg"},{"name":"Relaxed Summer Linen Long Sleeve Shirt","size":"XL","qty":1,"price":1799,"image":"assets/images/movie-tee.jpg"}]', 3252.9, 0, 0, 3252.9, 'Delivered', 'Blue Dart Express', 'BD-9401773612', 'https://www.bluedart.com/tracking?trackNumber=BD-9401773612', '2026-10-27', '2026-10-25 10:00:00', '2026-10-27 16:20:00', '[{"status":"Order Placed","time":"2026-10-24 06:45 PM","note":"Order confirmed","updatedBy":"System"},{"status":"Packed","time":"2026-10-25 08:30 AM","note":"Packed and barcoded","updatedBy":"Warehouse"},{"status":"Shipped","time":"2026-10-25 10:00 AM","note":"Shipped via Blue Dart (BD-9401773612)","updatedBy":"Arun Kumar"},{"status":"Out for Delivery","time":"2026-10-27 10:15 AM","note":"Courier out for delivery in Mumbai","updatedBy":"Carrier Sync"},{"status":"Delivered","time":"2026-10-27 04:20 PM","note":"Delivered directly to customer. Signed by Vikram.","updatedBy":"Blue Dart API"}]', '2026-10-24 18:45:00')
ON DUPLICATE KEY UPDATE `status`=VALUES(`status`);
INSERT INTO `orders` (`id`, `customer_name`, `customer_email`, `customer_phone`, `customer_city`, `items`, `subtotal`, `tax`, `shipping_fee`, `total`, `status`, `carrier`, `tracking_number`, `tracking_url`, `estimated_delivery`, `shipped_at`, `delivered_at`, `timeline`, `created_at`)
VALUES ('FF-90211', 'Ananya Iyer', 'ananya.iyer@yahoo.com', '+91 94441 89012', 'Coimbatore', '[{"name":"Kids\' Naruto Spirit Anime Streetwear Tee","size":"S","qty":1,"price":799,"image":"assets/images/anime-tee-model-front.jpg"}]', 838.95, 0, 0, 838.95, 'Shipped', 'India Post Speed', 'IPS-9999000011', 'https://www.indiapost.gov.in/_layouts/15/dop.portal.tracking/trackconsignment.aspx?consignmentNo=IPS-9999000011', '2026-10-27', '2026-10-28 10:21:24+01:00', '2026-10-27 13:45:00', '[{"status":"Order Placed","time":"2026-10-25 02:10 PM","note":"Order confirmed","updatedBy":"System"},{"status":"Shipped","time":"2026-10-26 09:00 AM","note":"Dispatched via India Post Speed Post","updatedBy":"Admin"},{"status":"Delivered","time":"2026-10-27 01:45 PM","note":"Delivered to recipient address","updatedBy":"Post Master"},{"status":"Shipped","time":"2026-10-28 10:21 AM","note":"Bulk updated via CSV: India Post Speed (AWB: IPS-9999000011)","updatedBy":"Bulk CSV"}]', '2026-10-25 14:10:00')
ON DUPLICATE KEY UPDATE `status`=VALUES(`status`);
INSERT INTO `orders` (`id`, `customer_name`, `customer_email`, `customer_phone`, `customer_city`, `items`, `subtotal`, `tax`, `shipping_fee`, `total`, `status`, `carrier`, `tracking_number`, `tracking_url`, `estimated_delivery`, `shipped_at`, `delivered_at`, `timeline`, `created_at`)
VALUES ('FF-90210', 'Rahul Sharma', 'rahul.sharma@techcorp.in', '+91 98110 55432', 'Hyderabad', '[{"name":"Tokyo Drift Midnight Club Vintage Tee","size":"L","qty":2,"price":1299,"image":"assets/images/vintage-tee.jpg"}]', 2727.9, 0, 0, 2727.9, 'Shipped', 'Delhivery', 'DEL-9988776655', 'https://www.delhivery.com/track/package/DEL-9988776655', '2026-10-31', '2026-10-28 10:21:24+01:00', NULL, '[{"status":"Order Placed","time":"2026-10-28 10:20 AM","note":"Order placed with COD payment","updatedBy":"System"},{"status":"Shipped","time":"2026-10-28 10:21 AM","note":"Dispatched via Delhivery (AWB: DEL-9988776655)","updatedBy":"Arun Kumar"}]', '2026-10-28 10:20:00')
ON DUPLICATE KEY UPDATE `status`=VALUES(`status`);
INSERT INTO `orders` (`id`, `customer_name`, `customer_email`, `customer_phone`, `customer_city`, `items`, `subtotal`, `tax`, `shipping_fee`, `total`, `status`, `carrier`, `tracking_number`, `tracking_url`, `estimated_delivery`, `shipped_at`, `delivered_at`, `timeline`, `created_at`)
VALUES ('FF-90209', 'Divya Prakash', 'divya.p@gmail.com', '+91 98840 99887', 'Madurai', '[{"name":"Attack on Titan Scout Regiment Vintage Tee","size":"M","qty":1,"price":1299,"image":"assets/images/anime-tee.jpg"}]', 1363.95, 0, 0, 1363.95, 'Shipped', 'Delhivery', 'DEL-901928374', 'https://www.delhivery.com/track/package/DEL-901928374', '2026-10-24', '2026-10-21 10:00:00', NULL, '[{"status":"Order Placed","time":"2026-10-20 11:00 AM","note":"Order placed","updatedBy":"System"},{"status":"Shipped","time":"2026-10-21 10:00 AM","note":"Shipped via Delhivery (DEL-901928374)","updatedBy":"Admin"}]', '2026-10-20 11:00:00')
ON DUPLICATE KEY UPDATE `status`=VALUES(`status`);

-- ------------------------------------------------------------------------------
-- 6. Table: banners (Storefront Hero Banners CMS)
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `banners` (
  `department` varchar(50) NOT NULL,
  `badge` varchar(150) NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `primary_cta` json DEFAULT NULL,
  `secondary_cta` json DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`department`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `banners` (`department`, `badge`, `title`, `subtitle`, `image`, `primary_cta`, `secondary_cta`)
VALUES ('all', 'FEATHER FITS • CONFIDENCE FITS YOU', 'Confidence Fits You. <span>Express Every Vibe.</span>', 'Elevate your daily rotation with heavyweight combed cotton tees, iconic anime art, cinematic vintage graphics, and modern street tailoring across Men, Women & Kid', 'assets/images/hero-banner.jpg', '{"label":"Explore Men\'s Drops","dept":"men"}', '{"label":"Explore Women\'s Fits","dept":"women"}')
ON DUPLICATE KEY UPDATE `title`=VALUES(`title`), `image`=VALUES(`image`);
INSERT INTO `banners` (`department`, `badge`, `title`, `subtitle`, `image`, `primary_cta`, `secondary_cta`)
VALUES ('men', 'MEN\'S APPAREL • STREETWEAR & ESSENTIALS', 'Men\'s Streetwear <span>& Modern Tailoring.</span>', 'Engineered for pure style and heavy-cotton durability. Browse oversized anime prints, casual linen overshirts, and tailored utility cargos.', 'assets/images/mens-apparel.jpg', '{"label":"Shop Men\'s T-Shirts","subcat":"men-tshirts"}', '{"label":"Shop Men\'s Shirts","subcat":"men-shirts"}')
ON DUPLICATE KEY UPDATE `title`=VALUES(`title`), `image`=VALUES(`image`);
INSERT INTO `banners` (`department`, `badge`, `title`, `subtitle`, `image`, `primary_cta`, `secondary_cta`)
VALUES ('women', 'WOMEN\'S COLLECTION • CONTEMPORARY ELEGANCE', 'Women\'s Luxury <span>Street & Relaxed Fits.</span>', 'Chic structured blazers, breezy linen overshirts, cropped organic cotton tees, and matching coordinates tailored to perfection.', 'assets/images/womens-apparel.jpg', '{"label":"Shop Women\'s Tops","subcat":"women-tshirts"}', '{"label":"Shop Women\'s Shirts","subcat":"women-shirts"}')
ON DUPLICATE KEY UPDATE `title`=VALUES(`title`), `image`=VALUES(`image`);
INSERT INTO `banners` (`department`, `badge`, `title`, `subtitle`, `image`, `primary_cta`, `secondary_cta`)
VALUES ('kids', 'KIDS\' STREETWEAR • PLAYFUL & DURABLE', 'Kids\' Anime Drops <span>& Urban Playwear.</span>', 'Ultra-soft 100% hypoallergenic combed cotton tees, authentic anime designs, light breathable overshirts, and comfortable play sets.', 'assets/images/kids-hero-banner.jpg', '{"label":"Shop Kids\' Anime Tees","subcat":"kids-tshirts"}', '{"label":"Shop Kids\' Sets","subcat":"kids-sets"}')
ON DUPLICATE KEY UPDATE `title`=VALUES(`title`), `image`=VALUES(`image`);

-- ------------------------------------------------------------------------------
-- 7. Table: announcements (Top Sticky Bar Announcement)
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `announcements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `announcements` (`id`, `text`, `is_active`)
VALUES (1, 'USE CODE <strong>FEATHER20</strong> FOR 20% OFF ALL OVERSIZED STREETWEAR • FREE EXPRESS SHIPPING OVER ₹999', 1)
ON DUPLICATE KEY UPDATE `text`=VALUES(`text`);

-- ------------------------------------------------------------------------------
-- 8. Table: otps (Registration & Password Reset Verifications)
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `otps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `target` varchar(191) NOT NULL,
  `otp_code` varchar(10) NOT NULL,
  `expires_at` datetime NOT NULL,
  `is_used` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_target` (`target`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
