DROP TABLE IF EXISTS `isns_admin`;

CREATE TABLE `isns_admin` (
  `admin_id` smallint(5) unsigned NOT NULL auto_increment,
  `admin_name` varchar(20) NOT NULL,
  `admin_password` char(32) NOT NULL,
  `admin_group` varchar(20) default NULL,
  `is_pass` tinyint(2) unsigned default '1',
  `active_time` datetime default NULL,
  PRIMARY KEY  (`admin_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `isns_tag`;

CREATE TABLE `isns_tag` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `name` varchar(20) NOT NULL,
  `count` mediumint(8) default 0,
  `hot` tinyint(2) default 0,
  PRIMARY KEY  (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `isns_tag_relation`;

CREATE TABLE `isns_tag_relation` (
  `id` mediumint(8) unsigned NOT NULL,
  `mod_id` mediumint(8) NOT NULL,
  `content_id` mediumint(8) NOT NULL,
  KEY `id` (`id`),
  KEY `mod_id` (`mod_id`),
  KEY `content_id` (`content_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/* Table structure for table `isns_album` */

DROP TABLE IF EXISTS `isns_album`;

CREATE TABLE `isns_album` (
  `album_id` mediumint(8) unsigned NOT NULL auto_increment,
  `album_name` varchar(20) default NULL,
  `user_id` mediumint(8) unsigned NOT NULL,
  `user_name` varchar(20) default NULL,
  `album_info` varchar(150) default NULL,
  `add_time` datetime default NULL,
  `update_time` datetime default NULL,
  `album_skin` varchar(150) default NULL,
  `photo_num` SMALLINT(5) unsigned default '0',
  `is_pass` tinyint(2) default '1',
  `privacy` varchar(200) DEFAULT NULL,
  `comments` int(5) unsigned NOT NULL default '0',
  `tag` varchar(80) default NULL,
  PRIMARY KEY  (`album_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `isns_album_comment` */

DROP TABLE IF EXISTS `isns_album_comment`;

CREATE TABLE `isns_album_comment` (
  `comment_id` mediumint(8) unsigned NOT NULL auto_increment,
  `visitor_ico` varchar(150) default NULL,
  `is_hidden` tinyint(2) NOT NULL default '0',
  `album_id` mediumint(8) unsigned NOT NULL,
  `visitor_id` mediumint(8) unsigned NOT NULL,
  `host_id` mediumint(8) unsigned NOT NULL,
  `visitor_name` varchar(20) default NULL,
  `content` text,
  `add_time` datetime default NULL,
  `readed` tinyint(2) default '0',
  PRIMARY KEY  (`comment_id`),
  KEY `album_id` (`album_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `isns_blog` */

DROP TABLE IF EXISTS `isns_blog`;

CREATE TABLE `isns_blog` (
  `log_id` mediumint(8) unsigned NOT NULL auto_increment,
  `user_id` mediumint(8) unsigned default NULL,
  `user_name` varchar(20) default NULL,
  `user_ico` varchar(150) default NULL,
  `log_title` varchar(80) default NULL,
  `log_sort` mediumint(8) NOT NULL default '0',
  `is_pass` tinyint(2) NOT NULL default '1',
  `log_sort_name` varchar(30) default NULL,
  `log_content` text,
  `add_time` datetime default NULL,
  `edit_time` datetime default NULL,
  `privacy` varchar(200) DEFAULT NULL,
  `hits` mediumint(8) default '0',
  `comments` int(5) unsigned NOT NULL default '0',
  `tag` varchar(80) default NULL,
  PRIMARY KEY  (`log_id`),
  KEY `user_id` (`user_id`),
  KEY `log_sort` (`log_sort`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `isns_blog_comment` */

DROP TABLE IF EXISTS `isns_blog_comment`;

CREATE TABLE `isns_blog_comment` (
  `comment_id` mediumint(8) unsigned NOT NULL auto_increment,
  `visitor_ico` varchar(150) default NULL,
  `is_hidden` tinyint(2) NOT NULL default '0',
  `visitor_id` mediumint(8) unsigned NOT NULL,
  `log_id` mediumint(8) unsigned NOT NULL,
  `host_id` mediumint(8) unsigned NOT NULL,
  `visitor_name` varchar(20) default NULL,
  `content` text,
  `add_time` datetime default NULL,
  `readed` tinyint(2) default '0',
  PRIMARY KEY  (`comment_id`),
  KEY `log_id` (`log_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `isns_blog_sort` */

DROP TABLE IF EXISTS `isns_blog_sort`;

CREATE TABLE `isns_blog_sort` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `name` varchar(20) default NULL,
  `user_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `isns_group_members` */

DROP TABLE IF EXISTS `isns_group_members`;

CREATE TABLE `isns_group_members` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `group_id` mediumint(8) unsigned default NULL,
  `user_id` mediumint(8) unsigned NOT NULL,
  `user_name` varchar(20) default NULL,
  `user_sex` tinyint(2) default NULL,
  `user_ico` varchar(150) default NULL,
  `state` tinyint(2) default NULL,
  `role` tinyint(2) default '2',
  `add_time` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `group_id` (`group_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `isns_group_subject` */

DROP TABLE IF EXISTS `isns_group_subject`;

CREATE TABLE `isns_group_subject` (
  `subject_id` mediumint(8) unsigned NOT NULL auto_increment,
  `group_id` mediumint(8) unsigned NOT NULL,
  `user_id` mediumint(8) unsigned NOT NULL,
  `user_name` varchar(20) default NULL,
  `user_ico` varchar(150) default NULL,
  `title` varchar(50) default NULL,
  `content` text,
  `add_time` datetime default NULL,
  `hits` int(5) unsigned default '0',
  `comments` int(5) unsigned default '0',
  `tag` varchar(80) default NULL,
  PRIMARY KEY  (`subject_id`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `isns_group_subject_comment` */

DROP TABLE IF EXISTS `isns_group_subject_comment`;

CREATE TABLE `isns_group_subject_comment` (
  `comment_id` mediumint(8) unsigned NOT NULL auto_increment,
  `visitor_ico` varchar(150) default NULL,
  `is_hidden` tinyint(2) default '0',
  `group_id` mediumint(8) unsigned NOT NULL,
  `host_id` mediumint(8) unsigned NOT NULL,
  `subject_id` mediumint(8) unsigned NOT NULL,
  `visitor_id` mediumint(8) unsigned NOT NULL,
  `visitor_name` varchar(20) default NULL,
  `content` text,
  `add_time` datetime default NULL,
  `readed` tinyint(2) default '0',
  PRIMARY KEY  (`comment_id`),
  KEY `subject_id` (`subject_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `isns_group_type` */

DROP TABLE IF EXISTS `isns_group_type`;

CREATE TABLE `isns_group_type` (
  `id` mediumint(8) NOT NULL auto_increment,
  `order_num` mediumint(8) unsigned default NULL,
  `name` varchar(20) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `isns_groups` */

DROP TABLE IF EXISTS `isns_groups`;

CREATE TABLE `isns_groups` (
  `group_id` mediumint(8) unsigned NOT NULL auto_increment,
  `add_userid` mediumint(8) unsigned NOT NULL,
  `is_pass` tinyint(2) NOT NULL default '1',
  `member_count` mediumint(8) unsigned default '1',
  `group_name` varchar(50) default NULL,
  `group_resume` varchar(100) default NULL,
  `group_time` datetime default NULL,
  `group_manager_name` varchar(60) default NULL,
  `group_manager_id` varchar(20) default NULL,
  `group_req_id` text default NULL,
  `group_creat_name` varchar(20) default NULL,
  `group_logo` varchar(150) default NULL,
  `group_join_type` tinyint(2) NOT NULL default '0',
  `group_type` varchar(20) default NULL,
  `group_type_id` SMALLINT(5) unsigned default NULL,
  `affiche` varchar(200) default NULL,
  `tag` varchar(80) default NULL,
  `subjects_num` mediumint(8) unsigned default '0',
  `comments` mediumint(8) unsigned default '0',
  PRIMARY KEY  (`group_id`),
  KEY `add_userid` (`add_userid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `isns_guest` */

DROP TABLE IF EXISTS `isns_guest`;

CREATE TABLE `isns_guest` (
  `guest_id` mediumint(8) unsigned NOT NULL auto_increment,
  `guest_user_id` mediumint(8) unsigned NOT NULL,
  `guest_user_name` varchar(20) default NULL,
  `guest_user_ico` varchar(150) default NULL,
  `user_id` mediumint(8) unsigned NOT NULL,
  `add_time` datetime default NULL,
  PRIMARY KEY  (`guest_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `isns_hi` */

DROP TABLE IF EXISTS `isns_hi`;

CREATE TABLE `isns_hi` (
  `hi_id` mediumint(8) unsigned NOT NULL auto_increment,
  `from_user_id` mediumint(8) unsigned NOT NULL,
  `from_user_name` varchar(20) default NULL,
  `from_user_ico` varchar(150) default NULL,
  `hi` tinyint(2) default NULL,
  `to_user_id` mediumint(8) unsigned NOT NULL,
  `add_time` datetime default NULL,
  `readed` tinyint(2) default '0',
  PRIMARY KEY  (`hi_id`),
  KEY `to_user_id` (`to_user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `isns_integral` */

DROP TABLE IF EXISTS `isns_integral`;

CREATE TABLE `isns_integral` (
  `operation` varchar(20) default NULL,
  `integral` SMALLINT(5) default '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `isns_integral` */

insert into `isns_integral`(`operation`,`integral`) values ('blog',2),('photo',1),('com_sub',1),('subject',2),('com_msg',1),('login',10),('invited',20),('one_ico',40),('del_blog',-2),('del_photo',-1),('del_subject',-2),('del_com_msg',-1),('del_com_sub',-1),('convert',100),('upgrade',5),('poll',2),('del_poll',-2),('share',2),('del_share',-2);

/*Table structure for table `isns_mood` */

DROP TABLE IF EXISTS `isns_mood`;

CREATE TABLE `isns_mood` (
  `mood_id` mediumint(8) unsigned NOT NULL auto_increment,
  `user_id` mediumint(8) unsigned NOT NULL,
  `user_name` varchar(20) NOT NULL,
  `user_ico` varchar(150) NOT NULL,
  `mood` text,
  `comments` int(5) unsigned default '0',
  `add_time` datetime default NULL,
  PRIMARY KEY  (`mood_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `isns_mood_comment` */

DROP TABLE IF EXISTS `isns_mood_comment`;

CREATE TABLE `isns_mood_comment` (
  `comment_id` mediumint(8) unsigned NOT NULL auto_increment,
  `mood_id` mediumint(8) unsigned NOT NULL,
  `host_id` mediumint(8) unsigned NOT NULL,
  `visitor_id` mediumint(8) unsigned NOT NULL,
  `visitor_name` varchar(20) default NULL,
  `content` text,
  `add_time` datetime default NULL,
  `visitor_ico` varchar(150) default NULL,
  `is_hidden` tinyint(2) default '0',
  PRIMARY KEY  (`comment_id`),
  KEY `mood_id` (`mood_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `isns_msg_inbox` */

DROP TABLE IF EXISTS `isns_msg_inbox`;

CREATE TABLE `isns_msg_inbox` (
  `mess_id` mediumint(8) unsigned NOT NULL auto_increment,
  `mess_title` varchar(70) default NULL,
  `mess_content` varchar(500) default NULL,
  `from_user_id` mediumint(8) unsigned NOT NULL,
  `from_user` varchar(20) default NULL,
  `from_user_ico` varchar(150) default NULL,
  `user_id` mediumint(8) unsigned NOT NULL,
  `add_time` datetime default NULL,
  `mesinit_id` mediumint(8) unsigned default NULL,
  `readed` tinyint(2) default '0',
  PRIMARY KEY  (`mess_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `isns_msg_outbox` */

DROP TABLE IF EXISTS `isns_msg_outbox`;

CREATE TABLE `isns_msg_outbox` (
  `mess_id` mediumint(8) unsigned NOT NULL auto_increment,
  `mess_title` varchar(70) default NULL,
  `mess_content` varchar(500) default NULL,
  `to_user_id` mediumint(8) unsigned NOT NULL,
  `to_user` varchar(20) default NULL,
  `to_user_ico` varchar(150) default NULL,
  `user_id` mediumint(8) unsigned NOT NULL,
  `state` tinyint(2) default '0',
  `add_time` datetime default NULL,
  PRIMARY KEY  (`mess_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `isns_msgboard` */

DROP TABLE IF EXISTS `isns_msgboard`;

CREATE TABLE `isns_msgboard` (
  `mess_id` mediumint(8) unsigned NOT NULL auto_increment,
  `from_user_id` mediumint(8) unsigned NOT NULL,
  `from_user_name` varchar(20) default NULL,
  `from_user_ico` varchar(150) default NULL,
  `message` text,
  `to_user_id` mediumint(8) unsigned NOT NULL,
  `add_time` datetime default NULL,
  `readed` tinyint(2) default '0',
  PRIMARY KEY  (`mess_id`),
  KEY `to_user_id` (`to_user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `isns_online` */

DROP TABLE IF EXISTS `isns_online`;

CREATE TABLE `isns_online` (
  `online_id` mediumint(8) unsigned NOT NULL auto_increment,
  `user_id` mediumint(8) unsigned NOT NULL,
  `user_name` varchar(20) default NULL,
  `user_sex` tinyint(2) default NULL,
  `user_ico` varchar(150) default NULL,
  `birth_year` varchar(6) default NULL,
  `birth_province` varchar(30) default NULL,
  `birth_city` varchar(30) default NULL,
  `reside_province` varchar(30) default NULL,
  `reside_city` varchar(30) default NULL,
  `active_time` int(10) DEFAULT 0,
  `hidden` tinyint(2) default '0',
  `session_code` char(32) default NULL,
  PRIMARY KEY  (`online_id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `isns_pals_def_sort` */

DROP TABLE IF EXISTS `isns_pals_def_sort`;

CREATE TABLE `isns_pals_def_sort` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `order_num` smallint(5) default NULL,
  `name` varchar(20) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `isns_pals_mine` */

DROP TABLE IF EXISTS `isns_pals_mine`;

CREATE TABLE `isns_pals_mine` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `user_id` mediumint(8) unsigned NOT NULL,
  `pals_id` mediumint(8) unsigned NOT NULL,
  `pals_sort_id` smallint(5) default '0',
  `pals_sort_name` varchar(20) default NULL,
  `pals_name` varchar(20) default NULL,
  `pals_sex` tinyint(2) default NULL,
  `add_time` datetime default NULL,
  `pals_ico` varchar(150) default NULL,
  `accepted` tinyint(2) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`),
  KEY `pals_id` (`pals_id`),
  KEY `pals_sort_id` (`pals_sort_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `isns_pals_request` */

DROP TABLE IF EXISTS `isns_pals_request`;

CREATE TABLE `isns_pals_request` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `user_id` mediumint(8) unsigned NOT NULL,
  `req_id` mediumint(8) unsigned NOT NULL,
  `req_name` varchar(20) default NULL,
  `req_sex` tinyint(2) default NULL,
  `add_time` datetime default NULL,
  `req_ico` varchar(150) default NULL,
  `from_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `isns_pals_sort` */

DROP TABLE IF EXISTS `isns_pals_sort`;

CREATE TABLE `isns_pals_sort` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `name` varchar(20) default NULL,
  `user_id` mediumint(8) unsigned default NULL,
  `count` mediumint(8) unsigned default '0',
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `isns_photo` */

DROP TABLE IF EXISTS `isns_photo`;

CREATE TABLE `isns_photo` (
  `photo_id` mediumint(8) unsigned NOT NULL auto_increment,
  `user_id` mediumint(8) unsigned NOT NULL,
  `user_name` varchar(20) default NULL,
  `photo_name` varchar(20) default NULL,
  `photo_information` text,
  `add_time` datetime default NULL,
  `photo_src` varchar(150) default NULL,
  `photo_thumb_src` varchar(150) default NULL,
  `album_id` mediumint(8) unsigned NOT NULL,
  `is_pass` tinyint(2) default '1',
  `privacy` varchar(200) DEFAULT NULL,
  `comments` int(5) unsigned unsigned NOT NULL default '0',
  `tag` varchar(80) default NULL,
  PRIMARY KEY  (`photo_id`),
  KEY `album_id` (`album_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `isns_photo_comment` */

DROP TABLE IF EXISTS `isns_photo_comment`;

CREATE TABLE `isns_photo_comment` (
  `comment_id` mediumint(8) unsigned NOT NULL auto_increment,
  `photo_id` mediumint(8) unsigned NOT NULL,
  `host_id` mediumint(8) unsigned NOT NULL,
  `visitor_id` mediumint(8) unsigned NOT NULL,
  `visitor_name` varchar(20) default NULL,
  `content` text,
  `add_time` datetime default NULL,
  `readed` tinyint(2) default '0',
  `visitor_ico` varchar(150) default NULL,
  `is_hidden` tinyint(2) NOT NULL default '0',
  PRIMARY KEY  (`comment_id`),
  KEY `photo_id` (`photo_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `isns_poll` */

DROP TABLE IF EXISTS `isns_poll`;

CREATE TABLE `isns_poll` (
  `p_id` mediumint(8) unsigned NOT NULL auto_increment,
  `user_id` mediumint(8) unsigned default '0',
  `username` varchar(20) default NULL,
  `user_ico` varchar(150) default NULL,
  `subject` varchar(80) default '',
  `voternum` mediumint(8) unsigned default '0',
  `comments` int(5) unsigned default '0',
  `multiple` tinyint(2) default '0',
  `maxchoice` tinyint(3) default '0',
  `sex` tinyint(2) default '0',
  `noreply` tinyint(2) default '0',
  `credit` smallint(5) unsigned default '0',
  `percredit` smallint(5) unsigned default '0',
  `expiration` date default NULL,
  `lastvote` datetime default NULL,
  `dateline` datetime default NULL,
  `message` text,
  `summary` text,
  `option` text,
  `is_pass` tinyint(2) default '1',
  `tag` varchar(80) default NULL,
  PRIMARY KEY  (`p_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `isns_poll_comment` */

DROP TABLE IF EXISTS `isns_poll_comment`;

CREATE TABLE `isns_poll_comment` (
  `comment_id` mediumint(8) unsigned NOT NULL auto_increment,
  `visitor_id` mediumint(8) unsigned default NULL,
  `visitor_name` varchar(20) default NULL,
  `p_id` mediumint(8) unsigned default NULL,
  `host_id` mediumint(8) unsigned default NULL,
  `add_time` datetime default NULL,
  `content` text,
  `visitor_ico` varchar(150) default NULL,
  `is_hidden` tinyint(2) NOT NULL default '0',
  PRIMARY KEY  (`comment_id`),
  KEY `p_id` (`p_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `isns_polloption` */

DROP TABLE IF EXISTS `isns_plugin_url`;

CREATE TABLE `isns_plugin_url` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) DEFAULT NULL,
  `layout_id` varchar(20) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `sequence` smallint(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `isns_polloption`;

CREATE TABLE `isns_polloption` (
  `oid` mediumint(8) unsigned NOT NULL auto_increment,
  `pid` mediumint(8) unsigned NOT NULL default '0',
  `votenum` mediumint(8) unsigned NOT NULL default '0',
  `option` varchar(100) default NULL,
  PRIMARY KEY  (`oid`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `isns_polluser` */

DROP TABLE IF EXISTS `isns_polluser`;

CREATE TABLE `isns_polluser` (
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `username` varchar(20) default '',
  `pid` mediumint(8) unsigned default '0',
  `option` text,
  `dateline` datetime default NULL,
  `anony` tinyint(2) NOT NULL default '0',
  PRIMARY KEY  (`uid`),
  KEY `pid` (`pid`,`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `isns_recent_affair` */

DROP TABLE IF EXISTS `isns_recent_affair`;

CREATE TABLE `isns_recent_affair` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `type_id` tinyint(2) default 0,
  `title` varchar(500) default NULL,
  `content` text,
  `user_id` mediumint(8) unsigned NOT NULL,
  `user_name` varchar(20) default NULL,
  `user_ico` varchar(150) default NULL,
  `date_time` datetime default NULL,
  `update_time` datetime default NULL,
  `for_content_id` mediumint(8) default 0,
  `mod_type` tinyint(2) default 0,
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`),
  KEY `type_id` (`type_id`),
  KEY `mod_type` (`mod_type`,`for_content_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `isns_recommend` */

DROP TABLE IF EXISTS `isns_recommend`;

CREATE TABLE `isns_recommend` (
  `recommend_id` mediumint(8) unsigned NOT NULL auto_increment,
  `user_id` mediumint(8) unsigned default NULL,
  `user_name` varchar(20) default NULL,
  `user_ico` varchar(150) default NULL,
  `is_pass` tinyint(2) default '1',
  `guest_num` mediumint(8) default '0',
  `user_sex` tinyint(2) default '0',
  `rec_class` tinyint(2) default '0',
  `rec_order` tinyint(2) default '0',
  `show_ico` varchar(150) default NULL,
  PRIMARY KEY  (`recommend_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `isns_report` */

DROP TABLE IF EXISTS `isns_report`;

CREATE TABLE `isns_report` (
  `report_id` mediumint(8) unsigned NOT NULL auto_increment,
  `user_id` mediumint(8) unsigned default NULL,
  `reason` varchar(150) default NULL,
  `user_name` varchar(20) default NULL,
  `type` varchar(20) default NULL,
  `content` text,
  `add_time` datetime default NULL,
  `reported_id` mediumint(8) unsigned default NULL,
  `userd_id` mediumint(8) unsigned default NULL,
  `rep_num` mediumint(8) unsigned default '1',
  PRIMARY KEY  (`report_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `isns_share` */

DROP TABLE IF EXISTS `isns_share`;

CREATE TABLE `isns_share` (
  `s_id` mediumint(8) NOT NULL auto_increment,
  `type_id` tinyint(2) default NULL,
  `user_id` mediumint(8) unsigned default NULL,
  `user_name` varchar(20) default NULL,
  `user_ico` varchar(150) default NULL,
  `content` text,
  `s_title` varchar(300) default NULL,
  `out_link` varchar(255) default NULL,
  `add_time` datetime default NULL,
  `for_content_id` mediumint(8) unsigned default NULL,
  `comments` int(5) unsigned default '0',
  `movie_thumb` varchar(255) default NULL,
  `movie_link` varchar(255) default NULL,
  `is_pass` tinyint(2) default '1',
  `tag` varchar(80) default NULL,
  PRIMARY KEY  (`s_id`),
  KEY `user_id` (`user_id`),
  KEY `type_id` (`type_id`,`for_content_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `isns_share_comment` */

DROP TABLE IF EXISTS `isns_share_comment`;

CREATE TABLE `isns_share_comment` (
  `comment_id` mediumint(8) unsigned NOT NULL auto_increment,
  `visitor_id` mediumint(8) unsigned default NULL,
  `visitor_name` varchar(20) default NULL,
  `s_id` mediumint(8) unsigned default NULL,
  `host_id` mediumint(8) unsigned default NULL,
  `add_time` datetime default NULL,
  `content` text,
  `visitor_ico` varchar(150) default NULL,
  `is_hidden` tinyint(2) NOT NULL default '0',
  PRIMARY KEY  (`comment_id`),
  KEY `s_id` (`s_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `isns_tmp_file` */

DROP TABLE IF EXISTS `isns_tmp_file`;

CREATE TABLE `isns_tmp_file` (
  `mod_id` mediumint(8) unsigned NOT NULL,
  `mod_count` mediumint(8) unsigned default '0',
  `affair_array` text,
  `data_array` text,
  PRIMARY KEY  (`mod_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


/*Table structure for table `isns_uploadfile` */

DROP TABLE IF EXISTS `isns_uploadfile`;

CREATE TABLE `isns_uploadfile` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `user_id` mediumint(8) unsigned NOT NULL,
  `add_time` datetime default NULL,
  `file_src` varchar(150) default NULL,
  `file_name` varchar(80) default NULL,
  PRIMARY KEY  (`id`),
  KEY `userid` (`user_id`)

) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `isns_plugins`;

CREATE TABLE `isns_plugins` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `valid` tinyint(2) DEFAULT '0',
  `autoorder` tinyint(2) DEFAULT '0',
  `reg_date` datetime NOT NULL,
  `image` varchar(150) NOT NULL,
  `comment_num` mediumint(8) unsigned DEFAULT '0',
  `use_num` mediumint(8) unsigned DEFAULT '0',
  `info` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `isns_backgroup`;

CREATE TABLE `isns_backgroup` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `gid` varchar(20) DEFAULT NULL,
  `name` varchar(40) DEFAULT NULL,
  `rights` text,
  `pluginrights` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `isns_frontgroup`;

CREATE TABLE `isns_frontgroup` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `gid` varchar(20) DEFAULT NULL,
  `name` varchar(40) DEFAULT NULL,
  `rights` text,
  `pluginrights` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*Table structure for table `isns_users` */

DROP TABLE IF EXISTS `isns_users`;

CREATE TABLE `isns_users` (
  `user_id` mediumint(8) unsigned NOT NULL auto_increment,
  `user_marry` tinyint(2) default NULL,
  `user_email` varchar(50) default NULL,
  `user_name` varchar(20) default NULL,
  `user_pws` char(32) default NULL,
  `user_sex` tinyint(2) default NULL,
  `user_call` varchar(20) default NULL,
  `birth_province` varchar(30) default NULL,
  `birth_city` varchar(30) default NULL,
  `reside_province` varchar(30) default NULL,
  `reside_city` varchar(30) default NULL,
  `user_ico` varchar(150) default NULL,
  `is_pass` tinyint(2) default '1',
  `user_add_time` datetime default NULL,
  `birth_year` char(6) default NULL,
  `birth_month` char(4) default NULL,
  `birth_day` char(4) default NULL,
  `user_blood` char(2) default NULL,
  `user_qq` varchar(15) default NULL,
  `creat_group` varchar(150) default NULL,
  `join_group` varchar(150) default NULL,
  `guest_num` mediumint(8) unsigned default '0',
  `integral` mediumint(8) default '10',
  `access_limit` tinyint(2) default '0',
  `access_questions` varchar(100) default NULL,
  `access_answers` varchar(100) default NULL,
  `inputmess_limit` tinyint(2) default '0',
  `palsreq_limit` tinyint(2) default '0',
  `lastlogin_datetime` datetime default NULL,
  `invite_from_uid` mediumint(8) unsigned default NULL,
  `hidden_pals_id` text,
  `hidden_type_id` text,
  `login_ip` char(15) default NULL,
  `is_recommend` tinyint(2) NOT NULL default '0',
  `dressup` varchar( 20 ) default 0,
  `use_plugins` varchar( 1000 ) default NULL,
  `use_apps` varchar( 1000 ) default NULL,
  `user_group` varchar( 30 ) default 'base',
  `forget_pass` varchar(50) default NULL,
  PRIMARY KEY  (`user_id`),
  UNIQUE KEY `user_email` (`user_email`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `isns_remind`;

CREATE TABLE `isns_remind` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL,
  `type_id` tinyint(2) NOT NULL,
  `date` datetime NOT NULL,
  `content` varchar(200) DEFAULT NULL,
  `is_focus` tinyint(2) NOT NULL,
  `from_uid` mediumint(8) unsigned NOT NULL,
  `from_uname` varchar(20) NOT NULL,
  `from_uico` varchar(150) NOT NULL,
  `link` varchar(150) NOT NULL,
  `count` mediumint(8) unsigned DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`is_focus`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `isns_invite_code`;

CREATE TABLE `isns_invite_code` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `sendor_id` mediumint(8) NOT NULL,
  `code_txt` varchar(20) NOT NULL,
  `is_admin` tinyint(2) NOT NULL DEFAULT '0',
  `add_time` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code_txt` (`code_txt`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `isns_group_type` (`id`, `order_num`, `name`) VALUES
(1, 1, '时尚生活'),
(2, 2, '影视天地'),
(3, 3, '极品音乐'),
(4, 4, '旅游天下'),
(5, 5, '运动休闲'),
(6, 6, '校园联盟'),
(7, 7, '网络互联'),
(8, 8, '至爱宠物'),
(9, 9, '明星粉丝'),
(10, 10, '文学艺术'),
(11, 11, '同城同乡'),
(12, 12, '两性情感'),
(13, 13, '游戏动漫'),
(14, 14, '投资理财'),
(15, 15, '其他');

INSERT INTO `isns_pals_def_sort` (`id`, `order_num`, `name`) VALUES
(1, 1, '亲朋'),
(2, 2, '好友'),
(3, 3, '同学');