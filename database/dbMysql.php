<?php

require_once 'config/config.php';

$dbh = new PDO("mysql:host=" . DB_HOST, DB_USER, DB_PASS);
$dbh->query('DROP DATABASE ' . DB_NAME);

$dbh->query('CREATE DATABASE ' . DB_NAME . ';');

$dbh->query('CREATE TABLE IF NOT EXISTS Camagru.users (
	id INT AUTO_INCREMENT PRIMARY KEY,
	user_name VARCHAR(40) NOT NULL,
	email VARCHAR(40) NOT NULL,
	is_active BOOLEAN DEFAULT FALSE,
    is_notificate BOOLEAN DEFAULT FALSE,
	password VARCHAR(60) NOT NULL
);');

$dbh->query('CREATE TABLE Camagru.pending_users (
    token CHAR(40) NOT NULL,
    user_id INT NOT NULL,
    PRIMARY KEY(token),
    CONSTRAINT pending_users_users_fk FOREIGN KEY (user_id) REFERENCES Camagru.users(id) ON DELETE CASCADE
);');

$dbh->query('CREATE TABLE Camagru.recovery_users (
    token CHAR(40) NOT NULL,
    user_id integer NOT NULL,
    PRIMARY KEY(token),
    CONSTRAINT recovery_users_users_fk FOREIGN KEY (user_id) REFERENCES Camagru.users(id) ON DELETE CASCADE
);');

$dbh->query('CREATE TABLE Camagru.edited_images (
    name CHAR(40) NOT NULL,
    path CHAR(100) NOT NULL,
    user_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY(name),
    CONSTRAINT edited_images_users_fk FOREIGN KEY (user_id) REFERENCES Camagru.users(id) ON DELETE CASCADE
);');

$dbh->query('CREATE TABLE Camagru.comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    context TEXT,
    edited_image_id CHAR(40) NOT NULL,
    user_id INT NOT NULL,
    parent_id INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT comments_edited_images_fk FOREIGN KEY (edited_image_id) REFERENCES Camagru.edited_images(name) ON DELETE CASCADE,
    CONSTRAINT comments_users_fk FOREIGN KEY (user_id) REFERENCES Camagru.users(id) ON DELETE CASCADE,
    CONSTRAINT comments_comments_fk FOREIGN KEY (parent_id) REFERENCES Camagru.comments(id) ON DELETE CASCADE
);');

$dbh->query('CREATE TABLE Camagru.likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    edited_image_id CHAR(40) NOT NULL,
    CONSTRAINT likes_edited_images_fk FOREIGN KEY (edited_image_id) REFERENCES Camagru.edited_images(name) ON DELETE CASCADE,
    CONSTRAINT likes_users_fk FOREIGN KEY (user_id) REFERENCES Camagru.users(id) ON DELETE CASCADE,
    CONSTRAINT edited_image_id_user_id_unique UNIQUE(user_id, edited_image_id)
);');

$dbh->query('CREATE TABLE Camagru.image_effects (
    name CHAR(100) PRIMARY KEY
);');

$dbh->query('INSERT INTO Camagru.image_effects(name) VALUES
("effects/effect1.png"),
("effects/effect2.png"),
("effects/effect3.png"),
("effects/effect4.png"),
("effects/effect5.png"),
("effects/effect6.png"),
("effects/effect7.png"),
("effects/effect8.png"),
("effects/effect9.png"),
("effects/effect10.png"),
("effects/effect11.png"),
("effects/effect12.png"),
("effects/effect13.png")
;');