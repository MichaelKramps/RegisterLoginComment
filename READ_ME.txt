-Ownership of Code-

//This application was written by Michael Kramps, however,
//This work is not 100% original.
//Much of the logic in this program was inspired by Alex Garrett's tutorials at phpacademy,
//Any questions of originality should be directed to Michael Kramps.
//Email: mdk989@gmail.com

-Explanation of the Application-

//This group of files is intended to provide website users with the ability to create an account and log in to that account
//Once logged in, they can leave comments and view their profile page
//This is a skeleton version of the system I have implemented on my website (guitarlessonoid.com)

-Instructions for use-
	
	-Get Register/Login Functionality-
	//For the register/login system to work you must have a mysql database named 'practice'
	//The host, username and password for the database can be changed in the init.php file in the global config array
	//Use this SQL to put two tables in your 'practice' database:

CREATE TABLE  `practice`.`session` (
`id` INT NOT NULL AUTO_INCREMENT ,
`userid` INT NOT NULL ,
`hash` VARCHAR( 64 ) NOT NULL ,
PRIMARY KEY (  `id` )
) ENGINE = MYISAM ;

CREATE TABLE  `practice`.`users` (
`id` INT NOT NULL AUTO_INCREMENT ,
`username` VARCHAR( 50 ) NOT NULL ,
`password` VARCHAR( 64 ) NOT NULL ,
`salt` VARCHAR( 32 ) NOT NULL ,
PRIMARY KEY (  `id` )
) ENGINE = MYISAM ;


	-Get Comment Functionality-
	//To be able to leave and view comments you must have a mysql database named 'comments'
	//Use this SQL to put one table in your 'comments database'
	
CREATE TABLE  `comments`.`ndex` (
`id` INT NOT NULL AUTO_INCREMENT ,
`username` VARCHAR( 50 ) NOT NULL ,
`comment` TEXT NOT NULL ,
PRIMARY KEY (  `id` )
) ENGINE = MYISAM 