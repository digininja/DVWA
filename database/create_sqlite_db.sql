CREATE TABLE `users` (
`user_id` int NOT NULL,
`first_name` text DEFAULT NULL,
`last_name` text DEFAULT NULL,
`user` text DEFAULT NULL,
`password` text DEFAULT NULL,
`avatar` text DEFAULT NULL,
`last_login` datetime,
`failed_login` int,
PRIMARY KEY (`user_id`)
);

CREATE TABLE `guestbook` (
`comment_id` int,
`comment` text default null,
`name` text DEFAULT NULL,
PRIMARY KEY (`comment_id`)
);


insert into users values ('1','admin','admin','admin',('password'),'admin.jpg', DATE(), '0');
insert into users values ('2','Gordon','Brown','gordonb',('abc123'),'gordonb.jpg', DATE(), '0');
insert into users values ('3','Hack','Me','1337',('charley'),'1337.jpg', DATE(), '0');
insert into users values ('4','Pablo','Picasso','pablo',('letmein'),'pablo.jpg', DATE(), '0');
insert into users values ('5','Bob','Smith','smithy',('password'),'smithy.jpg', DATE(), '0');;

insert into guestbook values ('1', 'What a brilliant app!', 'Marcel Marceau');
