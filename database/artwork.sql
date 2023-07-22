CREATE TABLE Artwork (
    articleID INT(11) PRIMARY KEY,
    artist VARCHAR(50),
    yearMade INT(10),
    medium VARCHAR(50),
    FOREIGN KEY articleID REFERENCES Article,
);