create table genuser (
     id double precision not null unique,
     name char(50) not null unique,
     password char(50),
     raccessap int not null,
     raccessar int not null,
     raccessgl int not null,
     raccesspay int not null,
     raccessinv int not null,
     raccessl1 int not null,
     raccessl2 int not null,
     raccessl3 int not null,
     raccessfln int not null,
     raccessfix int not null,
     raccessimp int not null,
     waccessap int not null,
     waccessar int not null,
     waccessgl int not null,
     waccesspay int not null,
     waccessinv int not null,
     waccessl1 int not null,
     waccessl2 int not null,
     waccessl3 int not null,
     waccessfln int not null,
     waccessfix int not null,
     waccessimp int not null,
     supervisor int not null,
     active int not null,
     stylesheetid int not null,
     deflanguage int not null,
     primary key(id));

create sequence genuserseq increment by 1 start with 1;
create trigger genusertrig before insert on genuser
for each row
when (new.id is null)
	begin select genuserseq.nextval into :new.id from dual;
end


create table genmessage (
     id double precision not null unique,
     userid double precision not null,
     sourceuserid double precision not null,
     entrydate date not null,
     readdate date not null,
     message blob not null,
     primary key(id));

create sequence genmessageseq increment by 1 start with 1;
create trigger genmessagetrig before insert on genmessage
for each row
when (new.id is null)
	begin select genmessageseq.nextval into :new.id from dual;
end


create table genstylesheet (
     id int not null unique,
     name char(50) not null unique,
     file char(50) not null unique,
     primary key(id));

create sequence genstylesheetseq increment by 1 start with 1;
create trigger genstylesheettrig before insert on genstylesheet
for each row
when (new.id is null)
	begin select genstylesheetseq.nextval into :new.id from dual;
end


create table gencompany (
     id int not null unique,
     address1 char(100),
     address2 char(100),
     city char(50),
     state char(20),
     zip char(15),
     country char(20),
     phone1 char(20),
     phone2 char(20),
     phone3 char(20),
     phone4 char(20),
     email char(50),
     web char(100),
     name char(50),
     active int,
     primary key(id));

create sequence gencompanyseq increment by 1 start with 1;
create trigger gencompanytrig before insert on gencompany
for each row
when (new.id is null)
	begin select gencompanyseq.nextval into :new.id from dual;
end


create table glaccount (
     id double precision not null unique,
     name char(6) not null,
     description char(30) not null,
     accounttypeid int not null,
     companyid double precision not null,
     summaryaccountid double precision not null,
     lastchangedate timestamp,
     lastchangeuserid double precision,
     primary key(id));

create sequence glaccountseq increment by 1 start with 1;
create trigger glaccounttrig before insert on glaccount
for each row
when (new.id is null)
	begin select glaccountseq.nextval into :new.id from dual;
end


create table accounttype (
     id int not null unique,
     description char(30) not null,
     primary key(id));


create table gltransvoucher(
     id double precision not null unique,
     voucher char(20) not null,
     description char(50) not null,
     comments char(50) not null,
     wherefrom int not null,
     status int,
     cancel int,
     companyid double precision not null,
     standardset double precision not null,
     entrydate date,
     posteddate date,
     post2date date not null,
     canceldate date,
     lastchangedate timestamp,
     lastchangeuserid double precision,
     entryuserid double precision,
     canceluserid double precision,
     postuserid double precision not null,
     primary key(id));

create sequence gltransvoucheseq increment by 1 start with 1;
create trigger gltransvouchetrig before insert on gltransvouche
for each row
when (new.id is null)
	begin select gltransvoucheseq.nextval into :new.id from dual;
end


create table gltransaction(
     id double precision not null unique,
     glaccountid double precision not null,
     voucherid double precision,
     amount decimal(14,2) not null,
     primary key(id));

create sequence gltransactioseq increment by 1 start with 1;
create trigger gltransactiotrig before insert on gltransactio
for each row
when (new.id is null)
	begin select gltransactioseq.nextval into :new.id from dual;
end


create table glbudgets (
     id double precision not null unique,
     glaccountid double precision not null,
     companyid double precision not null,
     budgetyear int not null,
     jan decimal(14,2) not null,
     feb decimal(14,2) not null,
     mar decimal(14,2) not null,
     apr decimal(14,2) not null,
     may decimal(14,2) not null,
     jun decimal(14,2) not null,
     jul decimal(14,2) not null,
     aug decimal(14,2) not null,
     sep decimal(14,2) not null,
     oct decimal(14,2) not null,
     nov decimal(14,2) not null,
     decm decimal(14,2) not null,
     lastchangedate timestamp,
     lastchangeuserid double precision not null,
     primary key(id));

create sequence glbudgetsseq increment by 1 start with 1;
create trigger glbudgetstrig before insert on glbudgets
for each row
when (new.id is null)
	begin select glbudgetsseq.nextval into :new.id from dual;
end


create table glcompany (
     id double precision not null unique,
     fiscalbeginmonth int not null,
     primary key(id));


create table item(
     id double precision not null unique,
     itemcode char(20) not null,
     compositeitemyesno int not null,
     description char(100) not null,
     categoryid double precision not null,
     stockunitnameid double precision not null,
     priceunitnameid double precision not null,
     lbsperpriceunit double precision not null,
     priceunitsperstockunit double precision not null,
     inventoryglacctid double precision not null,
     catalogdescription blob,
     catalogsheeturl char(200),
     graphicurl char(200),
     companyid double precision not null,
     cancel int not null,
     canceldate date,
     canceluserid double precision,
     entrydate date,
     entryuserid double precision,
     lastchangedate timestamp,
     lastchangeuserid double precision,
     primary key(id));

create sequence iteseq increment by 1 start with 1;
create trigger itetrig before insert on ite
for each row
when (new.id is null)
	begin select iteseq.nextval into :new.id from dual;
end


create table itemcategory(
     id double precision not null unique,
     name char(50),
     seasonname1 char(20) not null,
     seasonname2 char(20) not null,
     seasonname3 char(20) not null,
     seasonname4 char(20) not null,
     seasonbegin1 int not null,
     seasonbegin2 int not null,
     seasonbegin3 int not null,
     seasonbegin4 int not null,
     seasonend1 int not null,
     seasonend2 int not null,
     seasonend3 int not null,
     seasonend4 int not null,
     cancel int not null,
     canceldate date,
     canceluserid double precision,
     entrydate date,
     entryuserid double precision,
     lastchangedate timestamp,
     lastchangeuserid double precision,
     primary key(id));

create sequence itemcategorseq increment by 1 start with 1;
create trigger itemcategortrig before insert on itemcategor
for each row
when (new.id is null)
	begin select itemcategorseq.nextval into :new.id from dual;
end


create table compositeitemid(
     id double precision not null unique,
     itemcodeid double precision not null,
     subitemcodeid double precision,
     quantity double precision not null,
     cancel int not null,
     canceldate date,
     canceluserid double precision,
     entrydate date,
     entryuserid double precision,
     lastchangedate timestamp,
     lastchangeuserid double precision,
     primary key(id));

create sequence compositeitemiseq increment by 1 start with 1;
create trigger compositeitemitrig before insert on compositeitemi
for each row
when (new.id is null)
	begin select compositeitemiseq.nextval into :new.id from dual;
end


create table unitname(
     id double precision not null unique,
     unitname char(10),
     cancel int not null,
     canceldate date,
     canceluserid double precision,
     entrydate date,
     entryuserid double precision,
     lastchangedate timestamp,
     lastchangeuserid double precision);

create sequence unitnamseq increment by 1 start with 1;
create trigger unitnamtrig before insert on unitnam
for each row
when (new.id is null)
	begin select unitnamseq.nextval into :new.id from dual;
end


create table priceperpriceunit(
     id double precision not null unique,
     itemid double precision not null,
     itemlocationid double precision not null,
     pricelevelid double precision not null,
     price decimal(10,4) not null,
     cancel int not null,
     canceldate date,
     canceluserid double precision,
     entrydate date,
     entryuserid double precision,
     lastchangedate timestamp,
     lastchangeuserid double precision,
     primary key(id));

create sequence priceperpriceuniseq increment by 1 start with 1;
create trigger priceperpriceunitrig before insert on priceperpriceuni
for each row
when (new.id is null)
	begin select priceperpriceuniseq.nextval into :new.id from dual;
end


create table pricediscount(
     id double precision not null unique,
     itemid double precision not null,
     itemlocationid double precision not null,
     quantity decimal(12,4) not null,
     discount decimal(6,3) not null,
     cancel int not null,
     canceldate date,
     canceluserid double precision,
     entrydate date,
     entryuserid double precision,
     lastchangedate timestamp,
     lastchangeuserid double precision,
     primary key(id));

create sequence pricediscounseq increment by 1 start with 1;
create trigger pricediscountrig before insert on pricediscoun
for each row
when (new.id is null)
	begin select pricediscounseq.nextval into :new.id from dual;
end


create table inventorylocation(
     id double precision not null unique,
     companyid double precision not null,
     gencompanyid double precision not null,
     primary key(id));

create sequence inventorylocatioseq increment by 1 start with 1;
create trigger inventorylocatiotrig before insert on inventorylocatio
for each row
when (new.id is null)
	begin select inventorylocatioseq.nextval into :new.id from dual;
end


create table itemlocation(
     id double precision not null unique,
     itemid double precision not null,
     inventorylocationid double precision not null,
     onhandqty double precision not null,
     maxstocklevelseason1 double precision not null,
     minstocklevelseason1 double precision not null,
     orderqtyseason1 double precision not null,
     maxstocklevelseason2 double precision not null,
     minstocklevelseason2 double precision not null,
     orderqtyseason2 double precision not null,
     maxstocklevelseason3 double precision not null,
     minstocklevelseason3 double precision not null,
     orderqtyseason3 double precision not null,
     maxstocklevelseason4 double precision not null,
     minstocklevelseason4 double precision not null,
     orderqtyseason4 double precision not null,
     markupsetid double precision not null,
     firstcost decimal(12,5) not null,
     midcost decimal(12,5) not null,
     lastcost decimal(12,5) not null,
     firstqty double precision not null,
     midqty double precision not null,
     lastqty double precision not null,
     cancel int not null,
     canceldate date,
     canceluserid double precision,
     entrydate date,
     entryuserid double precision,
     lastchangedate timestamp,
     lastchangeuserid double precision,
     primary key(id));

create sequence itemlocatioseq increment by 1 start with 1;
create trigger itemlocatiotrig before insert on itemlocatio
for each row
when (new.id is null)
	begin select itemlocatioseq.nextval into :new.id from dual;
end


create table markupset(
     id double precision not null unique,
     description char(35),
     costbased int not null,
     companyid double precision not null ,
     cancel int not null,
     canceldate date,
     canceluserid double precision,
     entrydate date,
     entryuserid double precision,
     lastchangedate timestamp,
     lastchangeuserid double precision,
     primary key(id));

create sequence markupseseq increment by 1 start with 1;
create trigger markupsetrig before insert on markupse
for each row
when (new.id is null)
	begin select markupseseq.nextval into :new.id from dual;
end


create table markupsetlevel(
     id double precision not null unique,
     pricelevelid double precision not null,
     markupsetid double precision not null,
     markuppercent decimal (6,3) not null,
     cancel int not null,
     canceldate date,
     canceluserid double precision,
     entrydate date,
     entryuserid double precision,
     lastchangedate timestamp,
     lastchangeuserid double precision,
     primary key(id));

create sequence markupsetleveseq increment by 1 start with 1;
create trigger markupsetlevetrig before insert on markupsetleve
for each row
when (new.id is null)
	begin select markupsetleveseq.nextval into :new.id from dual;
end


create table pricelevel(
       id double precision not null unique,
       description char(30) not null,
       companyid double precision not null ,
       cancel int not null,
       canceldate date,
       canceluserid double precision,
       entrydate date,
       entryuserid double precision,
       lastchangedate timestamp,
       lastchangeuserid double precision,
       primary key(id));

create sequence priceleveseq increment by 1 start with 1;
create trigger pricelevetrig before insert on priceleve
for each row
when (new.id is null)
	begin select priceleveseq.nextval into :new.id from dual;
end


create table itemvendor(
       id double precision not null unique,
       vendorid double precision not null,
       itemid double precision not null,
       vordernumber char(30) not null,
       vitemunitnameid double precision not null,
       vitemconversion double precision not null,
       vitemcost1 decimal (10,3) not null,
       vitemqty1 decimal (10,0) not null,
       vitemcost2 decimal(10,3) not null,
       vitemqty2 decimal(10,0) not null,
       vitemcost3 decimal(10,3) not null,
       vitemqty3 decimal(10,0) not null,
       vitemcost4 decimal (10,3) not null,
       cancel int not null,
       canceldate date,
       canceluserid double precision,
       entrydate date,
       entryuserid double precision not null,
       lastchangedate timestamp,
       lastchangeuserid double precision,
       primary key(id));

create sequence itemvendoseq increment by 1 start with 1;
create trigger itemvendotrig before insert on itemvendo
for each row
when (new.id is null)
	begin select itemvendoseq.nextval into :new.id from dual;
end


create table invpo(
       id double precision not null unique,
       vendorid double precision not null,
       ponumber char(20) not null,
       duedate date,
       locationid double precision not null,
       carrierserviceid double precision,
       tracknumber char(50),
       contact char(20),
       requisition char(20),
       ordernumber char(20),
       gencompanyid double precision not null,
       complete int not null,
       cancel int not null,
       canceldate date,
       canceluserid double precision,
       entrydate date,
       entryuserid double precision,
       lastchangedate timestamp,
       lastchangeuserid double precision,
       primary key(id));

create sequence invpseq increment by 1 start with 1;
create trigger invptrig before insert on invp
for each row
when (new.id is null)
	begin select invpseq.nextval into :new.id from dual;
end


create table invpodetail(
       id double precision not null unique,
       invpoid double precision not null,
       itemid double precision not null,
       itemqty decimal(10,2) not null,
       itemprice decimal(10,3) not null,
       primary key(id));

create sequence invpodetaiseq increment by 1 start with 1;
create trigger invpodetaitrig before insert on invpodetai
for each row
when (new.id is null)
	begin select invpodetaiseq.nextval into :new.id from dual;
end


create table invpoquote(
       id double precision not null unique,
       invpoid double precision not null,
       vendorid double precision not null,
       invpodetailid double precision not null,
       itemqty decimal (10,2) not null,
       itemprice decimal(10,3) not null,
       primary key(id));

create sequence invpoquotseq increment by 1 start with 1;
create trigger invpoquottrig before insert on invpoquot
for each row
when (new.id is null)
	begin select invpoquotseq.nextval into :new.id from dual;
end


create table invreceive(
       id double precision not null unique,
       recsource int not null,
       invpoid double precision not null,
       receivedate date,
       itemid double precision not null,
       vendorid double precision not null,
       locationid double precision not null,
       itemqty decimal(10,2) not null,
       itemprice decimal(10,3) not null,
       itemqtyused decimal(10,2) not null,
       conversion decimal(8,4) not null,
       track char(30),
       receiveunitnameid double precision not null,
       passtoap int not null,
       apbillid double precision not null,
       gencompanyid double precision not null,
       cancel int not null,
       canceldate date,
       canceluserid double precision,
       entrydate date,
       entryuserid double precision,
       lastchangedate timestamp,
       lastchangeuserid double precision,
       primary key(id));

create sequence invreceivseq increment by 1 start with 1;
create trigger invreceivtrig before insert on invreceiv
for each row
when (new.id is null)
	begin select invreceivseq.nextval into :new.id from dual;
end


create table vendor(
     id double precision not null unique,
     paytocompanyid double precision not null,
     orderfromcompanyid double precision not null,
     orderfromname char(30),
     paytermsid double precision not null,
     paynone int not null,
    ,
    ,
     customeraccount char(20),
     companyid double precision not null,
     cancel int not null,
     canceldate date,
     canceluserid double precision,
     entrydate date,
     entryuserid double precision,
     lastchangedate timestamp,
     lastchangeuserid double precision,
     primary key(id));

create sequence vendoseq increment by 1 start with 1;
create trigger vendotrig before insert on vendo
for each row
when (new.id is null)
	begin select vendoseq.nextval into :new.id from dual;
end


create table company(
     id double precision not null unique,
     companyname char(35) not null,
     address1 char(100),
     address2 char(100),
     mailstop char(20),
     city char(50),
     state char(20),
     zip char(15),
     country char(20),
     phone1 char(20),
     phone1comment char(20),
     phone2 char(20),
     phone2comment char(20),
     phone3 char(20),
     phone3comment char(20),
     phone4 char(20),
     phone4comment char(20),
     email1 char(50),
     email1comment char(20),
     email2 char(50),
     email2comment char(20),
     website char(100),
     federalid char(20),
     cancel int not null,
     canceldate date,
     canceluserid double precision,
     entrydate date,
     entryuserid double precision,
     lastchangedate timestamp,
     lastchangeuserid double precision);

create sequence companseq increment by 1 start with 1;
create trigger compantrig before insert on compan
for each row
when (new.id is null)
	begin select companseq.nextval into :new.id from dual;
end


create table customer(
     id double precision not null unique,
     companyid double precision not null,
     taxexemptid double precision not null,
     creditlimit double precision not null,
     salesglacctid double precision not null,
     salesmanid double precision not null,
     servicerepid double precision not null,
     invoicetermsid double precision not null,
     quotecommentid double precision not null,
     interest int not null,
     billtoattnname char(30),
     quoteattnname char(30),
     chargecode char(30),
     salestaxnum char(30),
     gencompanyid double precision not null,
     cancel int,
     canceldate date,
     canceluserid double precision,
     entrydate date,
     entryuserid double precision,
     lastchangedate timestamp,
     lastchangeuserid double precision,
     primary key(id));

create sequence customeseq increment by 1 start with 1;
create trigger custometrig before insert on custome
for each row
when (new.id is null)
	begin select customeseq.nextval into :new.id from dual;
end


create table shipto(
     id double precision not null unique,
     companyid double precision not null,
     shiptocompanyid double precision not null unique,
    ,
     cancel int not null,
     canceldate date,
     canceluserid double precision,
     entrydate date,
     entryuserid double precision,
     lastchangedate timestamp,
     lastchangeuserid double precision,
     primary key(id));

create sequence shiptseq increment by 1 start with 1;
create trigger shipttrig before insert on shipt
for each row
when (new.id is null)
	begin select shiptseq.nextval into :new.id from dual;
end


create table salesman(
     id double precision not null unique,
     companyid double precision not null,
     payrollid double precision not null,
     commissionrate decimal(6,3) not null,
     commissionbase int not null,
     servicerep int not null,
     salesman int not null,
     gencompanyid double precision not null ,
     cancel int not null,
     canceldate date,
     canceluserid double precision,
     entrydate date,
     entryuserid double precision,
     lastchangedate timestamp,
     lastchangeuserid double precision,
     primary key(id));

create sequence salesmaseq increment by 1 start with 1;
create trigger salesmatrig before insert on salesma
for each row
when (new.id is null)
	begin select salesmaseq.nextval into :new.id from dual;
end


create table quotecomment(
     id double precision not null unique,
     comments char(100) not null,
     cancel int,
     canceldate date,
     canceluserid double precision,
     entrydate date,
     entryuserid double precision,
     lastchangedate timestamp,
     lastchangeuserid double precision);

create sequence quotecommenseq increment by 1 start with 1;
create trigger quotecommentrig before insert on quotecommen
for each row
when (new.id is null)
	begin select quotecommenseq.nextval into :new.id from dual;
end


create table invoiceterms(
     id double precision not null unique,
     verbal char(30),
     discountpercent decimal(6,3) not null,
     discountdays int not null,
     discountdayofmonth int not null,
     netduedays int not null,
     ar int not null,
     ap int not null,
     cancel int not null,
     canceldate date,
     canceluserid double precision,
     entrydate date,
     entryuserid double precision,
     lastchangedate timestamp,
     lastchangeuserid double precision,
     primary key(id));

create sequence invoicetermseq increment by 1 start with 1;
create trigger invoicetermtrig before insert on invoiceterm
for each row
when (new.id is null)
	begin select invoicetermseq.nextval into :new.id from dual;
end


create table salestax(
     id double precision not null unique,
     taxname char(30),
     taxrate decimal(7,4) not null,
     taxbase int not null,
     cancel int not null,
     canceldate date,
     canceluserid double precision,
     entrydate date,
     entryuserid double precision,
     lastchangedate timestamp,
     lastchangeuserid double precision);

create sequence salestaseq increment by 1 start with 1;
create trigger salestatrig before insert on salesta
for each row
when (new.id is null)
	begin select salestaseq.nextval into :new.id from dual;
end


create table customersalestax(
     id double precision not null unique,
     customerid double precision not null,
     salestaxid double precision,
     primary key(id));

create sequence customersalestaseq increment by 1 start with 1;
create trigger customersalestatrig before insert on customersalesta
for each row
when (new.id is null)
	begin select customersalestaseq.nextval into :new.id from dual;
end


create table taxexempt(
     id double precision not null unique,
     exemptname char(30),
     cancel int not null,
     canceldate date,
     canceluserid double precision,
     entrydate date,
     entryuserid double precision,
     lastchangedate timestamp,
     lastchangeuserid double precision);

create sequence taxexempseq increment by 1 start with 1;
create trigger taxexemptrig before insert on taxexemp
for each row
when (new.id is null)
	begin select taxexempseq.nextval into :new.id from dual;
end


create table arorder(
       id double precision not null unique,
       ordernumber double precision not null,
       ponumber char(30),
       orderbycompanyid double precision not null,
       shiptocompanyid double precision not null,
       status int not null,
       customerbillcode char(20),
       companyid double precision not null,
       pricelevelid double precision not null,
       inventorylocationid double precision not null,
       cancel int not null,
       canceldate date,
       canceluserid double precision,
       entrydate date,
       entryuserid double precision,
       duedate date,
       lastchangedate timestamp,
       lastchangeuserid double precision,
       primary key(id));

create sequence arordeseq increment by 1 start with 1;
create trigger arordetrig before insert on arorde
for each row
when (new.id is null)
	begin select arordeseq.nextval into :new.id from dual;
end


create table arordernotes (
       orderid double precision not null unique,
       note blob,
       lastchangedate timestamp,
       lastchangeuserid double precision not null,
       primary key(orderid));


create table arorderdetail (
       id double precision not null unique,
       orderid double precision not null,
       itemid double precision not null,
       linenumber int not null,
       qtyorder double precision not null,
       qtyship double precision not null,
       qtybill double precision not null,
       glaccountid double precision not null,
       taxflag int not null,
       costeach decimal(10,4),
       priceach decimal(10,4),
       entrydate date,
       entryuserid double precision not null,
       lastchangedate timestamp,
       lastchangeuserid double precision not null,
       primary key(id));

create sequence arorderdetailseq increment by 1 start with 1;
create trigger arorderdetailtrig before insert on arorderdetail
for each row
when (new.id is null)
	begin select arorderdetailseq.nextval into :new.id from dual;
end


create table arordershippackage (
     id double precision not null unique,
     ordershipid double precision not null,
     weight double precision not null,
     cost double precision not null,
     tracknumber char(50) not null,
     arinvoiceid double precision not null,
     primary key(id));

create sequence arordershippackageseq increment by 1 start with 1;
create trigger arordershippackagetrig before insert on arordershippackage
for each row
when (new.id is null)
	begin select arordershippackageseq.nextval into :new.id from dual;
end


create table arordershipdetail (
       id double precision not null unique,
       ordershipid double precision not null,
       orderdetailid double precision not null,
       shipqty double precision not null,
       entrydate date,
       entryuserid double precision not null,
       lastchangedate timestamp,
       lastchangeuserid double precision not null,
       primary key(id));

create sequence arordershipdetailseq increment by 1 start with 1;
create trigger arordershipdetailtrig before insert on arordershipdetail
for each row
when (new.id is null)
	begin select arordershipdetailseq.nextval into :new.id from dual;
end


create table arordership (
       id double precision not null unique,
       orderid double precision not null,
       carrierserviceid double precision not null,
       shipdate date,
       locationid double precision not null,
       entrydate date,
       entryuserid double precision not null,
       lastchangedate timestamp,
       lastchangeuserid double precision not null,
       primary key(id));

create sequence arordershipseq increment by 1 start with 1;
create trigger arordershiptrig before insert on arordership
for each row
when (new.id is null)
	begin select arordershipseq.nextval into :new.id from dual;
end


create table arordertax (
       id double precision not null unique,
       orderid double precision not null,
       taxrateid double precision not null,
       tax decimal(12,2),
       entrydate date,
       entryuserid double precision not null,
       lastchangedate timestamp,
       lastchangeuserid double precision not null,
       primary key(id));

create sequence arordertaxseq increment by 1 start with 1;
create trigger arordertaxtrig before insert on arordertax
for each row
when (new.id is null)
	begin select arordertaxseq.nextval into :new.id from dual;
end


create table arordertrack (
       id double precision not null unique,
       orderid double precision not null,
       action int not null,
       trackdate date,
       trackuserid double precision not null,
       lastchangedate timestamp,
       lastchangeuserid double precision not null,
       primary key (id));

create sequence arordertrackseq increment by 1 start with 1;
create trigger arordertracktrig before insert on arordertrack
for each row
when (new.id is null)
	begin select arordertrackseq.nextval into :new.id from dual;
end


create table carrier (
     id double precision not null unique,
     companyid double precision not null unique,
     customernumber char(20),
     trackingurlbase char(150),
     trackingurlvarname char(30),
     primary key(id));

create sequence carrierseq increment by 1 start with 1;
create trigger carriertrig before insert on carrier
for each row
when (new.id is null)
	begin select carrierseq.nextval into :new.id from dual;
end


create table carrierservice (
     id double precision not null unique,
     carrierid double precision not null,
     description char(30) not null,
     primary key(id));

create sequence carrierserviceseq increment by 1 start with 1;
create trigger carrierservicetrig before insert on carrierservice
for each row
when (new.id is null)
	begin select carrierserviceseq.nextval into :new.id from dual;
end


create table arcompany (
     id double precision not null unique,
     imageurl char(150),
     cash double precision not null,
     checking double precision not null,
     interest double precision not null,
     discount double precision not null,
     cost double precision not null,
     inventory double precision not null,
     shipliability double precision not null,
     primary key(id));


Create table glpie(
     id double precision unique not null,
     name char(30) not null,
     description char(100),
     begindate date,
     findate date,
          cancel int not null,
          canceldate date,
         canceluserid double precision,
          entrydate date,
          entryuserid double precision,
          lastchangedate timestamp,
          lastchangeuserid double precision,
     primary key(id));

create sequence glpiseq increment by 1 start with 1;
create trigger glpitrig before insert on glpi
for each row
when (new.id is null)
	begin select glpiseq.nextval into :new.id from dual;
end


Create table glpieslice(
     id double precision not null unique,
     glpieid double precision not null,
     name char(30),
     begindate date,
     findate date,
          lastchangedate timestamp,
          lastchangeuserid double precision not null,
     primary key(id));

create sequence glpieslicseq increment by 1 start with 1;
create trigger glpieslictrig before insert on glpieslic
for each row
when (new.id is null)
	begin select glpieslicseq.nextval into :new.id from dual;
end


create table glpieslicedetail(
     id double precision not null unique,
     glpiesliceid double precision not null,
     glaccountid double precision not null,
     companyid double precision not null,
          lastchangedate timestamp,
          lastchangeuserid double precision not null,
     primary key(id));

create sequence glpieslicedetaiseq increment by 1 start with 1;
create trigger glpieslicedetaitrig before insert on glpieslicedetai
for each row
when (new.id is null)
	begin select glpieslicedetaiseq.nextval into :new.id from dual;
end


create table invcompany (
     id double precision not null unique,
     cash double precision not null,
     sales double precision not null,
     loss double precision not null,
     cost double precision not null,
     freight double precision not null,
     tax double precision not null,
     primary key(id));


create table extuser (
     id double precision not null unique,
     name char(30) not null,
     password char(50) not null,
     customer int not null,
     vendor int not null,
     cancel int not null,
     stylesheetid int not null,
     canceldate date,
     canceluserid double precision,
     entrydate date,
     entryuserid double precision,
     lastchangedate timestamp,
     lastchangeuserid double precision,
     primary key(id));

create sequence extuserseq increment by 1 start with 1;
create trigger extusertrig before insert on extuser
for each row
when (new.id is null)
	begin select extuserseq.nextval into :new.id from dual;
end


create table arinvoice(
       id double precision not null unique,
       invoicenumber char(20) not null,
       ponumber char(30),
       wherefrom int not null,
       orderid double precision not null,
       orderbycompanyid double precision not null,
       shiptocompanyid double precision not null,
       status int not null,
       customerbillcode char(20),
       companyid double precision not null,
       shipcost decimal (10,2),
       invoicetotal decimal (12,2),
       prepaidamount decimal (12,2),
       duedate date,
       discountdate date,
       discountamount decimal(12,2),
       accruedinterest decimal(12,2),
       datelastinterestcalc date,
       cancel int not null,
       canceldate date,
       canceluserid double precision,
       entrydate date,
       entryuserid double precision,
       lastchangedate timestamp,
       lastchangeuserid double precision,
       primary key(id));

create sequence arinvoicseq increment by 1 start with 1;
create trigger arinvoictrig before insert on arinvoic
for each row
when (new.id is null)
	begin select arinvoicseq.nextval into :new.id from dual;
end


create table arinvoicenotes (
       invoiceid double precision not null unique,
       note blob,
       lastchangedate timestamp,
       lastchangeuserid double precision not null,
       primary key(invoiceid));


create table arinvoicedetail (
       id double precision not null unique,
       invoiceid double precision not null,
       itemcode char(20) not null,
       description char(100) not null,
       linenumber int not null,
       qty double precision not null,
       qtyunitnameid int not null,
       glaccountid double precision not null,
       taxflag int not null,
       costtotal decimal(10,4),
       costglaccountid double precision not null,
       priceach decimal(10,4),
       priceunitnameid int not null,
       qtyunitperpriceunit decimal (10,4),
       totalprice decimal (10,2),
       entrydate date,
       entryuserid double precision,
       lastchangedate timestamp,
       lastchangeuserid double precision,
       primary key(id));

create sequence arinvoicedetailseq increment by 1 start with 1;
create trigger arinvoicedetailtrig before insert on arinvoicedetail
for each row
when (new.id is null)
	begin select arinvoicedetailseq.nextval into :new.id from dual;
end


create table invoicetaxdetail(
     id double precision  not null unique,
     invoiceid double precision not null,
     taxid double precision not null,
     taxamount decimal (12,2),
     primary key(id));

create sequence invoicetaxdetaiseq increment by 1 start with 1;
create trigger invoicetaxdetaitrig before insert on invoicetaxdetai
for each row
when (new.id is null)
	begin select invoicetaxdetaiseq.nextval into :new.id from dual;
end


create table invoicepaymentdetail(
     id double precision  not null unique,
     invoiceid double precision not null,
     amount decimal (12,2),
     voucherid double precision not null,
     datereceived date,
     primary key(invoiceid));

create sequence invoicepaymentdetaiseq increment by 1 start with 1;
create trigger invoicepaymentdetaitrig before insert on invoicepaymentdetai
for each row
when (new.id is null)
	begin select invoicepaymentdetaiseq.nextval into :new.id from dual;
end


create table zipcode(
     zip char(10) not null,
     state char(10) not null,
     city char(50) not null,
     longitude double precision,
     latitude double precision,
     key(zip));


create table apcompany (
     id double precision not null unique,
     payable double precision not null,
     interestexpense double precision not null,
     discount double precision not null,
     discearn int not null,
     usetransactiondate int not null,
     key(id));


create table checkacct(
       id double precision not null unique,
       name char(30),
       glaccountid double precision not null,
       lastchecknumberused decimal(20),
      ,
       gencompanyid double precision not null,
       ap int not null,
       pay int not null,
       primary key(id));

create sequence checkaccseq increment by 1 start with 1;
create trigger checkacctrig before insert on checkacc
for each row
when (new.id is null)
	begin select checkaccseq.nextval into :new.id from dual;
end


create table apbill(
     id double precision not null unique,
     invoicenumber char(20) not null,
     cancel int not null,
     complete int not null,
     paynone int not null,
     total decimal(12,2) not null,
     description char(50),
     dateofinvoice date,
     duedate date,
     discountamount decimal(12,2) not null,
     discountdate date,
     vendorid double precision not null,
     comment blob,
     gencompanyid double precision not null,
     wherefrom int not null,
     entrydate date,
     entryuserid double precision,
     lastchangedate timestamp,
     lastchangeuserid double precision,
     canceldate date not null,
     canceluserid double precision not null,
     primary key(id));

create sequence apbilseq increment by 1 start with 1;
create trigger apbiltrig before insert on apbil
for each row
when (new.id is null)
	begin select apbilseq.nextval into :new.id from dual;
end


create table apbilldetail(
     id double precision not null unique,
     apbillid double precision not null,
     amount decimal(12,2) not null,
     glaccountid double precision not null,
     invreceiveid double precision not null,
     primary key(id));

create sequence apbilldetaiseq increment by 1 start with 1;
create trigger apbilldetaitrig before insert on apbilldetai
for each row
when (new.id is null)
	begin select apbilldetaiseq.nextval into :new.id from dual;
end


create table apbillpayment(
     id double precision not null unique,
     apbillid double precision not null,
     amount decimal(12,2) not null,
     checkid double precision not null,
     entrydate date,
     entryuserid double precision,
     lastchangedate timestamp,
     lastchangeuserid double precision,
     primary key(id));

create sequence apbillpaymenseq increment by 1 start with 1;
create trigger apbillpaymentrig before insert on apbillpaymen
for each row
when (new.id is null)
	begin select apbillpaymenseq.nextval into :new.id from dual;
end


create table check(
       id double precision not null unique,
       wherefrom int not null,
       paytype int not null,
       checkdate date,
       checkvoid int not null,
       amount decimal(12,2) not null,
       checkaccountid double precision not null,
       checknumber decimal(15),
       cashdate date not null,
       entrydate date,
       entryuserid double precision,
       lastchangedate timestamp,
       lastchangeuserid double precision,
       primary key(id));

create sequence checseq increment by 1 start with 1;
create trigger chectrig before insert on chec
for each row
when (new.id is null)
	begin select checseq.nextval into :new.id from dual;
end


create table docmgmtcategory (
     id double precision not null,
     name char(255) not null,
     primary key(id));

create sequence docmgmtcategoryseq increment by 1 start with 1;
create trigger docmgmtcategorytrig before insert on docmgmtcategory
for each row
when (new.id is null)
	begin select docmgmtcategoryseq.nextval into :new.id from dual;
end


create table docmgmtdata (
     id double precision not null,
     category double precision not null,
     owner double precision not null,
     realname char(255) not null,
     created date not null,
     description char(255),
     itemid double precision not null,
     comment blob,
     status double precision not null,
     primary key(id));

create sequence docmgmtdataseq increment by 1 start with 1;
create trigger docmgmtdatatrig before insert on docmgmtdata
for each row
when (new.id is null)
	begin select docmgmtdataseq.nextval into :new.id from dual;
end


create table docmgmtperms (
     fid double precision not null,
     uid double precision not null,
     rights double precision not null,
     primary key(fid));


create table docmgmtlog (
     id double precision not null,
     modified_on date not null,
     modified_by double precision not null,
     note blob,
     primary key(id));


create table premployee (
     id double precision not null unique,
     companyid double precision not null,
     firstname char(30),
     lastname char(30),
     ssnumber char(11),
     dateofbirth date not null,
     hiredate date not null,
     terminatedate date not null,
     lastreviewid double precision not null,
     paytype int not null,
     payperiod int not null,
     payperperiod decimal(10,2) not null,
     lastpaychangedate date not null,
     glaccountid double precision not null,
     maritalstatus int not null,
     federalexemptions int not null,
     extrafitperpayperiod decimal(10,2) not null,
     extrafitbasedon int not null,
     eic int not null,
     prstateid double precision not null,
     stateexemptions int not null,
     extrasitperpayperiod decimal(10,2) not null,
     extrasitbasedon int not null,
     prlocalid double precision not null,
     localexemptions int not null,
     extralitperpayperiod decimal(10,2) not null,
     extralitbasedon int not null,
     prcityid double precision not null,
     cityexemptions int not null,
     extracitperpayperiod decimal(10,2) not null,
     extracitbasedon int not null,
     workmanscomprate decimal(10,6) not null,
     pensplanid1 double precision not null,
     pensplandedamount1 decimal(10,4) not null,
     pensplanbase1 int not null,
     pensplanid2 double precision not null,
     pensplandedamount2 decimal(10,4) not null,
     pensplanbase2 int not null,
     vacationhoursaccrued decimal(10,2) not null,
     sickleavehoursaccrued decimal(10,2) not null,
     prdedgroupid double precision not null,
     status int not null,
     gencompanyid double precision not null,
     cancel int not null,
     canceldate date not null,
     canceluserid double precision not null,
     entrydate date not null,
     entryuserid double precision not null,
     lastchangedate timestamp,
     lastchangeuserid double precision not null,
     primary key(id));

create sequence premployeeseq increment by 1 start with 1;
create trigger premployeetrig before insert on premployee
for each row
when (new.id is null)
	begin select premployeeseq.nextval into :new.id from dual;
end


create table prempldeduction (
     id double precision not null unique,
     employeeid double precision not null,
     description char(50),
     amountperperiod decimal(10,4),
     glaccountid double precision not null,
     periodsremain int not null,
     cancel int not null,
     canceldate date not null,
     canceluserid double precision not null,
     entrydate date not null,
     entryuserid double precision not null,
     lastchangedate timestamp,
     lastchangeuserid double precision not null,
     primary key(id));

create sequence prempldeductionseq increment by 1 start with 1;
create trigger prempldeductiontrig before insert on prempldeduction
for each row
when (new.id is null)
	begin select prempldeductionseq.nextval into :new.id from dual;
end


create table premplweek (
     id double precision not null unique,
     employeeid double precision not null,
     periodbegindate date not null,
     periodenddate date not null,
     prperiodid double precision not null,
     tipspay decimal(10,2) not null,
     tipsaswages decimal(10,2) not null,
     misctaxablepay decimal(10,2) not null,
     misctaxablecomment char(30),
     miscnontaxablepay decimal(10,2) not null,
     miscnontaxablecomment char(30),
     vacaccrue decimal(5,2) not null,
     sickaccrue decimal(5,2) not null,
     federaltax decimal(10,2) not null,
     ficatax decimal(10,2) not null,
     statetax decimal(10,2) not null,
     localtax decimal(10,2) not null,
     citytax decimal(10,2) not null,
     prstateid double precision not null,
     prcityid double precision not null,
     prlocalid double precision not null,
     eiccredit decimal(10,2) not null,
     miscdeduction decimal(10,2) not null,
     miscdeductioncomment char(30),
     medicarededuction decimal(10,2) not null,
     calculatestatus int not null,
     fuitax decimal(10,2) not null,
     cficatax decimal(10,2) not null,
     cmedicarededuction decimal(10,2) not null,
     suitax decimal(10,2) not null,
     netpay decimal(10,2) not null,
     checkid double precision not null,
     cancel int not null,
     canceldate date not null,
     canceluserid double precision not null,
     entrydate date not null,
     entryuserid double precision not null,
     lastchangedate timestamp,
     lastchangeuserid double precision not null,
     primary key(id));

create sequence premplweekseq increment by 1 start with 1;
create trigger premplweektrig before insert on premplweek
for each row
when (new.id is null)
	begin select premplweekseq.nextval into :new.id from dual;
end


create table premplweekpaydetail (
     id double precision not null unique,
     premplweekid double precision not null,
     prpaytypeid int not null,
     prbendedid double precision not null,
     qty decimal(8,2) not null,
     rate decimal(8,4) not null,
     amount decimal(10,2) not null,
     glaccountid double precision not null,
     primary key(id));

create sequence premplweekpaydetailseq increment by 1 start with 1;
create trigger premplweekpaydetailtrig before insert on premplweekpaydetail
for each row
when (new.id is null)
	begin select premplweekpaydetailseq.nextval into :new.id from dual;
end


create table prpaytype (
     id double precision not null unique,
     name char(4) not null,
     description char(40) not null,
     multiplier decimal(5,2) not null,
     vacation int not null,
     sick int not null,
     gencompanyid double precision not null,
     primary key(id));

create sequence prpaytypeseq increment by 1 start with 1;
create trigger prpaytypetrig before insert on prpaytype
for each row
when (new.id is null)
	begin select prpaytypeseq.nextval into :new.id from dual;
end


create table premplweekdeddetail (
     id double precision not null unique,
     premplweekid double precision not null,
     prempldeductionid double precision not null,
     prbendedid double precision not null,
     prpensionid double precision not null,
     amount decimal(10,2) not null,
     dedtype int not null,
     primary key(id));

create sequence premplweekdeddetailseq increment by 1 start with 1;
create trigger premplweekdeddetailtrig before insert on premplweekdeddetail
for each row
when (new.id is null)
	begin select premplweekdeddetailseq.nextval into :new.id from dual;
end


create table prperiod (
     id double precision not null unique,
     numperyear int not null,
     name char(15) not null,
     primary key (id));

create sequence prperiodseq increment by 1 start with 1;
create trigger prperiodtrig before insert on prperiod
for each row
when (new.id is null)
	begin select prperiodseq.nextval into :new.id from dual;
end


create table prpaychange (
       id double precision not null unique,
       employeeid double precision not null,
       oldpay decimal (10,2),
       newpay decimal (10,2),
       paystartdate date not null,
       lastchangedate timestamp,
       lastchangeuserid double precision not null,
       primary key(id));

create sequence prpaychangeseq increment by 1 start with 1;
create trigger prpaychangetrig before insert on prpaychange
for each row
when (new.id is null)
	begin select prpaychangeseq.nextval into :new.id from dual;
end


create table practive (
       id double precision not null unique,
       employeeid double precision not null,
       status int not null,
       statuschangedate date not null,
       lastchangedate timestamp,
       lastchangeuserid double precision not null,
       primary key(id));

create sequence practiveseq increment by 1 start with 1;
create trigger practivetrig before insert on practive
for each row
when (new.id is null)
	begin select practiveseq.nextval into :new.id from dual;
end


create table premplreview (
     id double precision not null unique,
     employeeid double precision not null,
     evaluatorname char(50),
     evaldate date not null,
     premplreviewratingid double precision not null,
     comments blob,
     cancel int not null,
     canceldate date not null,
     canceluserid double precision not null,
     entrydate date not null,
     entryuserid double precision not null,
     lastchangedate timestamp,
     lastchangeuserid double precision not null,
     primary key(id));

create sequence premplreviewseq increment by 1 start with 1;
create trigger premplreviewtrig before insert on premplreview
for each row
when (new.id is null)
	begin select premplreviewseq.nextval into :new.id from dual;
end


create table premplreviewrating (
    id double precision not null unique,
    description char(50) not null,
    primary key(id));

create sequence premplreviewratingseq increment by 1 start with 1;
create trigger premplreviewratingtrig before insert on premplreviewrating
for each row
when (new.id is null)
	begin select premplreviewratingseq.nextval into :new.id from dual;
end


create table prdepositchecks(
     checkid double precision not null,
     prperiodid double precision not null,
     periodbegindate date not null,
     periodenddate date not null,
     gencompanyid double precision not null,
     primary key(checkid));


create table prcompany (
     id double precision not null unique,
     fedtaxnum char(15) not null,
     w2companyname char(35),
     w2companyaddress1 char(100),
     w2companyaddress2 char(100),
     w2citystatezip char(100),
     stateunemplnum char(15),
     glcheckaccountid double precision not null,
     glfitpayableid double precision not null,
     glficapayableid double precision not null,
     glficaexpenseid double precision not null,
     glfuipayableid double precision not null,
     glfuiexpenseid double precision not null,
     glmedicarepayableid double precision not null,
     glmedicareexpenseid double precision not null,
     glsuipayableid double precision not null,
     glsuiexpenseid double precision not null,
     glmiscdedpayableid double precision not null,
     gltaxexemptexpenseid double precision not null,
     glworkmanscomppayableid double precision not null,
     glworkmanscompexpenseid double precision not null,
     post2payables int not null,
     checkacctid double precision not null,
     shift2multiplier decimal(5,2),
     shift3multiplier decimal(5,2),
     sickleavehrsperyear decimal(5,2),
     maxsickleave decimal(7,2),
     minwagehr decimal(10,2),
     autoprintdeposit int not null,
     depositvendorid double precision not null,
     cancel int not null,
     canceldate date not null,
     canceluserid double precision not null,
     entrydate date not null,
     entryuserid double precision not null,
     lastchangedate timestamp,
     lastchangeuserid double precision not null,
     primary key(id));


create table prcompanyperiod (
       id double precision not null unique,
       prcompanyid double precision not null,
       prperiodid double precision not null,
       maxpayhr decimal(10,2) not null,
       maxgross decimal(10,2) not null,
       primary key(id));

create sequence prcompanyperiodseq increment by 1 start with 1;
create trigger prcompanyperiodtrig before insert on prcompanyperiod
for each row
when (new.id is null)
	begin select prcompanyperiodseq.nextval into :new.id from dual;
end


create table prvacation (
     id double precision not null unique,
     yrsbeforeaccrue decimal(5,2) not null,
     vacdaysperyear decimal(5,2) not null,
     maxaccrue decimal(5,2) not null,
     gencompanyid double precision not null,
     cancel int not null,
     canceldate date not null,
     canceluserid double precision not null,
     entrydate date not null,
     entryuserid double precision not null,
     lastchangedate timestamp,
     lastchangeuserid double precision not null,
     primary key(id));

create sequence prvacationseq increment by 1 start with 1;
create trigger prvacationtrig before insert on prvacation
for each row
when (new.id is null)
	begin select prvacationseq.nextval into :new.id from dual;
end


create table prfederal (
     id double precision not null unique,
     maxwagesfica decimal(10,2) not null,
     employeeficapercent decimal(8,4) not null,
     companyficapercent decimal(8,4) not null,
     maxwagesmedicare decimal(10,2) not null,
     employeemedicarepercent decimal(8,4) not null,
     companymedicarepercent decimal(8,4) not null,
     maxwagesfui decimal(10,2) not null,
     companyfuipercent decimal(8,4) not null,
     eicsinglepercent1 decimal(8,4) not null,
     eicsingleover1 decimal(10,2) not null,
     eicsingletax2 decimal(10,2) not null,
     eicsingleover2 decimal(10,2) not null,
     eicsingletax3 decimal(10,2) not null,
     eicsinglepercent3 decimal(8,4) not null,
     eicsingleover3 decimal(10,2) not null,
     eicmarriedpercent1 decimal(8,4) not null,
     eicmarriedover1 decimal(10,2) not null,
     eicmarriedtax2 decimal(10,2) not null,
     eicmarriedover2 decimal(10,2) not null,
     eicmarriedtax3 decimal(10,2) not null,
     eicmarriedpercent3 decimal(8,4) not null,
     eicmarriedover3 decimal(10,2) not null,
     exemptionallow decimal(10,2) not null,
     gencompanyid double precision not null unique,
     cancel int not null,
     canceldate date not null,
     canceluserid double precision not null,
     entrydate date not null,
     entryuserid double precision not null,
     lastchangedate timestamp,
     lastchangeuserid double precision not null,
     primary key(id));

create sequence prfederalseq increment by 1 start with 1;
create trigger prfederaltrig before insert on prfederal
for each row
when (new.id is null)
	begin select prfederalseq.nextval into :new.id from dual;
end


create table prfederaldetail (
     id double precision not null unique,
     prfederalid double precision not null,
     maritalstatus int not null,
     deductiontable int not null,
     tax decimal(10,2) not null,
     percent decimal(8,4) not null,
     over decimal(10,2) not null,
     primary key(id));

create sequence prfederaldetailseq increment by 1 start with 1;
create trigger prfederaldetailtrig before insert on prfederaldetail
for each row
when (new.id is null)
	begin select prfederaldetailseq.nextval into :new.id from dual;
end


create table genstate (
     id double precision not null unique,
     stateinit char(3) unique not null,
     statename char(30),
     primary key(id));

create sequence genstateseq increment by 1 start with 1;
create trigger genstatetrig before insert on genstate
for each row
when (new.id is null)
	begin select genstateseq.nextval into :new.id from dual;
end


create table prstate (
     id double precision not null unique,
     gencompanyid double precision not null,
     genstateid double precision not null,
     taxnum char(20),
     suipercent decimal(8,4) not null,
     suimax decimal(10,2) not null,
     deductfed int not null,
     feddeductmax decimal(10,2) not null,
     exemptyr1 decimal(10,2) not null,
     exemptyr2 decimal(10,2) not null,
     exemptyr3 decimal(10,2) not null,
     exemptyr4 decimal(10,2) not null,
     glacctid double precision not null,
     vendorid double precision not null,
     maxexemptpercent decimal(8,4),
     maxexemptyear decimal(10,2),
     taxcreditexempt1 decimal(10,2) not null,
     taxcreditexempt2 decimal(10,2) not null,
     taxcreditexempt3 decimal(10,2) not null,
     taxcreditexempt4 decimal(10,2) not null,
     cancel int not null,
     canceldate date not null,
     canceluserid double precision not null,
     entrydate date not null,
     entryuserid double precision not null,
     lastchangedate timestamp,
     lastchangeuserid double precision not null,
     primary key(id));

create sequence prstateseq increment by 1 start with 1;
create trigger prstatetrig before insert on prstate
for each row
when (new.id is null)
	begin select prstateseq.nextval into :new.id from dual;
end


create table prstatedetail (
     id double precision not null unique,
     prstateid double precision not null,
     maritalstatus int not null,
     deductiontable int not null,
     tax decimal(10,2) not null,
     percent decimal(8,4) not null,
     over decimal(10,2) not null,
     primary key(id));

create sequence prstatedetailseq increment by 1 start with 1;
create trigger prstatedetailtrig before insert on prstatedetail
for each row
when (new.id is null)
	begin select prstatedetailseq.nextval into :new.id from dual;
end


create table prlocal (
     id double precision not null unique,
     gencompanyid double precision not null,
     abrev char(3) not null,
     name char(30) not null,
     taxnum char(20) not null,
     deductfed int not null,
     feddeductmax decimal(10,2) not null,
     exemptyr1 decimal(10,2) not null,
     exemptyr2 decimal(10,2) not null,
     exemptyr3 decimal(10,2) not null,
     exemptyr4 decimal(10,2) not null,
     glacctid double precision not null,
     vendorid double precision not null,
     maxexemptpercent decimal(5,2) not null,
     maxexemptyear decimal(10,2) not null,
     taxcreditexempt1 decimal(10,2) not null,
     taxcreditexempt2 decimal(10,2) not null,
     taxcreditexempt3 decimal(10,2) not null,
     taxcreditexempt4 decimal(10,2) not null,
     cancel int not null,
     canceldate date not null,
     canceluserid double precision not null,
     entrydate date not null,
     entryuserid double precision not null,
     lastchangedate timestamp,
     lastchangeuserid double precision not null,
     primary key(id));

create sequence prlocalseq increment by 1 start with 1;
create trigger prlocaltrig before insert on prlocal
for each row
when (new.id is null)
	begin select prlocalseq.nextval into :new.id from dual;
end


create table prlocaldetail (
     id double precision not null unique,
     prlocalid double precision not null,
     maritalstatus int not null,
     deductiontable int not null,
     tax decimal(10,2) not null,
     percent decimal(8,4) not null,
     over decimal(10,2) not null,
     primary key(id));

create sequence prlocaldetailseq increment by 1 start with 1;
create trigger prlocaldetailtrig before insert on prlocaldetail
for each row
when (new.id is null)
	begin select prlocaldetailseq.nextval into :new.id from dual;
end


create table prcity (
     id double precision not null unique,
     gencompanyid double precision not null,
     abrev char(3) not null,
     name char(30) not null,
     taxnum char(20) not null,
     deductfed int not null,
     feddeductmax decimal(10,2) not null,
     exemptyr1 decimal(10,2) not null,
     exemptyr2 decimal(10,2) not null,
     exemptyr3 decimal(10,2) not null,
     exemptyr4 decimal(10,2) not null,
     glacctid double precision not null,
     vendorid double precision not null,
     maxexemptpercent decimal(5,2) not null,
     maxexemptyear decimal(10,2) not null,
     taxcreditexempt1 decimal(10,2) not null,
     taxcreditexempt2 decimal(10,2) not null,
     taxcreditexempt3 decimal(10,2) not null,
     taxcreditexempt4 decimal(10,2) not null,
     cancel int not null,
     canceldate date not null,
     canceluserid double precision not null,
     entrydate date not null,
     entryuserid double precision not null,
     lastchangedate timestamp,
     lastchangeuserid double precision not null,
     primary key(id));

create sequence prcityseq increment by 1 start with 1;
create trigger prcitytrig before insert on prcity
for each row
when (new.id is null)
	begin select prcityseq.nextval into :new.id from dual;
end


create table prcitydetail (
     id double precision not null unique,
     prcityid double precision not null,
     maritalstatus int not null,
     deductiontable int not null,
     tax decimal(10,2) not null,
     percent decimal(8,4) not null,
     over decimal(10,2) not null,
     primary key(id));

create sequence prcitydetailseq increment by 1 start with 1;
create trigger prcitydetailtrig before insert on prcitydetail
for each row
when (new.id is null)
	begin select prcitydetailseq.nextval into :new.id from dual;
end


create table prbended (
     id double precision not null unique,
     gencompanyid double precision not null,
     paytype int not null,
     bendedtype int not null,
     name char(30) not null,
     howfig int not null,
     prdedgroupid double precision not null,
     rate decimal(8,4) not null,
     ceilingperyear decimal(10,2) not null,
     expenseglacctid double precision not null,
     payableglacctid double precision not null,
     vendorid double precision not null,
     cancel int not null,
     canceldate date not null,
     canceluserid double precision not null,
     entrydate date not null,
     entryuserid double precision not null,
     lastchangedate timestamp,
     lastchangeuserid double precision not null,
     primary key(id));

create sequence prbendedseq increment by 1 start with 1;
create trigger prbendedtrig before insert on prbended
for each row
when (new.id is null)
	begin select prbendedseq.nextval into :new.id from dual;
end


create table prdedgroup (
       id double precision not null unique,
       name char(30) not null,
       gencompanyid double precision not null,
       primary key(id));

create sequence prdedgroupseq increment by 1 start with 1;
create trigger prdedgrouptrig before insert on prdedgroup
for each row
when (new.id is null)
	begin select prdedgroupseq.nextval into :new.id from dual;
end


create table prpension (
     id double precision not null unique,
     gencompanyid double precision not null,
     name char(30) not null,
     w2plantype int not null,
     w2plansubtype char(1),
     employercontribhow int not null,
     employercontribute decimal(8,4) not null,
     employermaxmatchpercent decimal(8,4) not null,
     mustbeinplan int not null,
     calcbasis int not null,
     prdedgroupid double precision not null,
     paytype int not null,
     payableglacctid double precision not null,
     expenseglacctid double precision not null,
     federalincometax int not null,
     stateincometax int not null,
     localincometax int not null,
     cityincometax int not null,
     employeefica int not null,
     companyfica int not null,
     fui int not null,
     sui int not null,
     workmanscomp int not null,
     vendorid double precision not null,
     cancel int not null,
     canceldate date not null,
     canceluserid double precision not null,
     entrydate date not null,
     entryuserid double precision not null,
     lastchangedate timestamp,
     lastchangeuserid double precision not null,
     primary key(id));

create sequence prpensionseq increment by 1 start with 1;
create trigger prpensiontrig before insert on prpension
for each row
when (new.id is null)
	begin select prpensionseq.nextval into :new.id from dual;
end


create table estquotecblgenstock (
     id double precision not null unique,
     name char(30) not null,
     gencompanyid double precision not null,
     orderflag int not null,
     cancel int not null,
     canceldate date not null,
     canceluserid double precision not null,
     entrydate date not null,
     entryuserid double precision not null,
     lastchangedate timestamp,
     lastchangeuserid double precision,
     primary key(id));

create sequence estquotecblgenstockseq increment by 1 start with 1;
create trigger estquotecblgenstocktrig before insert on estquotecblgenstock
for each row
when (new.id is null)
	begin select estquotecblgenstockseq.nextval into :new.id from dual;
end


create table estquotecblsubstock (
     id double precision not null unique,
     estquotecblgenstockid double precision not null,
     name char(50) not null,
     weight decimal(6,4) not null,
     turnaround int not null,
     orderflag int not null,
     parts int not null,
     primary key(id));

create sequence estquotecblsubstockseq increment by 1 start with 1;
create trigger estquotecblsubstocktrig before insert on estquotecblsubstock
for each row
when (new.id is null)
	begin select estquotecblsubstockseq.nextval into :new.id from dual;
end


create table estquotecblsubstockcolors (
     id double precision not null unique,
     substockid double precision not null,
     color char(50) not null,
     primary key(id));

create sequence estquotecblsubstockcolorsseq increment by 1 start with 1;
create trigger estquotecblsubstockcolorstrig before insert on estquotecblsubstockcolors
for each row
when (new.id is null)
	begin select estquotecblsubstockcolorsseq.nextval into :new.id from dual;
end


create table estquotecblgenink (
     id double precision not null unique,
     maxcolors int not null,
     regcharge decimal(4,2) not null,
     gencompanyid double precision not null,
     primary key(id));

create sequence estquotecblgeninkseq increment by 1 start with 1;
create trigger estquotecblgeninktrig before insert on estquotecblgenink
for each row
when (new.id is null)
	begin select estquotecblgeninkseq.nextval into :new.id from dual;
end


create table estquotecblink (
     id double precision not null unique,
     name char(50) not null,
     jobprice decimal(6,2) not null,
     mprice decimal(6,4) not null,
     type int not null,
     gencompanyid double precision not null,
     primary key(id));

create sequence estquotecblinkseq increment by 1 start with 1;
create trigger estquotecblinktrig before insert on estquotecblink
for each row
when (new.id is null)
	begin select estquotecblinkseq.nextval into :new.id from dual;
end


create table estquotecblworktype (
       id double precision not null unique,
       name char(50) not null,
       turnaroundqty decimal (10,0) not null,
       turnarounddaysuptoqty int not null,
       turnarounddaysoverqty int not null,
       gencompanyid double precision not null,
       cancel int not null,
       canceldate date not null,
       canceluserid double precision not null,
       entrydate date not null,
       entryuserid double precision not null,
       lastchangedate timestamp,
       lastchangeuserid double precision,
       primary key(id));

create sequence estquotecblworktypeseq increment by 1 start with 1;
create trigger estquotecblworktypetrig before insert on estquotecblworktype
for each row
when (new.id is null)
	begin select estquotecblworktypeseq.nextval into :new.id from dual;
end


create table estquotecblworktypestdqty (
       id double precision not null unique,
       quantity decimal (10,0) not null,
       worktypeid double precision not null,
       primary key(id));

create sequence estquotecblworktypestdqtyseq increment by 1 start with 1;
create trigger estquotecblworktypestdqtytrig before insert on estquotecblworktypestdqty
for each row
when (new.id is null)
	begin select estquotecblworktypestdqtyseq.nextval into :new.id from dual;
end


create table estquotecblworktypeaddl (
       id double precision not null unique,
       worktypeid double precision not null,
       question char(50) not null,
       amount decimal(5,2) not null,
       validreply int not null,
       minreply decimal (10,2) not null,
       maxreply decimal (10,2) not null,
       askonlyifmtblackink int not null,
       askonlyifmtoneink int not null,
       askonlyifmtoneside int not null,
       askonlyminsizelength decimal (10,3) not null,
       askonlyminsizewidth decimal (10,3) not null,
       askonlymaxsizelength decimal (10,3) not null,
       askonlymaxsizewidth decimal (10,3) not null,
       askonlyifoneink int not null,
       calculatehow int not null,
       cancel int not null,
       canceldate date not null,
       canceluserid double precision not null,
       entrydate date not null,
       entryuserid double precision not null,
       lastchangedate timestamp,
       lastchangeuserid double precision,
       primary key(id));

create sequence estquotecblworktypeaddlseq increment by 1 start with 1;
create trigger estquotecblworktypeaddltrig before insert on estquotecblworktypeaddl
for each row
when (new.id is null)
	begin select estquotecblworktypeaddlseq.nextval into :new.id from dual;
end


create table estquotecblstdsize (
       id double precision not null unique,
       length decimal(10,3) not null,
       width decimal(10,3) not null,
       orderflag int not null,
       gencompanyid double precision not null,
       primary key(id));

create sequence estquotecblstdsizeseq increment by 1 start with 1;
create trigger estquotecblstdsizetrig before insert on estquotecblstdsize
for each row
when (new.id is null)
	begin select estquotecblstdsizeseq.nextval into :new.id from dual;
end


create table estquotecblpricelist (
       id double precision not null unique,
       stdsizeid double precision not null,
       worktypeid double precision not null,
       name char(50) not null,
       maxinkside1 int not null,
       maxinkside2 int not null,
       trimchargepermpercut decimal(8,2) not null,
       trimmincharge decimal(8,2) not null,
       qtyperbox decimal(5) not null,
       notes blob not null,
       point2id double precision not null,
       gencompanyid double precision not null,
       cancel int not null,
       canceldate date not null,
       canceluserid double precision not null,
       entrydate date not null,
       entryuserid double precision not null,
       lastchangedate timestamp,
       lastchangeuserid double precision,
       primary key(id));

create sequence estquotecblpricelistseq increment by 1 start with 1;
create trigger estquotecblpricelisttrig before insert on estquotecblpricelist
for each row
when (new.id is null)
	begin select estquotecblpricelistseq.nextval into :new.id from dual;
end


create table estquotecblpriceliststock (
       id double precision not null unique,
       pricelistid double precision not null,
       genstockid double precision not null,
       substockid double precision not null,
       addlchargeperm decimal(6,2) not null,
       addlmincharge decimal (6,2) not null,
       primary key(id));

create sequence estquotecblpriceliststockseq increment by 1 start with 1;
create trigger estquotecblpriceliststocktrig before insert on estquotecblpriceliststock
for each row
when (new.id is null)
	begin select estquotecblpriceliststockseq.nextval into :new.id from dual;
end


create table estquotecblpriceliststockcolors(
       id double precision not null unique,
       priceliststockid double precision not null,
       substockcolorsid double precision not null,
       primary key(id));

create sequence estquotecblpriceliststockcolorseq increment by 1 start with 1;
create trigger estquotecblpriceliststockcolortrig before insert on estquotecblpriceliststockcolor
for each row
when (new.id is null)
	begin select estquotecblpriceliststockcolorseq.nextval into :new.id from dual;
end


create table estquotecblpricelistprice (
       id double precision not null unique,
       pricelistid double precision not null,
       quantity decimal (10,0) not null,
       priceperm decimal (6,2) not null,
       primary key(id));

create sequence estquotecblpricelistpriceseq increment by 1 start with 1;
create trigger estquotecblpricelistpricetrig before insert on estquotecblpricelistprice
for each row
when (new.id is null)
	begin select estquotecblpricelistpriceseq.nextval into :new.id from dual;
end


create table estquotecblbindery (
       id double precision not null unique,
       name char(50) not null,
       gencompanyid double precision not null,
       cancel int not null,
       canceldate date not null,
       canceluserid double precision not null,
       entrydate date not null,
       entryuserid double precision not null,
       lastchangedate timestamp,
       lastchangeuserid double precision,
       primary key(id));

create sequence estquotecblbinderyseq increment by 1 start with 1;
create trigger estquotecblbinderytrig before insert on estquotecblbindery
for each row
when (new.id is null)
	begin select estquotecblbinderyseq.nextval into :new.id from dual;
end


create table estquotecblbinderyaddl (
       id double precision not null unique,
       estquotecblbinderyid double precision not null,
       name char(50) not null,
       question blob not null,
       validreply int not null,
       qtyperpkg decimal(5,0) not null,
       weightpermpkg decimal(5,2) not null,
       turnaround int not null,
       orderflag int not null,
       tooltip char(255) not null,
       cancel int not null,
       canceldate date not null,
       canceluserid double precision not null,
       entrydate date not null,
       entryuserid double precision not null,
       lastchangedate timestamp,
       lastchangeuserid double precision,
       primary key(id));

create sequence estquotecblbinderyaddlseq increment by 1 start with 1;
create trigger estquotecblbinderyaddltrig before insert on estquotecblbinderyaddl
for each row
when (new.id is null)
	begin select estquotecblbinderyaddlseq.nextval into :new.id from dual;
end


create table estquotecblbinderyaddloptions (
       id double precision not null unique,
       binderyaddlid double precision not null,
       prompt char(20) not null,
       numprompt decimal(10,2) not null,
       orderflag int not null,
       primary key(id));

create sequence estquotecblbinderyaddloptionsseq increment by 1 start with 1;
create trigger estquotecblbinderyaddloptionstrig before insert on estquotecblbinderyaddloptions
for each row
when (new.id is null)
	begin select estquotecblbinderyaddloptionsseq.nextval into :new.id from dual;
end


create table estquotecblbinderyaddloptionsize (
       id double precision not null unique,
       binderyaddlid double precision not null,
       binderyaddloptionsid double precision not null,
       estquotecblbinderyaddlsizeid double precision not null,
       amountaddl decimal(5,2) not null,
       setupaddl decimal(5,2) not null,
       minimumaddl decimal(5,2) not null,
       primary key(id));

create sequence estquotecblbinderyaddloptionsizeseq increment by 1 start with 1;
create trigger estquotecblbinderyaddloptionsizetrig before insert on estquotecblbinderyaddloptionsize
for each row
when (new.id is null)
	begin select estquotecblbinderyaddloptionsizeseq.nextval into :new.id from dual;
end


create table estquotecblbinderyworktype (
       estquotecblbinderyid double precision not null,
       estquotecblworktypeid double precision not null,
       key(estquotecblbinderyid));


create table estquotecblbinderyaddlsize (
       id double precision not null unique,
       estquotecblbinderyaddlid double precision not null,
       estquotecblstdsizeid double precision not null,
       amount decimal(5,2) not null,
       setupcharge decimal(5,2) not null,
       minreply decimal (10,2) not null,
       maxreply decimal (10,2) not null,
       minimumcharge decimal(5,2) not null,
       calculatehow int not null,
       maxpass int not null,
       entrydate date not null,
       entryuserid double precision not null,
       lastchangedate timestamp,
       lastchangeuserid double precision,
       primary key(id));

create sequence estquotecblbinderyaddlsizeseq increment by 1 start with 1;
create trigger estquotecblbinderyaddlsizetrig before insert on estquotecblbinderyaddlsize
for each row
when (new.id is null)
	begin select estquotecblbinderyaddlsizeseq.nextval into :new.id from dual;
end


create table estquotecblquote (
       id double precision not null unique,
       customerid double precision not null,
       contactname char(50),
       description char(255),
       stdsizeid double precision not null,
       cwidth decimal(10,3) not null,
       clength decimal(10,3) not null,
       substockid double precision not null,
       substockcolorid double precision not null,
       tightreg1 int not null,
       tightreg2 int not null,
       gencompanyid double precision not null,
       shippingservice char(3),
       shippingzipcode char(12),
       special char(255),
       quotenum char(20),
       cancel int not null,
       canceldate date not null,
       canceluserid double precision not null,
       entrydate date not null,
       entryuserid double precision not null,
       lastchangedate timestamp,
       lastchangeuserid double precision,
       primary key(id));

create sequence estquotecblquoteseq increment by 1 start with 1;
create trigger estquotecblquotetrig before insert on estquotecblquote
for each row
when (new.id is null)
	begin select estquotecblquoteseq.nextval into :new.id from dual;
end


create table estquotecblquoteqty (
       id double precision not null unique,
       quoteid double precision not null,
       qty double precision not null,
       weight decimal(10,2) not null,
       shipcost decimal(10,2) not null,
       turnaround int not null,
       total decimal(10,2) not null,
       primary key(id));

create sequence estquotecblquoteqtyseq increment by 1 start with 1;
create trigger estquotecblquoteqtytrig before insert on estquotecblquoteqty
for each row
when (new.id is null)
	begin select estquotecblquoteqtyseq.nextval into :new.id from dual;
end


create table estquotecblquoteink (
       id double precision not null unique,
       quoteid double precision not null,
       side int not null,
       inkid double precision not null,
       pms char(4),
       primary key(id));

create sequence estquotecblquoteinkseq increment by 1 start with 1;
create trigger estquotecblquoteinktrig before insert on estquotecblquoteink
for each row
when (new.id is null)
	begin select estquotecblquoteinkseq.nextval into :new.id from dual;
end


create table estquotecblquotequest (
       id double precision not null unique,
       quoteid double precision not null,
       questionid double precision not null,
       yesno int not null,
       numbetween double precision not null,
       blob char(50) not null,
       primary key(id));

create sequence estquotecblquotequestseq increment by 1 start with 1;
create trigger estquotecblquotequesttrig before insert on estquotecblquotequest
for each row
when (new.id is null)
	begin select estquotecblquotequestseq.nextval into :new.id from dual;
end


create table estquotecblquotebindquest (
       id double precision not null unique,
       quoteid double precision not null,
       questionid double precision not null,
       yesno int not null,
       numbetween double precision not null,
       blob char(50) not null,
       primary key(id));
create sequence estquotecblquotebindquestseq increment by 1 start with 1;
create trigger estquotecblquotebindquesttrig before insert on estquotecblquotebindquest
for each row
when (new.id is null)
	begin select estquotecblquotebindquestseq.nextval into :new.id from dual;
end


