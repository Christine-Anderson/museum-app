CREATE TABLE Artwork (
    articleID INT(11) PRIMARY KEY,
    artist VARCHAR(50),
    yearMade INT(10),
    medium VARCHAR(50) NOT NULL,
    FOREIGN KEY(articleID) REFERENCES(Article)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);
