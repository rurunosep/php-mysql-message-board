DROP TABLE IF EXISTS `posts`;
CREATE TABLE `posts` (
  `post_id` int unsigned NOT NULL AUTO_INCREMENT,
  `thread_id` int unsigned NOT NULL,
  `user_id` int unsigned NOT NULL,
  `body` text NOT NULL,
  `posted_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`post_id`)
);

DROP TABLE IF EXISTS `threads`;
CREATE TABLE `threads` (
  `thread_id` int unsigned NOT NULL AUTO_INCREMENT,
  `topic` varchar(255) NOT NULL,
  PRIMARY KEY (`thread_id`)
);

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` int unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(40) NOT NULL,
  `password_hash` char(60) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`)
);