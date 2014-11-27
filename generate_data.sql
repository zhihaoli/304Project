-- Here is a bunch of data we made (from the googledoc)
-- You need to CREATE database and then USE whatever you called it
-- so uncomment and/or modify the next 2 lines:

-- CREATE database cs304store;
use cs304;


drop table if exists item;
create table item
	(upc char(12) not null,
    title varchar(80) not null,
    item_type varchar(4) not null,
    category varchar(40) null,
    company varchar(50) not null,
    item_year int null,
    price float null,
    stock int not null);
    
drop table if exists leadSinger;
create table leadSinger
	(upc char(12) not null,
    name varchar(40) not null);
    
drop table if exists hasSong;
create table hasSong
	(upc char(12) not null,
    title varchar(80) not null);
    
drop table if exists i_order;
create table i_order
	(receiptId char(12) not null,
    order_date varchar(20) null,
    cid char(12) not null,
    cardNumber char(16) not null,
    expiryDate varchar(20) not null,
    expectedDate varchar(20) null,
    deliveredDate varchar(20) null);
    
drop table if exists purchaseItem;
create table purchaseItem
	(receiptId char(12) not null,
    upc char(12) not null,
    quantity int not null);
    
drop table if exists customer;
create table customer
	(cid varchar(12) not null,
    password varchar(20) not null,
    name varchar(40) not null,
    address varchar(40) null,
    phone char(12) null);
    
drop table if exists c_return;
create table c_return
	(retid char(12) not null,
    return_date varchar(20) null,
    receiptId char(12) not null);
    
drop table if exists returnItem;
create table returnItem
	(retid char(12) not null,
    upc char(12) not null,
    quantity int not null);

drop table if exists cart;
create table cart
    (upc char(12) not null,
    title varchar(30),
    quantity int not null);


-- declaring the keys

create unique index itemUpc 
on item (upc);

create unique index singer_upcName 
on leadSinger (upc, name);

create unique index  song_upcTitle
on hasSong (upc, title);
create unique index order_receiptId
on i_order (receiptId);

create unique index purchase_receiptIdUpc
on purchaseItem(receiptId, upc);

create unique index customerCid
on customer (cid);

create unique index returnId
on c_return (retid);

create unique index returnItemID
on returnItem (retid);

-- Tyler's example data

insert into customer
values('bli23', 'abc123', 'Bruce Li', '123123 Maple st', '604-123-4567');

insert into customer
values('cleung', 'abc123', 'Cecile Leung', '223123 Birch Ave', '604-223-4567');

insert into customer
values('mchen', 'abc123', 'Michelle Chen', '323123 Spruce Dr', '604-323-4567');

insert into item
values('0001', 'Awesome', 'CD', 'Rock',
'Awesome Recordings', 2014, 11.50, 3);

insert into leadSinger
values ('0001', 'Jo Awesome');

insert into hasSong
values ('0001', 'Awesome song 1');

insert into hasSong
values ('0001', 'Awesome song 2');

insert into hasSong
values ('0001', 'Awesome song 3');

insert into item
values('0002', 'GoGo', 'DVD', 'Pop',
'Awesome Recordings', 2014, 15.50, 4);

insert into leadSinger
values ('0002', 'GoGo Johnson');

insert into hasSong
values ('0002', 'GoGo song 1');

insert into hasSong
values ('0002', 'GoGo song 2');

insert into item
values('0003', 'Hello', 'CD', 'Country',
'Howdy Music', 2014, 20.00, 10);

insert into leadSinger
values ('0003', 'Marian Hello');

insert into hasSong
values ('0003', 'Hello song 1');

insert into hasSong
values ('0003', 'Hello song 2');

-- Cecile's add item data
INSERT INTO item
VALUES ('0004', 'Pug Life', 'DVD', 'Dogudrama',  'CompanyA', 1999, 8.95, 12);

INSERT INTO item
VALUES ('0005', 'Mug Life', 'DVD', 'Romance',  'CompanyA', 1998, 10.95, 3);

INSERT INTO item
VALUES ('0006', 'CS 320 In Real Life!', 'DVD', 'RomCom',  'Company Bee', 2010, 13.95, 5);


-- generate i_order data
INSERT INTO i_order
VALUES ('P_54768aab8f', '2014-11-28', 'bli23', 'cardNumber000016', '10/17', '2014-11-30', null);

INSERT INTO i_order
VALUES ('P_54768aab8g', '2014-11-28', 'cleung', 'cardNumber000016', '10/17', '2014-11-30', null);

INSERT INTO i_order
VALUES ('P_54768aab8h', '2014-11-28', 'mchen', 'cardNumber111116', '10/17','2014-11-30', null);

INSERT INTO i_order
VALUES ('P_54768aab8i', '2014-11-28', 'bli23', 'cardNumber111116', '10/17', '2014-11-30', null);

INSERT INTO i_order
VALUES ('P_54768aab8j', '2014-11-28', 'bli23', 'cardNumber111116', '10/17', '2014-11-30', null);

-- generate purchaseItem data

INSERT INTO purchaseItem
VALUES ('P_54768aab8f', '0001', 1);

INSERT INTO purchaseItem
VALUES ('P_54768aab8g', '0001', 3);

INSERT INTO purchaseItem
VALUES ('P_54768aab8h', '0002', 3);

INSERT INTO purchaseItem
VALUES ('P_54768aab8i', '0003', 5);

INSERT INTO purchaseItem
VALUES ('P_54768aab8j', '0005', 2);
