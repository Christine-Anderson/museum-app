CREATE TABLE Text (
    articleID INT(11) PRIMARY KEY,
    author VARCHAR(50),
    datePublished DATE,
    FOREIGN KEY(articleID) REFERENCES(Article)
);