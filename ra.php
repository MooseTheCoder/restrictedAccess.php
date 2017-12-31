<?php
/* 

	Restricted access version 1.0
	This script was developed for a friend of mine who wanted to have a "PayWall" on her website
	The script can be used to allow one time access, or work with your existing user system
	
*/

//Database connection function, not used for OTK
function db(){
	return mysqli_connect("host","user","password","database");
}

/*
	One Time Key Access
	OTK is the key the user needs to match, this is databaseless
*/

const OTK = "";

function auth_otk($k){
	if($k != OTK){
		return false;
	}
	return true;
}

function require_otk(){
	if(!isset($_SESSION['ra']) && $_SESSION['ra'] == true){
		return false;
	}
	return true;
}

/*

	Database Authentication
	You must have a table called to store keys, I will call mine access.
	It must contain : 
	id int PK AI
	key UK 
	active tinyint
	
	The user table must also be modified to have a "key" column
	
*/
function make_user_key($user){
	$k = get_active_key();
	return sha1($user.$k['key']);
}

function get_active_key(){
	$db = db();
	$ak = mysqli_query($db,"SELECT * FROM access WHERE active = 1");
	mysqli_close();
	return mysqli_fetch_assoc($ak);
}
//userID is the id of the user in the database
function match_user_key($userID){
	$userTable = "user"; //This should be user users table
	$db = db();
	$u = mysqli_query($db,"SELECT * FROM $user WHERE id=$userID");
	$k = get_active_key();
	//Make the key
	$key = make_user_key($u['username'])
	if($u['key'] != $key){
		return false
	}
	return true;
}
