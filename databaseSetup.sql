CREATE DATABASE groups;

USE DATABASE;

CREATE TABLE files(
    file_id int primary key auto_increment,
    file_name varchar(200) not null,
    file_size int not null,
    file_mime_type varchar(20) not null
);
