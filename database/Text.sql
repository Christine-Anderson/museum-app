CREATE TABLE Text (
    articleID INT(11) PRIMARY KEY,
    author VARCHAR(50),
    datePublished DATE NOT NULL,
    FOREIGN KEY(articleID) REFERENCES(Article)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);
