CREATE TABLE Text (
    articleID INT(11) PRIMARY KEY,
    datePublished DATE,
    author VARCHAR(50),
    FOREIGN KEY articleID REFERENCES Article,
);
