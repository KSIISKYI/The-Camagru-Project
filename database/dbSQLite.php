<?php


if (file_exists('database.sqlite')) {
	unlink('database.sqlite');
}

fopen("database.sqlite", "w");
$db = new PDO('sqlite:database.sqlite');


$db->query('CREATE TABLE IF NOT EXISTS users (
	id INTEGER PRIMARY KEY,
	user_name TEXT NOT NULL,
	email TEXT NOT NULL,
	is_active BOOLEAN DEFAULT FALSE,
    is_notificate BOOLEAN DEFAULT FALSE,
	password TEXT NOT NULL);');

$db->query('CREATE TABLE pending_users (
    token CHAR(40) NOT NULL,
    user_id integer NOT NULL,
    tstamp INTEGER UNSIGNED NOT NULL,
    PRIMARY KEY(token)
	FOREIGN KEY(user_id) REFERENCES users(id)
);');

$db->query('CREATE TABLE recovery_users (
    token CHAR(40) NOT NULL,
    user_id integer NOT NULL,
    tstamp INTEGER UNSIGNED NOT NULL,
    PRIMARY KEY(token)
	FOREIGN KEY(user_id) REFERENCES users(id)
);');

$db->query('CREATE TABLE edited_images (
    name CHAR(40) NOT NULL,
    user_id integer NOT NULL,
    created_at CHAR(40) NOT NULL,
    PRIMARY KEY(name)
	FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
);');

$db->query('CREATE TABLE comments (
    id INTEGER PRIMARY KEY,
    context TEXT,
    edited_image_id integer NOT NULL,
    user_id integer NOT NULL,
    parent_id integer DEFAULT NULL,
    created_at CHAR(40) NOT NULL,
	FOREIGN KEY(edited_image_id) REFERENCES edited_images(name) ON DELETE CASCADE
    FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
    FOREIGN KEY(parent_id) REFERENCES comments(id) ON DELETE CASCADE
);');

$db->query('CREATE TABLE likes (
    id INTEGER PRIMARY KEY,
    user_id INTEGER NOT NULL,
    edited_image_id CHAR(40) NOT NULL,
    FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
    FOREIGN KEY(edited_image_id) REFERENCES edited_images(name) ON DELETE CASCADE
    CONSTRAINT edited_image_id_user_id_unique UNIQUE(user_id, edited_image_id)
);');
