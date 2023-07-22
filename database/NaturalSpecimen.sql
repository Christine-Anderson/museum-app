CREATE TABLE NaturalSpecimen (
    articleID INT(11) PRIMARY KEY,
    nativeTo VARCHAR(50),
    species VARCHAR(50),
    timePeriod VARCHAR(50),
    FOREIGN KEY articleID REFERENCES Article,
);