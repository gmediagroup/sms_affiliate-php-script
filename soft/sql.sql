CREATE TABLE `config` (
`admin_login` text NOT NULL,
`admin_pass` text NOT NULL,
`proc` float NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO `config` VALUES ('admin', 'c4ca4238a0b923820dcc509a6f75849', 70);


CREATE TABLE `d_posts` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL default '0',
  `topic_id` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `content` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=cp1251 AUTO_INCREMENT=22 ;


CREATE TABLE `d_topics` (
  `id` int(11) NOT NULL auto_increment,
  `title` text NOT NULL,
  `date` date NOT NULL default '0000-00-00',
  `status` enum('open','close') NOT NULL default 'open',
  `user_id` int(11) NOT NULL default '0',
  `md5sum` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=cp1251 AUTO_INCREMENT=8 ;


create table `sms_tarifs` (`id` int(11) NOT NULL auto_increment,`country` text NOT NULL,`operator` text NOT NULL,`number` text NOT NULL,`message` text NOT NULL,`price` text NOT NULL,`currency` text NOT NULL,PRIMARY KEY  (`id`)) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1;

create table `passlog` (
`id` int(11) not null auto_increment,
`date` date not null default '0000-00-00',
`cost_rur` int(11) not null,
`pass` text not null,
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;;

create table `goodpass` (
`id` int(11) not null auto_increment,
`sum` int(11) not null,
`pass` text not null,
`refer` text not null,
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;;





CREATE TABLE `links` (
  `id` int(11) NOT NULL auto_increment,
  `link` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=cp1251 AUTO_INCREMENT=7 ;


INSERT INTO `links` VALUES (6, 'http://platnik.com/?id=');



CREATE TABLE `logs` (
  `id` int(11) NOT NULL auto_increment,
  `date` date NOT NULL default '0000-00-00',
  `sum` float NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=130 DEFAULT CHARSET=cp1251 AUTO_INCREMENT=130 ;


create table `traf` (
  `id` int(11) NOT NULL auto_increment,
  `date` date NOT NULL default '0000-00-00',
  `count` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1;


INSERT INTO `traf` VALUES ('', '2009-01-27', 415, 1);
INSERT INTO `traf` VALUES ('', '2009-01-28', 1835, 1);
INSERT INTO `traf` VALUES ('', '2009-01-29', 509, 1);
INSERT INTO `traf` VALUES ('', '2009-01-30', 762, 1);
INSERT INTO `traf` VALUES ('', '2009-01-31', 4201, 1);

INSERT INTO `logs` VALUES (1, '2009-01-27', 3.15, 1);
INSERT INTO `logs` VALUES (2, '2009-01-27', 3.15, 1);
INSERT INTO `logs` VALUES (3, '2009-01-27', 3.5, 1);
INSERT INTO `logs` VALUES (4, '2009-01-27', 3.15, 1);
INSERT INTO `logs` VALUES (5, '2009-01-27', 3.85, 1);
INSERT INTO `logs` VALUES (6, '2009-01-28', 3.85, 1);
INSERT INTO `logs` VALUES (7, '2009-01-28', 3.15, 1);
INSERT INTO `logs` VALUES (8, '2009-01-28', 3.5, 1);
INSERT INTO `logs` VALUES (9, '2009-01-28', 3.15, 1);
INSERT INTO `logs` VALUES (10, '2009-01-28', 3.5, 1);
INSERT INTO `logs` VALUES (11, '2009-01-29', 3.15, 1);
INSERT INTO `logs` VALUES (12, '2009-01-29', 3.85, 1);
INSERT INTO `logs` VALUES (13, '2009-01-30', 3.5, 1);
INSERT INTO `logs` VALUES (14, '2009-01-30', 3.5, 1);
INSERT INTO `logs` VALUES (15, '2009-01-31', 3.5, 1);
INSERT INTO `logs` VALUES (16, '2009-01-31', 3.85, 1);


-- --------------------------------------------------------

-- 
-- Структура таблицы `news`
-- 

CREATE TABLE `news` (
  `id` int(11) NOT NULL auto_increment,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `date` date NOT NULL default '0000-00-00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=242 DEFAULT CHARSET=cp1251 AUTO_INCREMENT=242 ;

-- 
-- Дамп данных таблицы `news`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `pay`
-- 

CREATE TABLE `pay` (
  `id` int(11) NOT NULL auto_increment,
  `sum` float NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `status` enum('checked','unchecked') NOT NULL default 'checked',
  `user_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=cp1251 AUTO_INCREMENT=21 ;

-- 
-- Дамп данных таблицы `pay`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `users`
-- 

CREATE TABLE `users` (
  `id` int(11) NOT NULL auto_increment,
  `login` text NOT NULL,
  `pass` text NOT NULL,
  `email` text NOT NULL,
  `icq` int(11) NOT NULL default '0',
  `rekv` text NOT NULL,
  `balans` float NOT NULL default '0',
  `status` enum('active','inactive') NOT NULL default 'active',
  `debug` enum('on','off') NOT NULL default 'on',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=cp1251 AUTO_INCREMENT=44 ;

-- 
-- Дамп данных таблицы `users`
-- 

INSERT INTO `users` VALUES (1, 'admin', 'admin', 'admin', 0, 'admin', 0, 'active', 'off');
