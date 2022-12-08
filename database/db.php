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
