CREATE TABLE natural_specimens (
    article_id INT(11) PRIMARY KEY,
    native_to VARCHAR(50),
    species VARCHAR(50),
    time_period VARCHAR(50),
    FOREIGN KEY article_id REFERENCES article,
);