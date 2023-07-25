CREATE TABLE Artifact (
    articleID INT(11) PRIMARY KEY,
    estimatedYear INT(10),
    madeBy VARCHAR(50),
    material VARCHAR(50),
    FOREIGN KEY(articleID) REFERENCES(Article)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY(creator) REFERENCES(Origin)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION
);