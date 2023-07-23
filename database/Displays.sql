CREATE TABLE Displays(
    exhibitID INT(11),
    articleID INT(11),
    FOREIGN KEY(exhibitID) REFERENCES(Exhibit),
    FOREIGN KEY(articleID) REFERENCES(Article)
);