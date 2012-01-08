SET NAMES UTF8;

DROP table `comment`;
DROP TABLE `post`;
DROP TABLE `user`;

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,

  `username` VARCHAR(255) NOT NULL,
  `email` VARCHAR(511) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

INSERT INTO `user` (`username`, `email`, `password`) VALUES ('admin', 'boxfrommars@gmail.com', MD5('passw0rd'));

CREATE TABLE `post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,

  `title` tinytext,
  `content` text,

  `updated_at` TIMESTAMP NOT NULL ON UPDATE NOW() DEFAULT NOW(),
  `created_at` TIMESTAMP NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

INSERT INTO `post` (`id_user`, `title`, `content`, `created_at`) VALUES (
  (SELECT `id` FROM `user` WHERE `username` = 'admin' ),
  'first article',
  '<p>Думал ли я, что однажды смогу вот так, положа руку на сердце, рассказать всю мою жизнь? Кому-то, кто внимательно меня выслушает? Я счастлив в моем положении, которому, наверно, мало кто позавидует, ибо оно дает мне эту возможность, столь редкую в нашей жизни. Это неземное блаженство, это полет души, когда нет иных забот и занятий, кроме одного: говорить правду, свободно и не стесняясь, всю правду как она есть.</p>
  <p>Случай мой диковинный.</p>',
  NULL
);


CREATE TABLE `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_post` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  
  `name` tinytext,
  `email` tinytext,
  `content` text,

  `updated_at` TIMESTAMP NOT NULL ON UPDATE NOW() DEFAULT NOW(),
  `created_at` TIMESTAMP NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;