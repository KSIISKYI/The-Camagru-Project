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



