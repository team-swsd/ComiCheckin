<?php

// DBを掴む
$db = new SQLite3(dirname(__FILE__) . '/../comicheckin-db.sqlite3');

// テーブル作成
$db->exec('
CREATE TABLE IF NOT EXISTS "member" (
	"id"	INTEGER PRIMARY KEY AUTOINCREMENT,
	"name"	text
)');

$db->exec('
CREATE TABLE IF NOT EXISTS "visitlog" (
    "id"    INTEGER PRIMARY KEY AUTOINCREMENT,
    value json
)');

$db->exec('
CREATE TABLE IF NOT EXISTS "purchaselog" (
    "id"    INTEGER PRIMARY KEY AUTOINCREMENT,
    "item_name" text,
    "created_at" TEXT NOT NULL DEFAULT (DATETIME(\'now\', \'localtime\'))
)');
