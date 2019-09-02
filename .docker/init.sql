CREATE TABLE `guestbook` (
  `comment_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `comment` varchar(300) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `users` (
  `user_id` int(6) NOT NULL,
  `first_name` varchar(15) DEFAULT NULL,
  `last_name` varchar(15) DEFAULT NULL,
  `user` varchar(15) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `avatar` varchar(70) DEFAULT NULL,
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `failed_login` int(3) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `guestbook` VALUES (1,'This is a test comment.','test');

INSERT INTO `users` VALUES
    (1,'admin','admin','admin','5f4dcc3b5aa765d61d8327deb882cf99','/hackable/users/admin.jpg','2019-09-02 12:22:32',0),
    (2,'Gordon','Brown','gordonb','e99a18c428cb38d5f260853678922e03','/hackable/users/gordonb.jpg','2019-09-02 12:22:32',0),
    (3,'Hack','Me','1337','8d3533d75ae2c3966d7e0d4fcc69216b','/hackable/users/1337.jpg','2019-09-02 12:22:32',0),
    (4,'Pablo','Picasso','pablo','0d107d09f5bbe40cade3de5c71e9e9b7','/hackable/users/pablo.jpg','2019-09-02 12:22:32',0),
    (5,'Bob','Smith','smithy','5f4dcc3b5aa765d61d8327deb882cf99','/hackable/users/smithy.jpg','2019-09-02 12:22:32',0);
