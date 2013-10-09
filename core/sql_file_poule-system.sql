--
-- poule_countries
--

CREATE TABLE `poule_countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

--
-- poule_matches
--

CREATE TABLE `poule_matches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `country_1` int(11) NOT NULL,
  `country_2` int(11) NOT NULL,
  `start_time` int(11) NOT NULL,
  `score` varchar(255) NOT NULL DEFAULT 'a:4:{s:7:"score_1";s:0:"";s:7:"score_2";s:0:"";s:9:"penalty_1";s:0:"";s:9:"penalty_2";s:0:"";}',
  PRIMARY KEY (`id`,`group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

--
-- `poule_matches_groups`
--

CREATE TABLE `poule_matches_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `phase` int(11) NOT NULL,
  `group_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`,`phase`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

--
-- `poule_phases`
--

CREATE TABLE `poule_phases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `count` int(11) NOT NULL,
  `match_group` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7;

--
-- `poule_score`
--

CREATE TABLE `poule_score` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `phase` int(11) NOT NULL,
  `score` text NOT NULL,
  PRIMARY KEY (`user_id`,`phase`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;