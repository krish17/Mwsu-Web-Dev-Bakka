CREATE TABLE IF NOT EXISTS `products` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `category` varchar(64) NOT NULL,
  `desc` mediumtext NOT NULL,
  `price` double(10,2) NOT NULL,
  `img` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6886 ;