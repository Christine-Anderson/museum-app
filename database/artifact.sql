CREATE TABLE artifact (
    article_id INT(11) PRIMARY KEY,
    estimated_year INT(10),
    made_by VARCHAR(50),
    region_of_origin VARCHAR(50),
    material VARCHAR(50),
    FOREIGN KEY article_id REFERENCES article,
);