CREATE TABLE article (
    article_id INT(11) PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255),
    condition VARCHAR(50),
    storage_location VARCHAR(50),
    storage_condition VARCHAR(50),
    date_aquired TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
);