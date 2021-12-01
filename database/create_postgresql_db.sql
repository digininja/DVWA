CREATE TABLE users (user_id INT PRIMARY KEY,first_name VARCHAR(15),last_name VARCHAR(15), "user" VARCHAR(15), password VARCHAR(32),avatar VARCHAR(70), last_login timestamp, failed_login INT);

INSERT INTO users VALUES ('1','admin','admin','admin',MD5('password'),'admin.jpg', CURRENT_TIMESTAMP, '0'),('2','Gordon','Brown','gordonb',MD5('abc123'),'gordonb.jpg', CURRENT_TIMESTAMP, '0'), ('3','Hack','Me','1337',MD5('charley'),'1337.jpg', CURRENT_TIMESTAMP, '0'), ('4','Pablo','Picasso','pablo',MD5('letmein'),'pablo.jpg', CURRENT_TIMESTAMP, '0'), ('5', 'Bob','Smith','smithy',MD5('password'),'smithy.jpg', CURRENT_TIMESTAMP, '0');

CREATE TABLE guestbook (comment_id serial PRIMARY KEY, comment VARCHAR(300), name VARCHAR(100));

INSERT INTO guestbook (comment, name) VALUES ('This is a test comment.','test');
