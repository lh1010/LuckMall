<?php
$array = [
	"CREATE TABLE IF NOT EXISTS `luck_city` (
	  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
	  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '城市名',
	  `parent_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
	  `level` tinyint(3) NOT NULL DEFAULT '1',
	  `sort` mediumint(8) NOT NULL DEFAULT '0' COMMENT '排序',
	  PRIMARY KEY (`id`),
	  KEY `upid` (`parent_id`,`sort`)
	) ENGINE=MyISAM AUTO_INCREMENT=45052 DEFAULT CHARSET=utf8 COMMENT='地区表';",
];
return $array;