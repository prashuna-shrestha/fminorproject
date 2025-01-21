create database minorproject;
use minorproject;
create table signupss(
    id INT AUTO_INCREMENT PRIMARY KEY,  -- company id
    firstname VARCHAR(50) NOT NULL,
    lastname VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    isactive Varchar(1) NOT NULL
);
ALTER TABLE signupss
ADD COLUMN profile_picture VARCHAR(255);
alter table signupss
ADD column company varchar(100),
ADD column location varchar(100);
select * from signupss;

-- FOR PROJECT PROFILE
CREATE TABLE C_Project (
    ProjectID INT PRIMARY KEY AUTO_INCREMENT,
    companyId INT, -- signup id
    FOREIGN KEY (companyId) REFERENCES signupss(id),
    jobPosition VARCHAR(200) NOT NULL,
    academicQualification VARCHAR(200) NOT NULL,
    experiences VARCHAR(255) NOT NULL,
    skills VARCHAR(255) NOT NULL,
    deadline DATE NOT NULL
);
ALTER TABLE c_project
MODIFY COLUMN freelancerRequired VARCHAR(50) not null;

ALTER TABLE C_Project
ADD COLUMN salary varchar(200);
SELECT * FROM C_Project;

-- freelancer division
create table Freelancerss(
freelancerID INT PRIMARY KEY AUTO_INCREMENT,
    firstName VARCHAR(200) NOT NULL,
    lastName VARCHAR(200) NOT NULL,
    email varchar(200) NOT NULL UNIQUE,
    experiences TEXT,
    skills TEXT,
    academicQualification VARCHAR(200),
    passwordHash varchar(255) NOT NULL,
    isactive Varchar(1) NOT NULL
    );
    ALTER TABLE Freelancerss
	ADD COLUMN profile_picture VARCHAR(255);
select * from Freelancerss;
    
CREATE TABLE Proposalsass(
    proposalID INT PRIMARY KEY auto_increment,
    companyId INT, 
    FreelancerID INT, -- Assuming this refers to the FreelancerID from Freelancerss table
    ProjectID INT, -- Assuming this refers to the ProjectID from C_Project table
    FOREIGN KEY (FreelancerID) REFERENCES Freelancerss(FreelancerID),
    FOREIGN KEY (ProjectID) REFERENCES  Project(ProjectID)
   
    -- add proposal status
);
Alter table Proposalsass 
Add column fileUpload varchar(255);
SELECT * FROM Proposalsass;
ALTER TABLE Proposalsass
ADD COLUMN status VARCHAR(20) DEFAULT 'pending', -- 'accepted', 'rejected', 'pending'
ADD COLUMN submissionTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

select * from proposalsass;

-- Remove default values from the company and jobPosition columns
ALTER TABLE Proposalsass
MODIFY COLUMN company varchar(100),
MODIFY COLUMN jobPosition VARCHAR(200);


-- admin
create table user(
    id INT AUTO_INCREMENT PRIMARY KEY,  -- company id
    firstname VARCHAR(50) NOT NULL,
    lastname VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL ,
    password_hash VARCHAR(255) NOT NULL,
    contact varchar(100),
    age varchar(10)
    );
select * from user;

-- update signupss set isactive = "Y" where id = 1;

