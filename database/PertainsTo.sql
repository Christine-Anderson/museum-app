CREATE TABLE PertainsTo(
    contractID INT(11),
    articleID INT(11),
    FOREIGN KEY(contractID) REFERENCES(Contract),
    FOREIGN KEY(articleID) REFERENCES(Article)
);