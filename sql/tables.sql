--  FACEBOOK - LIKE APP --

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
	`id`			INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
	`first_name`	VARCHAR(100),
	`last_name`		VARCHAR(100),
	`password`		VARCHAR(100),
	`email`			VARCHAR(100) UNIQUE,
	`telephone`		VARCHAR(11),
	`deleted`		BOOLEAN DEFAULT 0
);

DROP TABLE IF EXISTS `post`;
CREATE TABLE `post` (
	`id`			INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
	`message`		VARCHAR(500),
	`time`			DATETIME DEFAULT NOW(),
	`user_posted`	INT UNSIGNED,
	`parent`		INT UNSIGNED, -- if it is a comment on a post then it will have a parent if not this will be a 0
	`post_to`		INT UNSIGNED, -- basically if someone poasted on someone elses wall
	`content`		INT UNSIGNED DEFAULT 0,
	`deleted`		BOOLEAN DEFAULT 0
);


DROP TABLE IF EXISTS `friend_request`;
CREATE TABLE `friend_request` (
	`id`		INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
	`from_user`	INT UNSIGNED,
	`to_user`	INT UNSIGNED,
	`deleted`	BOOLEAN DEFAULT 0
);

DROP TABLE IF EXISTS `friend_map`;
CREATE TABLE `friend_map` (
	`id`			INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
	`user` 			INT,
	`friend` 		INT,
	`deleted`		BOOLEAN DEFAULT 0
);

DROP TABLE IF EXISTS `post_like_map`; -- Post is liked/disliked
CREATE TABLE `post_like_map` (
	`id` 			INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
	`post_id`		INT UNSIGNED,
	`user_id`		INT UNSIGNED,
	`like_status`	INT UNSIGNED,
	`deleted`		BOOLEAN DEFAULT 0
);

DROP TABLE IF EXISTS `user_messages`;
CREATE TABLE `user_messages` (
	`id` 			INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
	`from_user` 	INT,
	`to_user` 		INT,
	`message` 		VARCHAR(100),
	`deleted`		BOOLEAN DEFAULT 0
);

DROP TABLE IF EXISTS `content`;
CREATE TABLE `content` (
	`id`			INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
	`type`			VARCHAR(3),
	`user_posted`	INT,
	`location`		VARCHAR(100),
	`deleted`		BOOLEAN DEFAULT 0
);
