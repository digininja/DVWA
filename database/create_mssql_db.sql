/*
In case I get round to adding MS SQL support, this creates and populates the tables.
*/

CREATE DATABASE dvwa;

USE dvwa;

CREATE TABLE users (user_id INT PRIMARY KEY,first_name VARCHAR(15),last_name VARCHAR(15), [user] VARCHAR(15), password VARCHAR(32),avatar VARCHAR(70), last_login DATETIME, failed_login INT);

INSERT INTO users VALUES ('1','admin','admin','admin',CONVERT(NVARCHAR(32),HashBytes('MD5', 'password'),2),'admin.jpg', GETUTCDATE(), '0'), ('2','Gordon','Brown','gordonb',CONVERT(NVARCHAR(32),HashBytes('MD5', 'abc123'),2),'gordonb.jpg', GETUTCDATE(), '0'), ('3','Hack','Me','1337',CONVERT(NVARCHAR(32),HashBytes('MD5', 'charley'),2),'1337.jpg', GETUTCDATE(), '0'), ('4','Pablo','Picasso','pablo',CONVERT(NVARCHAR(32),HashBytes('MD5', 'letmein'),2),'pablo.jpg', GETUTCDATE(), '0'), ('5', 'Bob','Smith','smithy',CONVERT(NVARCHAR(32),HashBytes('MD5', 'password'),2),'smithy.jpg', GETUTCDATE(), '0');

CREATE TABLE guestbook (comment_id INT IDENTITY(1,1) PRIMARY KEY, comment VARCHAR(300), name VARCHAR(100),2);

INSERT INTO guestbook (comment, name) VALUES ('This is a test comment.','test');
