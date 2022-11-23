-- drop table ticket;
-- drop table passengerlocation;
-- drop table passengers;
-- drop table pets;
-- drop table cruiseship;
-- drop table hospitality;
-- drop table passengersstayat;
-- drop table activities;
-- drop table passengersparticipatein;
-- drop table restaurants;
-- drop table passengerseatat;
-- drop table captain;
-- drop table pilots;
-- drop table generalstaffsalary;
-- drop table generalstaff;
-- drop table managehospitality;
-- drop table manageactivities;
-- drop table managerestaurants;

create table cruiseship(
    hullID int primary key,
    cruiseName char(20),
    fromLocation char(20),
    toLocation char(20)
)

create table passengerlocation (
    postalCode char(6) primary key,
    city char(20)
)


create table captain(
    crewID int primary key,
    captainName char(20),
    salary int,
    licenseNum int
)

create table generalstaffsalary(
    staffRole char(20) primary key,
    salary int
)

create table passengers (
    passengerID int primary key,
    passengerName char(20),
    age int,
    postalCode char(6),
    passengerAddress char(20),
    foreign key (postalCode) references passengerlocation
        -- ON DELETE NO ACTION ON UPDATE CASCADE
)

create table ticket (
    ticketID int primary key,
    ticketClass char(20),
    ticketDate char(6),
    hullID int not null,
    passengerID int not null,
    foreign key (hullID) references cruiseship,
        -- ON DELETE NO ACTION ON UPDATE CASCADE,
    foreign key (passengerID) references passengers 
        -- ON DELETE NO ACTION ON UPDATE CASCADE
)

create table pets (
    passengerID int,
    petName char(20),
    breed char(20),
    primary key (passengerID, petName),
    foreign key (passengerID) references passengers
        -- ON DELETE CASCADE ON UPDATE CASCADE
)

create table hospitality(
    roomNo char(20) primary key,
    maxCapacity int,
    roomType char(20),
    hullID int,
    foreign key (hullID) references cruiseship
        -- ON DELETE CASCADE ON UPDATE CASCADE
)

create table passengersstayat(
    passengerID int,
    roomNo char(20),
    primary key (passengerID, roomNo),
    foreign key (passengerID) references passengers,
        -- ON DELETE CASCADE ON UPDATE CASCADE,
    foreign key (roomNo) references hospitality
        -- ON DELETE CASCADE ON UPDATE CASCADE
)

create table activities(
    stall char(20) primary key,
    actStart int,
    actEnd int,
    activityName char(20),
    hullID int,
    foreign key (hullID) references cruiseship
        -- ON DELETE CASCADE ON UPDATE CASCADE
)

create table passengersparticipatein(
    stall char(20),
    passengerID int,
    primary key (stall, passengerID),
    foreign key (stall) references activities,
        -- ON DELETE CASCADE ON UPDATE CASCADE,
    foreign key (passengerID) references passengers
        -- ON DELETE CASCADE ON UPDATE CASCADE
)

create table restaurants(
    stall char(20) primary key,
    restName char(20),
    restStart int,
    restEnd int,
    hullID int,
    foreign key (hullID) references cruiseship
        -- ON DELETE CASCADE ON UPDATE CASCADE
)

create table passengerseatat(
    passengerID int,
    stall char(20),
    primary key (passengerID, stall),
    foreign key (passengerID) references passengers,
        -- ON DELETE CASCADE ON UPDATE CASCADE,
    foreign key (stall) references restaurants
        -- ON DELETE CASCADE ON UPDATE CASCADE
)

create table pilots(
    crewID int,
    hullID int,
    primary key (crewID, hullID),
    foreign key (crewID) references captain,
        -- ON DELETE NO ACTION ON UPDATE CASCADE,
    foreign key (hullID) references cruiseship
        -- ON DELETE NO ACTION ON UPDATE CASCADE
) 

create table generalstaff(
    crewID int primary key,
	staffName char(20),
	staffRole char(20),
	foreign key (staffRole) references generalstaffsalary
		-- ON DELETE NO ACTION
		-- ON UPDATE CASCADE
)

create table managehospitality(
    crewID int,
    roomNum char(20),
    primary key (crewID, roomNum),
    foreign key (crewID) references generalstaff,
        -- ON DELETE NO ACTION ON UPDATE CASCADE,
    foreign key (roomNum) references Hospitality
        -- ON DELETE NO ACTION ON UPDATE CASCADE
)

create table manageactivities(
    crewID int,
    stall char(20),
    primary key (crewID, stall),
    foreign key (crewID) references generalstaff,
        -- ON DELETE NO ACTION ON UPDATE CASCADE,
    foreign key (stall) references activities
        -- ON DELETE NO ACTION ON UPDATE CASCADE
)

create table managerestaurants(
    crewID int,
    stall char(20),
    primary key (crewID, stall),
    foreign key (crewID) references generalstaff,
        -- ON DELETE NO ACTION ON UPDATE CASCADE,
    foreign key (stall) references restaurants
        -- ON DELETE NO ACTION ON UPDATE CASCADE
)