<?php
require_once "../database.php";

$db->db_change("CREATE TABLE `USERS` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(512) NOT NULL,
  `email` varchar(512) NOT NULL,
  `email_notify` int(11) DEFAULT '1',
  `password` text NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `USERS_login_uindex` (`login`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1");

$db->db_change("CREATE TABLE `USER_TEMP` (
  `temp_id` int(11) NOT NULL AUTO_INCREMENT,
  `email` text NOT NULL,
  `token` text NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`temp_id`)
) ENGINE=InnoDB AUTO_INCREMENT=126 DEFAULT CHARSET=latin1");

$db->db_change("CREATE TABLE `USER_RESTORE` (
  `temp_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `email` varchar(512) NOT NULL,
  `token` text NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`temp_id`),
  KEY `USER_RESTORE_USER_ID` (`user_id`),
  CONSTRAINT `USER_RESTORE_USER_ID` FOREIGN KEY (`user_id`) 
      REFERENCES `USERS` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1");

$db->db_change("CREATE TABLE `USER_PHOTO` (
  `photo_id` int(11) NOT NULL AUTO_INCREMENT,
  `photo_token` text,
  `user_id` int(11) DEFAULT NULL,
  `filter_id` int(11) DEFAULT NULL,
  `photo_src` text NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`photo_id`),
  KEY `USER_PHOTO_USERS_user_id_fk` (`user_id`),
  CONSTRAINT `USER_PHOTO_USERS_user_id_fk` FOREIGN KEY (`user_id`) 
      REFERENCES `USERS` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1110 DEFAULT CHARSET=latin1");

$db->db_change("CREATE TABLE `LIKES` (
  `like_id` int(11) NOT NULL AUTO_INCREMENT,
  `photo_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`like_id`),
  KEY `LIKES_USERS_user_id_fk` (`user_id`),
  KEY `LIKES_USER_PHOTO_photo_id_fk` (`photo_id`),
  CONSTRAINT `LIKES_USERS_user_id_fk` FOREIGN KEY (`user_id`) 
      REFERENCES `USERS` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `LIKES_USER_PHOTO_photo_id_fk` FOREIGN KEY (`photo_id`) 
      REFERENCES `USER_PHOTO` (`photo_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=98 DEFAULT CHARSET=latin1");

$db->db_change("CREATE TABLE `EMAIL_CHANGE` (
  `change_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `new_email` text NOT NULL,
  `token` text NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`change_id`),
  KEY `EMAIL_CHANGE_USER_ID` (`user_id`),
  CONSTRAINT `EMAIL_CHANGE_USER_ID` FOREIGN KEY (`user_id`) 
      REFERENCES `USERS` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1");

$db->db_change("CREATE TABLE `COMMENTS` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `photo_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `comment_text` text,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`comment_id`),
  KEY `COMMENTS_USERS_user_id_fk` (`user_id`),
  KEY `COMMENTS_USER_PHOTO_photo_id_fk` (`photo_id`),
  CONSTRAINT `COMMENTS_USERS_user_id_fk` FOREIGN KEY (`user_id`) 
      REFERENCES `USERS` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `COMMENTS_USER_PHOTO_photo_id_fk` FOREIGN KEY (`photo_id`) 
      REFERENCES `USER_PHOTO` (`photo_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=144 DEFAULT CHARSET=latin1");

$db->db_change("CREATE TABLE `ERR_CODES` (
  `err_id` int(11) NOT NULL AUTO_INCREMENT,
  `err_text` text,
  PRIMARY KEY (`err_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1");

$db->db_change("INSERT INTO `ERR_CODES` VALUES 
                               ('1', 'OK'), 
                               ('2', 'Email must be valid!'), 
                               ('3', 'Password doesn\'t match!'), 
                               ('4', 'Password too short!'), 
                               ('5', 'Token is invalid!'), 
                               ('6', 'Mail server error, try later!'), 
                               ('7', 'Login already exist!'), 
                               ('8', 'Email already exist!'), 
                               ('9', 'Wrong login or password!'), 
                               ('10', 'Email not found!'), 
                               ('11', 'Login too short!'), 
                               ('12', 'Wrong login format!'), 
                               ('13', 'Wrong old password!')");
?>