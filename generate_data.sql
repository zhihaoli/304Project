-- Here is a bunch of data we made (from the googledoc)
-- You need to CREATE database and then USE whatever you called it
-- so uncomment and/or modify the next 2 lines:

-- CREATE database cs304store;
use cs304store;


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
values('123456789012', 'abc123', 'Test', '123123 fake st', '604-123-4567');

insert into item
values('0001', 'Awesome', 'CD', 'Rock',
'Awesome Recordings', '2014', '11.50', 3);

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
'Awesome Recordings', '2014', '15.50', 4);

insert into leadSinger
values ('0002', 'GoGo Johnson');

insert into hasSong
values ('0002', 'GoGo song 1');

insert into hasSong
values ('0002', 'GoGo song 2');

insert into item
values('0003', 'Hello', 'CD', 'Country',
'Howdy Music', '2014', '20.00', 10);

insert into leadSinger
values ('0003', 'Marian Hello');

insert into hasSong
values ('0003', 'Hello song 1');

insert into hasSong
values ('0003', 'Hello song 2');

-- Cecile's add item data
INSERT INTO item
VALUES ('upc123456789', 'Pug Life', 'DVD', 'Dogudrama',  'CompanyA', 1999, 8.95, 123);

INSERT INTO item
VALUES ('upc023456789', 'Mug Life', 'CD', 'Romance',  'CompanyA', 1998, 10.95, 3);

INSERT INTO item
VALUES ('upc223456789', 'CS 320 AudioTextBook', 'CD', 'RomCom',  'Company Bee', 2010, 3.95, 1);

-- add stock to existing item
UPDATE item
SET stock = stock + 2
WHERE upc = 'upc123456789';

-- generate i_order data
INSERT INTO i_order
VALUES ('receiptId012', '05-19-1999', 'cid123456789', 'cardNumber000016', '05-20-2000', null, null);

INSERT INTO i_order
VALUES ('receiptId112', '12-19-1999', 'cid123456789', 'cardNumber000016', '05-21-2000', null, null);

INSERT INTO i_order
VALUES ('0receiptId12', '05-19-1999', 'cid123456780', 'cardNumber111116', '05-20-2013', null, null);

-- Manager processes delivery
-- user inputs: receiptID, expectedDate
UPDATE i_order
SET expectedDate = '2014-01-31'
WHERE receiptId = '0receiptId12';

