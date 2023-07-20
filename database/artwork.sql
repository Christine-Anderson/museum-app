CREATE TABLE artwork (
    article_id INT(11) PRIMARY KEY,
    artist VARCHAR(50),
    year_made INT(10),
    medium VARCHAR(50),
    FOREIGN KEY article_id REFERENCES article,
);