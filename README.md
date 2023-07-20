# The museum app

## Domain
The domain that our project models is a museum. We are going to model the inventory of
exhibits and artcles of the museum in additon to how visitors, museum employees, and artcle owners
interact with them. There are fve diferent types of artcles (identfed by unique IDs) within the museum
and informaton is stored on their date of creaton, exhibits they are displayed in, storage locaton, and
conditon. The project will also detail the owners of the artcles and any contracts that they have with
the museum. 
There will be diferent types of employees with roles in examining artcles, selling tckets,
and managing collectons and arranging exhibits. We will also be able to keep track of visitors atending
the museum and which actvites they partcipate in, pertaining to specifc exhibits.

## Benefits
The applicaton will provide the beneft of logical data independence, improve employee
collaboraton through having up to date informaton in the database, and allow outside users restricted
access to informaton. 

## Views
Users of the system will have two diferent access levels: employees (which itself
has diferent views depending on employee positon), and customers. The front desk staf have access to
ticket and visitor informaton and can sell tckets and update visitor informaton, archivists modify the
article informaton, and curators can manage the collectons and exhibits. For the customers of the
museum, visitors can view ticket informaton and exhibit and actvity schedules, and owners can view
their contract and informaton about their artcle on loan to the museum.

## Tools
This project will be done using PHP/Oracle, specifically the CPSC department’s installaton of
Oracle. For front-end we are using JavaScript, ReactJS, and HTML.

## Details
If an article is on loan to another museum, the location will be to set to onLoan. Ticket price is
based on the ageCategory of the ticket, for example youth, adult, and senior. We will analyze for
database redundancies in Milestone 2 which will likely occur as a result of this functonal dependency.
Only contracts that are complete and accepted by the museum will be stored in the database.
