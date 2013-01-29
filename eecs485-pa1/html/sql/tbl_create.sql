CREATE TABLE IF NOT EXISTS
User(
username VARCHAR(20) PRIMARY KEY,
password VARCHAR(20),
firstname VARCHAR(20),
lastname VARCHAR(20),
email VARCHAR(40)
);

CREATE TABLE IF NOT EXISTS
Album(
albumid INT PRIMARY KEY AUTO_INCREMENT,
title VARCHAR(50),
created DATETIME,
lastupdated DATETIME,
access VARCHAR(10),
username VARCHAR(20), 
FOREIGN KEY (username) references User(username),
CHECK (access in ('public','private'))
);

CREATE TABLE IF NOT EXISTS
Contain(
albumid INT,
url VARCHAR(255),
caption VARCHAR(255),
sequencenum INT,
PRIMARY KEY (albumid, url),
FOREIGN KEY (albumid) references Album(albumid), 
FOREIGN KEY (url) references Photo(url) 
);

CREATE TABLE IF NOT EXISTS
Photo(
url VARCHAR(255) PRIMARY KEY,
code LONGBLOB,
format CHAR(3),
date DATETIME
);

CREATE TABLE IF NOT EXISTS
AlbumAccess(
albumid INT,
username VARCHAR(20),
PRIMARY KEY (albumid, username),
FOREIGN KEY (albumid) references Album(albumid), 
FOREIGN KEY (username) references User(username) 
);

CREATE TABLE IF NOT EXISTS
Comment(
url varchar(255),
commentseqnum int,
comments varchar(255),
PRIMARY KEY (url, commentseqnum),
FOREIGN KEY (url) references Photo(url)
);

