
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `parent` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=37 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `parent`) VALUES
(1, 'furniture', NULL),
(2, 'antiques', 1),
(3, 'automotive', NULL),
(4, 'rvs+camp', 3),
(5, 'atv/utv/snow', 3),
(6, 'auto parts', 3),
(7, 'cars+trucks', 3),
(8, 'motorcycles', 3),
(9, 'boats', 3),
(10, 'household', NULL),
(11, 'appliances', 10),
(12, 'electronics', NULL),
(13, 'computer', 12),
(14, 'cell phones', 12),
(15, 'video gaming', 12),
(16, 'baby+kid', NULL),
(17, 'bikes', NULL),
(18, 'books', NULL),
(19, 'business', NULL),
(20, 'general', NULL),
(21, 'jewelry', NULL),
(22, 'materials', NULL),
(23, 'sporting', NULL),
(24, 'tickets', NULL),
(25, 'tools', NULL),
(26, 'arts+crafts', NULL),
(27, 'beauty+hlth', NULL),
(28, 'clothes+acc', NULL),
(29, 'collectibles', NULL),
(30, 'farm+garden', NULL),
(31, 'garage sale', NULL),
(32, 'heavy equip', NULL),
(33, 'music instr', NULL),
(34, 'photo+video', NULL),
(35, 'cds/dvd/vhs', NULL),
(36, 'toys+games', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE IF NOT EXISTS `items` (
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET latin1 NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `image_uri` varchar(255) CHARACTER SET latin1 NOT NULL,
  `location` varchar(255) CHARACTER SET latin1 NOT NULL,
  `user_id` varchar(255) CHARACTER SET latin1 NOT NULL,
  `is_approved` int(255) NOT NULL DEFAULT '0',
  `category` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`date`, `id`, `name`, `description`, `price`, `image_uri`, `location`, `user_id`, `is_approved`, `category`) VALUES
('0000-00-00 00:00:00', 0, 'Bike', 'Fixed gear bike, blue, front brake', '150.00', 'uploads/img/road_bike.jpg', 'San Francisco', '0', 1, 17),
('2014-04-02 06:45:03', 1, 'Bed frame', 'An ikea bed frame', '60.00', 'uploads/img/bedframe.jpg', '', '2', 1, 1),
('2014-04-22 06:00:16', 2, 'Medium Sized Tv', 'Medium sized, about 26" analog tv. Grey. Takes RCA.', '100.00', 'uploads/img/mediumtv.jpg', '', '1', 1, 12),
('2014-04-22 06:58:58', 3, 'Music note table', 'Music not shaped table', '60.00', 'uploads/img/desk.jpg', '', '4', -1, 1),
('2014-04-22 07:07:55', 4, 'Lagunitas beer', '3 cases of Lagunitas Meople Pumble beer.', '24.00', 'uploads/img/beer.jpg', '', '3', 1, 20),
('2014-04-22 07:41:56', 5, 'Tent', 'Small tent, fits four. Good condition', '30.00', 'uploads/img/tent.jpg', '', '2', 1, 23),
('2014-04-22 07:42:45', 6, 'Rolling desk', 'From Ikea, a good quality rolling desk. Has keyboard pull out and spot for desktop computer.', '20.00', 'uploads/img/smalldesk.jpg', '', '2', 1, 1),
('2014-04-22 07:43:26', 7, 'Loveseat', 'Loveseat couch. Comfy. Seats two comfortably, three it gets cuddly.', '20.00', 'uploads/img/loveseat.jpg', '', '2', 1, 1),
('2014-04-22 07:45:19', 8, 'Car with included art', 'A toyota pickup truck Covered in penny art. Yes penny, as in copper 1 cent coins.', '2000.00', 'uploads/img/car.jpg', '', '4', 1, 7),
('2014-05-05 15:21:32', 9, '2012 Nexus 7', 'First generation Nexus 7. Comes with blue case and screen protector. 32gb', '150.00', 'uploads/img/nexus7_1.jpg, uploads/img/nexus7_2.jpg, uploads/img/nexus7_3.jpg', 'San Francisco, CA', '4', 0, 12),
('2014-05-04 14:21:32', 10, '17inch HP Laptop', 'HP Laptop 17", has windows 7. Fast. HDMI connectivity', '990.00', 'uploads/img/hplaptop.jpg', 'San Francisco, CA', '3', 0, 13),
('2014-05-03 15:21:32', 11, 'Record Player', 'Has kitten stickers on it. Capable playing any size vinyl records', '75.00', 'uploads/img/vinly.jpg', 'San Francisco, CA', '2', 0, 12),
('2014-05-07 15:21:32', 12, 'iPhone 5', 'Comes with case and bubbly screen protector. 16 gb', '150.00', 'uploads/img/iphone5.jpg', 'San Francisco, CA', '2', 1, 14);

-- --------------------------------------------------------

--
-- Table structure for table `shopping_cart`
--

CREATE TABLE IF NOT EXISTS `shopping_cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `items_id` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE IF NOT EXISTS `transaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `buyer_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `is_completed` bit(2) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_admin` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `first_name`, `last_name`, `username`, `email`, `password`, `location`, `is_admin`) VALUES
(1, 'test', 'test', 'test', 'test@test.co', '$1$8z4.vI3.$/LCNKJidxl3MmE3ZKzH8q1', 'test', 1),
(2, 'Sally', 'Jones', 'sjones', 'sjones@gmail.com', '$1$Kv3.LJ2.$FL2UtB5QJSpXrBr3JF4gM1', 'San Francisco, CA', 0),
(3, 'Ryder', 'Bradley', 'rbrad', 'rbrad@gmail.com', '$1$2V1.ho3.$MjV0JgoPgmO384le.Q.8U1', 'San Francisco, CA', 0),
(4, 'Mark', 'Sanderson', 'msand', 'msand@hotmail.com', '$1$W7/.nz3.$tcygwiFl0KIewl8dYl83r1', 'San Jose, CA', 0),
(5, 'Frank', 'Myers', 'frankthetank', 'frankm@aol.com', '$1$kG/.dw/.$rrPr1Aq12x0xLP6J4VhVF.', 'Daly City, CA', 1);

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE IF NOT EXISTS `wishlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `items` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;