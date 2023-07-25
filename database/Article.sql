CREATE TABLE Article (
    articleID INT(11) PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    dateAquired TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    condition VARCHAR(50) NOT NULL,
    storageLocation VARCHAR(50) NOT NULL,
    UVProtection CHAR(1) NOT NULL,
    tempControl CHAR(1) NOT NULL,
    humidityControl CHAR(1) NOT NULL
);