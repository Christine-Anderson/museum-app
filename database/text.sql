CREATE TABLE text (
    article_id INT(11) PRIMARY KEY,
    date_published DATE,
    author VARCHAR(50),
    FOREIGN KEY article_id REFERENCES article,
);