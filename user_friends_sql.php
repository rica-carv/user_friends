# --------------------------------------------------------
#
# Table structure for table `euser_friends`
#
CREATE TABLE user_friends (
	friends_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  from_user int(5) NOT NULL default '0',
  to_user int(5) NOT NULL default '0',
  created int(10) NOT NULL default '0',
  status tinyint(1) NOT NULL default '1',
  PRIMARY KEY (`friends_id`),
  INDEX `user_id_friend_id`  (from_user, to_user)
);
