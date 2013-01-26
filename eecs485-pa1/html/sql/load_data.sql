DELETE FROM User;

INSERT INTO User
(username, password, firstname, lastname, email)
values
('sportslover',MD5('sportslover'), 'Paul', 'Walker', 'sportslover@hotmail.com'),
('traveler',MD5('traveler'), 'Rebecca', 'Travolta', 'rebt@explorer.org'),
('spacejunkie',MD5('spacejunkie'), 'Bob', 'Spacey', 'bspace@spacejunkies.net');

DELETE FROM Album;

INSERT INTO Album
(albumid, title, created, lastupdated, access, username)
values
(1, 'I love sports', NOW(), NOW(), 'public', 'sportslover'),
(2, 'I love football', NOW(), NOW(), 'public', 'sportslover'),
(3, 'Around The World', NOW(), NOW(), 'public', 'traveler'),
(4, 'Cool Space Shots', NOW(), NOW(), 'private', 'spacejunkie');

DELETE FROM AlbumAccess;

INSERT INTO AlbumAccess
(albumid, username)
values
(1, 'sportslover'),
(2, 'sportslover'),
(3, 'traveler'),
(4, 'spacejunkie');

DELETE FROM Contain;

INSERT INTO Contain
(albumid, url, caption, sequencenum)
values
(1, 'static/images/sports_s1.jpg', 'init_caption', 1),
(1, 'static/images/sports_s2.jpg', 'init_caption', 2),
(1, 'static/images/sports_s3.jpg', 'init_caption', 3),
(1, 'static/images/sports_s4.jpg', 'init_caption', 4),
(1, 'static/images/sports_s5.jpg', 'init_caption', 5),
(1, 'static/images/sports_s6.jpg', 'init_caption', 6),
(1, 'static/images/sports_s7.jpg', 'init_caption', 7),
(1, 'static/images/sports_s8.jpg', 'init_caption', 8),
(2, 'static/images/football_s1.jpg', 'init_caption', 1),
(2, 'static/images/football_s2.jpg', 'init_caption', 2),
(2, 'static/images/football_s3.jpg', 'init_caption', 3),
(2, 'static/images/football_s4.jpg', 'init_caption', 4),
(3, 'static/images/world_EiffelTower.jpg', 'init_caption', 1),
(3, 'static/images/world_GreatWall.jpg', 'init_caption', 2),
(3, 'static/images/world_Isfahan.jpg', 'init_caption', 3),
(3, 'static/images/world_Istanbul.jpg', 'init_caption', 4),
(3, 'static/images/world_Persepolis.jpg', 'init_caption', 5),
(3, 'static/images/world_Reykjavik.jpg', 'init_caption', 6),
(3, 'static/images/world_Seoul.jpg', 'init_caption', 7),
(3, 'static/images/world_Stonehenge.jpg', 'init_caption', 8),
(3, 'static/images/world_TajMahal.jpg', 'init_caption', 9),
(3, 'static/images/world_TelAviv.jpg', 'init_caption', 10),
(3, 'static/images/world_WashingtonDC.jpg', 'init_caption', 11),
(3, 'static/images/world_firenze.jpg', 'init_caption', 12),
(3, 'static/images/world_tokyo.jpg', 'init_caption', 13),
(4, 'static/images/space_EagleNebula.jpg', 'init_caption', 1),
(4, 'static/images/space_GalaxyCollision.jpg', 'init_caption', 2),
(4, 'static/images/space_HelixNebula.jpg', 'init_caption', 3),
(4, 'static/images/space_MilkyWay.jpg', 'init_caption', 4),
(4, 'static/images/space_OrionNebula.jpg', 'init_caption', 5);

DELETE FROM Photo;

INSERT INTO Photo
(url, format, date)
values
('static/images/sports_s1.jpg', 'jpg', NOW()),
('static/images/sports_s2.jpg', 'jpg', NOW()),
('static/images/sports_s3.jpg', 'jpg', NOW()),
('static/images/sports_s4.jpg', 'jpg', NOW()),
('static/images/sports_s5.jpg', 'jpg', NOW()),
('static/images/sports_s6.jpg', 'jpg', NOW()),
('static/images/sports_s7.jpg', 'jpg', NOW()),
('static/images/sports_s8.jpg', 'jpg', NOW()),
('static/images/football_s1.jpg', 'jpg', NOW()),
('static/images/football_s2.jpg', 'jpg', NOW()),
('static/images/football_s3.jpg', 'jpg', NOW()),
('static/images/football_s4.jpg', 'jpg', NOW()),
('static/images/world_EiffelTower.jpg', 'jpg', NOW()),
('static/images/world_GreatWall.jpg', 'jpg', NOW()),
('static/images/world_Isfahan.jpg', 'jpg', NOW()),
('static/images/world_Istanbul.jpg', 'jpg', NOW()),
('static/images/world_Persepolis.jpg', 'jpg', NOW()),
('static/images/world_Reykjavik.jpg', 'jpg', NOW()),
('static/images/world_Seoul.jpg', 'jpg', NOW()),
('static/images/world_Stonehenge.jpg', 'jpg', NOW()),
('static/images/world_TajMahal.jpg', 'jpg', NOW()),
('static/images/world_TelAviv.jpg', 'jpg', NOW()),
('static/images/world_WashingtonDC.jpg', 'jpg', NOW()),
('static/images/world_firenze.jpg', 'jpg', NOW()),
('static/images/world_tokyo.jpg', 'jpg', NOW()),
('static/images/space_EagleNebula.jpg', 'jpg', NOW()),
('static/images/space_GalaxyCollision.jpg', 'jpg', NOW()),
('static/images/space_HelixNebula.jpg', 'jpg', NOW()),
('static/images/space_MilkyWay.jpg', 'jpg', NOW()),
('static/images/space_OrionNebula.jpg', 'jpg', NOW());

INSERT INTO Comment
(url, commentseqnum, comments)
values
('static/images/sports_s1.jpg', 1, "This is awesome!"),
('static/images/sports_s1.jpg', 2, "This is awesome! Really?"),
('static/images/sports_s2.jpg', 3, "This is also awesome!"),
('static/images/sports_s3.jpg', 4, "This is awesome too!");
