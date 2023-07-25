## Milestone 1

### Domain
<p> The domain that our project models is a museum. We are going to model the inventory of
exhibits and articles of the museum in addition to how visitors, museum employees, and article owners
interact with them. There are fve different types of articles (identified by unique IDs) within the museum
and information is stored on their date of creation, exhibits they are displayed in, storage location, and
condition. The project will also detail the owners of the articles and any contracts that they have with
the museum. </p>
<p> There will be different types of employees with roles in examining articles, selling tickets,
and managing collections and arranging exhibits. We will also be able to keep track of visitors attending
the museum and which activities they participate in, pertaining to specific exhibits. </p>

### Database Specifications
<p> The application will provide the benefit of logical data independence, improve employee
collaboration through having up to date information in the database, and allow outside users restricted
access to information. </p>

<p> Users of the system will have two different access levels: employees (which itself
has different views depending on employee position), and customers. The front desk staff have access to
ticket and visitor information and can sell tickets and update visitor information, archivists modify the
article information, and curators can manage the collections and exhibits. For the customers of the
museum, visitors can view ticket information and exhibit and activity schedules, and owners can view
their contract and information about their article on loan to the museum. </p>

### Application platform
<p> This project will be done using PHP/Oracle, specifically the CPSC department’s installation of
Oracle. For front-end we are using JavaScript, ReactJS, and HTML. </p>

### Other Comments
<p> If an article is on loan to another museum, the location will be to set to onLoan. Ticket price is
based on the ageCategory of the ticket, for example youth, adult, and senior. We will analyze for
database redundancies in Milestone 2 which will likely occur as a result of this functional dependency.
Only contracts that are complete and accepted by the museum will be stored in the database. </p>

### ER Diagram
![ER_diagram](./images/version3.pdf)
