CREATE TABLE Photo (
    articleID INT(11) PRIMARY KEY,
    yearTaken INT(10),
    locationTaken VARCHAR(50),
    FOREIGN KEY articleID REFERENCES Article,
);
