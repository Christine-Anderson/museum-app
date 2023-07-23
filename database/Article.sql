CREATE TABLE Article (
    articleID INT(11) PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255),
    dateAquired TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    condition VARCHAR(50),
    storageLocation VARCHAR(50),
    storageCondition VARCHAR(50)
);