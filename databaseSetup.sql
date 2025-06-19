CREATE DATABASE groups;

USE DATABASE;

CREATE TABLE files(
    file_id int primary key auto_increment,
    file_name varchar(200) not null,
    file_size int not null,
    file_mime_type varchar(20) not null
);

CREATE TABLE tasks(
    task_id int primary key auto_increment,
    task_title varchar(200) not null,
    task_body varchar(2000) not null,
    reference_file_id int,
    FOREIGN KEY (reference_file_id) REFERENCES files(file_id),
    task_user_sentee int not null,
    FOREIGN KEY (task_user_sentee) REFERENCES users(user_id),
    groups_id int not null,
    FOREIGN KEY (groups_id) REFERENCES groups(groups_id),
    task_creation_date datetime not null,
    task_completion_date datetime not null,
    task_last_updated timestamp not null
);

CREATE TABLE sent_tasks(
    sent_task_id int primary key auto_increment,
    sent_task_timestamp timestamp,
    sent_task_status bool not null,
    file_id int,
    FOREIGN KEY (file_id) REFERENCES files(file_id),
    task_id int not null,
    FOREIGN KEY (task_id) REFERENCES tasks(task_id),
    user_id int not null,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE users(
    user_id int primary key auto_increment,
    user_name varchar(50) not null unique,
    password_hash varchar(60) not null
);

CREATE TABLE groups(
    groups_id int primary key auto_increment,
    group_name varchar(50) not null
);

CREATE TABLE group_members(
    group_members_id int primary key auto_increment,
    groups_id int not null,
    FOREIGN KEY (groups_id) REFERENCES groups(groups_id),
    user_id int not null,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- Test data
INSERT INTO groups (group_name) VALUES
('test_group');

INSERT INTO tasks (task_title, task_body, task_creation_date, task_completion_date, task_last_updated, task_user_sentee, groups_id, reference_file_id) VALUES
('Placeholder Title', 'Lorem ipsum dolor sit amet consectetur adipiscing elit quisque faucibus ex sapien vitae pellentesque sem placerat in id cursus mi pretium tellus duis convallis tempus leo eu aenean sed diam urna tempor pulvinar vivamus fringilla lacus nec metus bibendum egestas iaculis massa nisl malesuada lacinia integer nunc posuere ut hendrerit semper vel class aptent taciti sociosqu ad litora torquent per conubia nostra inceptos himenaeos orci varius natoque penatibus et magnis dis parturient montes nascetur ridiculus mus donec rhoncus eros lobortis nulla molestie mattis scelerisque maximus eget fermentum odio phasellus non purus est efficitur laoreet mauris pharetra vestibulum fusce dictum risus.', '2025-06-18 00:00:00', '2025-06-23 23:59:59', '2025-06-18 00:00:00', 1, 1, 35);

INSERT INTO sent_tasks (sent_task_status, task_id, user_id) VALUES
(false, 1, 1);
