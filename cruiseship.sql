drop table managerestaurants;
drop table manageactivities;
drop table managehospitalities;
drop table generalstaff;
drop table pilots;
drop table passengerseatat;
drop table restaurants;
drop table passengersparticipatein;
drop table activities;
drop table passengersstayat;
drop table hospitality;
drop table pets;
drop table ticket;
drop table passengers;
drop table generalstaffsalary;
drop table captain;
drop table passengerlocation;
drop table cruiseship;

create table cruiseship(
    hullID int primary key,
    cruiseName char(20),
    fromLocation char(20),
    toLocation char(20)
);

create table passengerlocation (
    postalCode char(6) primary key,
    city char(20)
);


create table captain(
    crewID int primary key,
    captainName char(20),
    salary int,
    licenseNum int
);

create table generalstaffsalary(
    staffRole char(20) primary key,
    salary int
);

create table passengers (
    passengerID int primary key,
    passengerName char(20),
    age int,
    postalCode char(6),
    passengerAddress char(20),
    foreign key (postalCode) references passengerlocation
);

create table ticket (
    ticketID int primary key,
    ticketClass char(20),
    ticketDate char(20),
    hullID int not null,
    passengerID int not null,
    foreign key (hullID) references cruiseship,
    foreign key (passengerID) references passengers
        ON DELETE CASCADE
);

create table pets (
    passengerID int,
    petName char(20),
    breed char(20),
    primary key (passengerID, petName),
    foreign key (passengerID) references passengers
        ON DELETE CASCADE
);

create table hospitality(
    roomNo char(20) primary key,
    maxCapacity int,
    roomType char(20),
    hullID int,
    foreign key (hullID) references cruiseship
        ON DELETE CASCADE
);

create table passengersstayat(
    passengerID int,
    roomNo char(20),
    primary key (passengerID, roomNo),
    foreign key (passengerID) references passengers
        ON DELETE CASCADE,
    foreign key (roomNo) references hospitality
        ON DELETE CASCADE
);

create table activities(
    stall char(20) primary key,
    actStart int,
    actEnd int,
    activityName char(20),
    hullID int,
    foreign key (hullID) references cruiseship
        ON DELETE CASCADE
);

create table passengersparticipatein(
    stall char(20),
    passengerID int,
    primary key (stall, passengerID),
    foreign key (stall) references activities
        ON DELETE CASCADE,
    foreign key (passengerID) references passengers
        ON DELETE CASCADE
);

create table restaurants(
    stall char(20) primary key,
    restName char(20),
    restStart int,
    restEnd int,
    hullID int,
    foreign key (hullID) references cruiseship
        ON DELETE CASCADE
);

create table passengerseatat(
    passengerID int,
    stall char(20),
    primary key (passengerID, stall),
    foreign key (passengerID) references passengers
        ON DELETE CASCADE,
    foreign key (stall) references restaurants
        ON DELETE CASCADE
);

create table pilots(
    crewID int,
    hullID int,
    primary key (crewID, hullID),
    foreign key (crewID) references captain,
    foreign key (hullID) references cruiseship
);

create table generalstaff(
    crewID int primary key,
	staffName char(20),
	staffRole char(20),
	foreign key (staffRole) references generalstaffsalary
);

create table managehospitalities(
    crewID int,
    roomNum char(20),
    primary key (crewID, roomNum),
    foreign key (crewID) references generalstaff,
    foreign key (roomNum) references hospitality
);

create table manageactivities(
    crewID int,
    stall char(20),
    primary key (crewID, stall),
    foreign key (crewID) references generalstaff,
    foreign key (stall) references activities
);

create table managerestaurants(
    crewID int,
    stall char(20),
    primary key (crewID, stall),
    foreign key (crewID) references generalstaff,
    foreign key (stall) references restaurants 
);


insert into cruiseship 
values ('1', 'Princess Cruises', 'Vancouver, BC', 'Vancouver Island, BC');
insert into cruiseship 
values ('2', 'Disney Cruise Line', 'Tsawwassen, BC', 'Tsawwassen, BC');
insert into cruiseship 
values ('3', 'Viking Cruises', 'Vancouver, BC', 'Vancouver, BC');
insert into cruiseship 
values ('4', 'Crystal Cruises', 'Vancouver, BC', 'Bowen Island, BC');
insert into cruiseship 
values ('5', 'Klondike Cruises', 'Nanaimo, BC', 'Vancouver, BC');

insert into passengerlocation
values ('v3t', 'surrey');
insert into passengerlocation
values ('v1m', 'surrey');
insert into passengerlocation
values ('v3j', 'burnaby');
insert into passengerlocation
values ('v3n', 'burnaby');
insert into passengerlocation
values ('v5z', 'vancouver');

insert into captain
values ('3', 'Masa', '120000', '1278');
insert into captain
values ('4', 'Brian', '160000', '2834');
insert into captain
values ('5', 'Alex', '170000', '7263');
insert into captain
values ('6', 'Mike', '200000', '4028');
insert into captain
values ('7', 'Chris', '110000', '3948');
insert into captain
values ('18', 'Bob Marley', '230000', '1278');
insert into captain
values ('19', 'Tupac', '90000', '2834');
insert into captain
values ('20', 'Snoop Dogg', '163000', '7263');
insert into captain
values ('21', '50 Cent', '125000', '4028');
insert into captain
values ('25', 'Jay Z', '103000', '3948');

insert into generalstaffsalary
values ('Housekeeping', '50000');
insert into generalstaffsalary
values ('Waiter', '55000');
insert into generalstaffsalary
values ('Cook', '70000');
insert into generalstaffsalary
values ('Activity Manager', '52000');
insert into generalstaffsalary
values ('Janitor', '60000');

insert into passengers
values ('1', 'Jason Smith', '21', 'v3t', '31232 140 Street');
insert into passengers
values ('2', 'John Legend', '35', 'v1m', '87732 128 Street');
insert into passengers
values ('3', 'Kim Jane', '28', 'v3j', '68232 64 Avenue');
insert into passengers
values ('4', 'Daniel Jane', '29', 'v3j', '68232 64 Avenue');
insert into passengers
values ('5', 'Alex Green', '33', 'v5z', '24521 120A Street');
insert into passengers
values ('6', 'Adrian Chun', '60', 'v3n', '43262 80 Avenue');
insert into passengers
values ('7', 'Ashley Campbell', '31', 'v5z', '14461 96 Street');
insert into passengers
values ('8', 'David Campbell', '32', 'v5z', '23232 100 Street');
insert into passengers
values ('9', 'Snoopy Binoo', '31', 'v3t', '23022 156 Street');
insert into passengers
values ('10', 'Joshua Asfa', '60', 'v3n', '76493 94 Avenue');

insert into ticket
values ('1', 'luxury', '6/23/22', '1', '1');
insert into ticket
values ('2', 'economy', '6/23/22', '1', '2');
insert into ticket
values ('3', 'suite', '9/20/22', '2', '3');
insert into ticket
values ('4', 'suite', '9/20/22', '2', '4');
insert into ticket
values ('5', 'economy', '9/25/22', '3', '5');
insert into ticket
values ('6', 'luxury', '10/15/22', '4', '6');
insert into ticket
values ('7', 'suite', '10/21/22', '5', '7');
insert into ticket
values ('8', 'suite', '10/21/22', '5', '8');
insert into ticket
values ('15', 'luxury', '6/30/22', '1', '9');
insert into ticket
values ('20', 'economy', '6/30/22', '1', '10');

insert into pets
values ('1', 'Bogey', 'Golden Retriever');
insert into pets
values ('3', 'Armpit', 'Pitbull');
insert into pets
values ('5', 'Steve', 'Siamese Cat');
insert into pets
values ('6', 'Bummy', 'Burmese Cat');
insert into pets
values ('7', 'Hammy', 'Syrian Hamster');

insert into hospitality
values ('PC1-01', '4', 'double kings', '1');
insert into hospitality
values ('PC1-02', '2', 'double twin', '1');
insert into hospitality
values ('PC1-03', '4', 'double kings', '1');
insert into hospitality
values ('PC1-04', '2', 'double twin', '1');
insert into hospitality
values ('DCL2-01', '2', 'single queen', '2');
insert into hospitality
values ('VC3-01', '1', 'single twin', '3');
insert into hospitality
values ('CC4-01', '4', 'double kings', '4');
insert into hospitality
values ('KC5-01', '2', 'double queen', '5');

insert into passengersstayat
values ('1', 'PC1-01');
insert into passengersstayat
values ('2', 'PC1-02');
insert into passengersstayat
values ('9', 'PC1-03');
insert into passengersstayat
values ('10', 'PC1-04');
insert into passengersstayat
values ('3', 'DCL2-01');
insert into passengersstayat
values ('4', 'DCL2-01');
insert into passengersstayat
values ('5', 'VC3-01');
insert into passengersstayat
values ('6', 'CC4-01');
insert into passengersstayat
values ('7', 'KC5-01');
insert into passengersstayat
values ('8', 'KC5-01');

insert into activities
values ('PC1-DANCE1', '1200', '1500', 'dancing', '1');
insert into activities
values ('PC1-DANCE2', '1600', '1800', 'dancing', '1');
insert into activities
values ('DCL2-DRAW1', '1400', '1900', 'painting', '2');
insert into activities
values ('DCL2-SWIM1', '1200', '2000', 'swimming', '2');
insert into activities
values ('VC3-SWIM1', '1100', '2100', 'swimming', '3');
insert into activities
values ('CC4-MNGLF1', '1400', '2300', 'mini golf', '4');
insert into activities
values ('KC5-MOVIE1', '1800', '2000', 'movies', '5');

insert into passengersparticipatein
values ('PC1-DANCE1', '3');
insert into passengersparticipatein
values ('PC1-DANCE2', '3');
insert into passengersparticipatein
values ('DCL2-DRAW1', '3');
insert into passengersparticipatein
values ('DCL2-SWIM1', '3');
insert into passengersparticipatein
values ('VC3-SWIM1', '3');
insert into passengersparticipatein
values ('CC4-MNGLF1', '3');
insert into passengersparticipatein
values ('KC5-MOVIE1', '3');
insert into passengersparticipatein
values ('PC1-DANCE1', '5');
insert into passengersparticipatein
values ('PC1-DANCE2', '5');
insert into passengersparticipatein
values ('DCL2-DRAW1', '5');
insert into passengersparticipatein
values ('DCL2-SWIM1', '5');
insert into passengersparticipatein
values ('VC3-SWIM1', '5');
insert into passengersparticipatein
values ('CC4-MNGLF1', '5');
insert into passengersparticipatein
values ('KC5-MOVIE1', '5');
insert into passengersparticipatein
values ('DCL2-SWIM1', '4');
insert into passengersparticipatein
values ('KC5-MOVIE1', '7');
insert into passengersparticipatein
values ('KC5-MOVIE1', '8');
insert into passengersparticipatein
values ('PC1-DANCE1', '1');

insert into restaurants
values ('DCL2-MCD', 'McDonalds', '0000', '2400', '2');
insert into restaurants
values ('KC5-KEG', 'The Keg', '1600', '2400', '5');
insert into restaurants
values ('PC1-TACO', 'Tacofina', '0800', '2200', '1');
insert into restaurants
values ('CC4-POKE', 'Pokaye', '1000', '2100', '4');
insert into restaurants
values ('VC3-EARLS', 'Earls', '1200', '2400', '3');

insert into passengerseatat 
values ('3', 'DCL2-MCD');
insert into passengerseatat 
values ('4', 'DCL2-MCD');
insert into passengerseatat 
values ('7', 'KC5-KEG');
insert into passengerseatat 
values ('8', 'KC5-KEG');
insert into passengerseatat 
values ('1', 'PC1-TACO');

insert into pilots
values ('3', '1');
insert into pilots
values ('4', '2');
insert into pilots
values ('5', '3');
insert into pilots
values ('6', '4');
insert into pilots
values ('7', '5');
insert into pilots
values ('18', '1');
insert into pilots
values ('19', '2');
insert into pilots
values ('20', '3');
insert into pilots
values ('21', '4');
insert into pilots
values ('25', '5');

insert into generalstaff
values ('10', 'Jason', 'Housekeeping');
insert into generalstaff
values ('11', 'Andy', 'Waiter');
insert into generalstaff
values ('12', 'West', 'Cook');
insert into generalstaff
values ('13', 'Janel', 'Activity Manager');
insert into generalstaff
values ('14', 'Ariana', 'Housekeeping');
insert into generalstaff
values ('15', 'Kawhi', 'Janitor');
insert into generalstaff
values ('16', 'Jinmin', 'Housekeeping');
insert into generalstaff
values ('17', 'Elon', 'Activity Manager');
insert into generalstaff
values ('29', 'Johnathon', 'Housekeeping');
insert into generalstaff
values ('30', 'Rachel', 'Housekeeping');
insert into generalstaff
values ('35', 'Jasmine', 'Housekeeping');
insert into generalstaff
values ('39', 'Mary', 'Housekeeping');

insert into managehospitalities
values ('10', 'PC1-01');
insert into managehospitalities
values ('10', 'PC1-02');
insert into managehospitalities
values ('30', 'PC1-03');
insert into managehospitalities
values ('35', 'PC1-04');
insert into managehospitalities
values ('10', 'KC5-01');
insert into managehospitalities
values ('11', 'DCL2-01');
insert into managehospitalities
values ('11', 'VC3-01');
insert into managehospitalities
values ('12', 'CC4-01');


insert into manageactivities
values ('13', 'PC1-DANCE1');
insert into manageactivities
values ('17', 'PC1-DANCE2');
insert into manageactivities
values ('15', 'DCL2-DRAW1');
insert into manageactivities
values ('15', 'VC3-SWIM1');
insert into manageactivities
values ('17', 'KC5-MOVIE1');

insert into managerestaurants
values ('11', 'DCL2-MCD');
insert into managerestaurants
values ('11', 'KC5-KEG');
insert into managerestaurants
values ('12', 'PC1-TACO');
insert into managerestaurants
values ('12', 'CC4-POKE');
insert into managerestaurants
values ('12', 'VC3-EARLS');