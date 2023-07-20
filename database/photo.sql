CREATE TABLE photo (
    article_id INT(11) PRIMARY KEY,
    year_taken INT(10),
    location_taken VARCHAR(50),
    FOREIGN KEY article_id REFERENCES article,
);