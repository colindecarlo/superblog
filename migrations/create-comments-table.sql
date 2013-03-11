--liquibase formatted sql

--changeset colin:add_comments_table
CREATE TABLE comments (
    comment_id INT(11) NOT NULL AUTO_INCREMENT,
    post_id INT(11) NOT NULL,
    email VARCHAR(255) NULL,
    comment TEXT NOT NULL,
    PRIMARY KEY (comment_id, post_id),
    CONSTRAINT fk_posts FOREIGN KEY (post_id) REFERENCES posts (post_id)
);
