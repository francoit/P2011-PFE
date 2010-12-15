#*********
# general
#*********
create table genuser (
     id double not null unique auto_increment,
     name char(50) not null unique,
     password char(64),
     raccessap int not null default 0,
     raccessar int not null default 0,
     raccessgl int not null default 0,
     raccesspay int not null default 0,
     raccessinv int not null default 0,
     raccessest int not null default 0,
     raccessfix int not null default 0,
     raccessimp int not null default 0,
     waccessap int not null default 0,
     waccessar int not null default 0,
     waccessgl int not null default 0,
     waccesspay int not null default 0,
     waccessinv int not null default 0,
     waccessest int not null default 0,
     waccessfix int not null default 0,
     waccessimp int not null default 0,
     saccessap int not null default 0,
     saccessar int not null default 0,
     saccessgl int not null default 0,
     saccesspay int not null default 0,
     saccessinv int not null default 0,
     saccessest int not null default 0,
     saccessfix int not null default 0,
     saccessimp int not null default 0,
     supervisor int not null default 0,
     active int not null default 0,
     stylesheetid int not null default 1,
     deflanguage int not null default 1,
     primary key(id), key(name));

create table genmessage (
     id double not null unique auto_increment,
     userid double not null,
     sourceuserid double not null default 0,
     entrydate datetime not null,
     readdate datetime not null default '0001-01-01 00:00:00',
     message blob not null,
     primary key(id), key(userid));

create table genstylesheet (
     id int not null unique auto_increment,
     name char(50) not null unique,
     filename char(50) not null unique,
     primary key(id));

create table gencompany (
     id int not null unique auto_increment,
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
     name char(80),
     currencyid double not null default 0,
     active int default 1,
     primary key(id));


create table menucategory (
     id int not null unique auto_increment,
     name char(50) not null,
     orderflag int not null default 0,
     menu int not null default 0,
     description blob,
     accessap int not null default 0,
     accessar int not null default 0,
     accessgl int not null default 0,
     accesspay int not null default 0,
     accessinv int not null default 0,
     accessest int not null default 0,
     accessfix int not null default 0,
     accessimp int not null default 0,
     setupap int not null default 0,
     setupar int not null default 0,
     setupgl int not null default 0,
     setuppay int not null default 0,
     setupinv int not null default 0,
     setupest int not null default 0,
     setupfix int not null default 0,
     setupimp int not null default 0,
     supervisor int not null default 0,
     nonsupervisor int not null default 0,
     extvend int not null default 0,
     extcust int not null default 0,
     nonext int not null default 0,
     primary key (id), key(orderflag), key(accessap), key(accessar), key(accessgl), key(accesspay), key(accessinv), key(accessest), key(accessfix), key(accessimp), key(supervisor), key(nonsupervisor), key(extvend), key(extcust), key(nonext));

create table menufunction (
     id int not null unique auto_increment,
     menucategoryid int not null,
     name char(50) not null,
     leftimageurl char(50),
     rightimageurl char(50),
     link char(50) not null,
     orderflag int not null default 0,
     accessap int not null default 0,
     accessar int not null default 0,
     accessgl int not null default 0,
     accesspay int not null default 0,
     accessinv int not null default 0,
     accessest int not null default 0,
     accessfix int not null default 0,
     accessimp int not null default 0,
     setupap int not null default 0,
     setupar int not null default 0,
     setupgl int not null default 0,
     setuppay int not null default 0,
     setupinv int not null default 0,
     setupest int not null default 0,
     setupfix int not null default 0,
     setupimp int not null default 0,
     supervisor int not null default 0,
     nonsupervisor int not null default 0,
     extvend int not null default 0,
     extcust int not null default 0,
     nonext int not null default 0,
     primary key (id), key(menucategoryid), key(orderflag), key(accessap), key(accessar), key(accessgl), key(accesspay), key(accessinv), key(accessest), key(accessfix), key(accessimp), key(supervisor), key(nonsupervisor), key(extvend), key(extcust), key(nonext));

create table menupage (
     id int not null unique auto_increment,
     menufunctionid int not null,
     name char(50) not null,
     orderflag int not null default 0,
     accessap int not null default 0,
     accessar int not null default 0,
     accessgl int not null default 0,
     accesspay int not null default 0,
     accessinv int not null default 0,
     accessest int not null default 0,
     accessfix int not null default 0,
     accessimp int not null default 0,
     setupap int not null default 0,
     setupar int not null default 0,
     setupgl int not null default 0,
     setuppay int not null default 0,
     setupinv int not null default 0,
     setupest int not null default 0,
     setupfix int not null default 0,
     setupimp int not null default 0,
     supervisor int not null default 0,
     nonsupervisor int not null default 0,
     extvend int not null default 0,
     extcust int not null default 0,
     nonext int not null default 0,
     primary key (id), key(menufunctionid), key(orderflag), key(accessap), key(accessar), key(accessgl), key(accesspay), key(accessinv), key(accessest), key(accessfix), key(accessimp), key(supervisor), key(nonsupervisor), key(extvend), key(extcust), key(nonext));

#*********
#gl
#*********
create table glaccount (
     id double not null unique auto_increment,
     name char(8) not null,
     description char(30) not null,
     accounttypeid int not null,
     companyid double not null default 0,
     summaryaccountid double not null default 0,
     lastchangedate timestamp,
     lastchangeuserid double,
     primary key(id), key(name), key(accounttypeid), key(companyid), key(summaryaccountid));


create table accounttype (
     id int not null unique,
     description char(30) not null,
     primary key(id));

create table gltransvoucher (
     id double not null unique auto_increment,
     voucher char(20) not null,
     description char(50) not null,
     comments char(50) not null default '',
     wherefrom int not null,
     status int default 0,
     cancel int default 0,
     companyid double not null,
     standardset double not null default 0,
     entrydate datetime,
     posteddate datetime,
     post2date date not null,
     canceldate datetime,
     lastchangedate timestamp,
     lastchangeuserid double,
     entryuserid double,
     canceluserid double,
     postuserid double,
     primary key(id), key(wherefrom), key(companyid), key(post2date), key(voucher));

create table gltransaction (
     id double not null unique auto_increment,
     glaccountid double not null,
     voucherid double,
     amount decimal(14,2) not null default 0,
     primary key(id), key(glaccountid), key(voucherid));

create table glbudgets (
     id double not null unique auto_increment,
     glaccountid double not null,
     companyid double not null,
     budgetyear int not null,
     jan decimal(14,2) not null default 0,
     feb decimal(14,2) not null default 0,
     mar decimal(14,2) not null default 0,
     apr decimal(14,2) not null default 0,
     may decimal(14,2) not null default 0,
     jun decimal(14,2) not null default 0,
     jul decimal(14,2) not null default 0,
     aug decimal(14,2) not null default 0,
     sep decimal(14,2) not null default 0,
     oct decimal(14,2) not null default 0,
     nov decimal(14,2) not null default 0,
     decm decimal(14,2) not null default 0,
     lastchangedate timestamp,
     lastchangeuserid double not null,
     primary key(id), key(glaccountid), key(companyid), key(budgetyear));

create table glcompany (
     id double not null unique,
     fiscalbeginmonth int not null default 1,
     primary key(id));

#*********
#inventory
#*********

create table item (
     id double not null unique auto_increment,
     itemcode char(20) not null,
     compositeitemyesno int not null default 0,
     description char(100) not null,
     categoryid double not null,
     stockunitnameid double not null,
     priceunitnameid double not null,
     lbsperpriceunit double not null,
     priceunitsperstockunit double not null,
     inventoryglacctid double not null,
     salesglacctid double not null default 0,
     catalogdescription text,
     catalogsheeturl char(200),
     graphicurl char(200),
     companyid double not null,
     cancel int not null default 0,
     canceldate datetime,
     canceluserid double,
     entrydate datetime,
     entryuserid double,
     lastchangedate timestamp,
     lastchangeuserid double,
     primary key(id), key(itemcode), key(categoryid), key(inventoryglacctid), key(companyid));

create table itemcategory (
     id double not null unique auto_increment,
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
     cancel int not null default 0,
     canceldate datetime,
     canceluserid double,
     entrydate datetime,
     entryuserid double,
     lastchangedate timestamp,
     lastchangeuserid double,
     primary key(id));

create table compositeitemid (
     id double not null unique auto_increment,
     itemcodeid double not null,
     subitemcodeid double,
     quantity double not null,
     cancel int not null default 0,
     canceldate datetime,
     canceluserid double,
     entrydate datetime,
     entryuserid double,
     lastchangedate timestamp,
     lastchangeuserid double,
     primary key(id), key(itemcodeid), key(subitemcodeid));

create table unitname (
     id double not null unique auto_increment,
     unitname char(10),
     cancel int not null default 0,
     canceldate datetime,
     canceluserid double,
     entrydate datetime,
     entryuserid double,
     lastchangedate timestamp,
     lastchangeuserid double);

create table priceperpriceunit (
     id double not null unique auto_increment,
     itemid double not null,
     itemlocationid double not null,
     pricelevelid double not null default 1,
     price decimal(10,4) not null,
     cancel int not null default 0,
     canceldate datetime,
     canceluserid double,
     entrydate datetime,
     entryuserid double,
     lastchangedate timestamp,
     lastchangeuserid double,
     primary key(id), key(itemid), key(itemlocationid), key(pricelevelid));

create table pricediscount (
     id double not null unique auto_increment,
     itemid double not null,
     itemlocationid double not null,
     quantity decimal(12,4) not null default 1,
     discount decimal(6,3) not null,
     cancel int not null default 0,
     canceldate datetime,
     canceluserid double,
     entrydate datetime,
     entryuserid double,
     lastchangedate timestamp,
     lastchangeuserid double,
     primary key(id), key(itemid), key(itemlocationid));

create table inventorylocation (
     id double not null unique auto_increment,
     companyid double not null,
     gencompanyid double not null,
     primary key(id), key(companyid));

create table itemlocation (
     id double not null unique auto_increment,
     itemid double not null,
     inventorylocationid double not null,
     onhandqty double not null,
     maxstocklevelseason1 double not null,
     minstocklevelseason1 double not null,
     orderqtyseason1 double not null,
     maxstocklevelseason2 double not null,
     minstocklevelseason2 double not null,
     orderqtyseason2 double not null,
     maxstocklevelseason3 double not null,
     minstocklevelseason3 double not null,
     orderqtyseason3 double not null,
     maxstocklevelseason4 double not null,
     minstocklevelseason4 double not null,
     orderqtyseason4 double not null,
     markupsetid double not null,
     firstcost decimal(12,5) not null,
     midcost decimal(12,5) not null,
     lastcost decimal(12,5) not null,
     firstqty double not null default 0,
     midqty double not null default 0,
     lastqty double not null default 0,
     cancel int not null default 0,
     canceldate datetime,
     canceluserid double,
     entrydate datetime,
     entryuserid double,
     lastchangedate timestamp,
     lastchangeuserid double,
     primary key(id), key(itemid), key(inventorylocationid), key(markupsetid));


create table markupset (
     id double not null unique auto_increment,
     description char(35),
     costbased int not null default 1,
     companyid double not null ,
     cancel int not null default 0,
     canceldate datetime,
     canceluserid double,
     entrydate datetime,
     entryuserid double,
     lastchangedate timestamp,
     lastchangeuserid double,
     primary key(id), key(companyid), key(costbased));

create table markupsetlevel (
     id double not null unique auto_increment,
     pricelevelid double not null,
     markupsetid double not null,
     markuppercent decimal (6,3) not null,
     cancel int not null default 0,
     canceldate datetime,
     canceluserid double,
     entrydate datetime,
     entryuserid double,
     lastchangedate timestamp,
     lastchangeuserid double,
     primary key(id), key(pricelevelid), key(markupsetid));

create table pricelevel (
       id double not null unique auto_increment,
       description char(30) not null,
       companyid double not null ,
       cancel int not null default 0,
       canceldate datetime,
       canceluserid double,
       entrydate datetime,
       entryuserid double,
       lastchangedate timestamp,
       lastchangeuserid double,
       primary key(id), key(companyid));

create table itemvendor (
       id double not null unique auto_increment,
       vendorid double not null,
       itemid double not null,
       vordernumber char(30) not null,
       vitemunitnameid double not null,
       vitemconversion double not null default 1,
       vitemcost1 decimal (10,3) not null,
       vitemqty1 decimal (10,0) not null,
       vitemcost2 decimal(10,3) not null,
       vitemqty2 decimal(10,0) not null,
       vitemcost3 decimal(10,3) not null,
       vitemqty3 decimal(10,0) not null,
       vitemcost4 decimal (10,3) not null,
       cancel int not null default 0,
       canceldate datetime,
       canceluserid double,
       entrydate datetime,
       entryuserid double not null,
       lastchangedate timestamp,
       lastchangeuserid double,
       primary key(id), key(itemid), key(vendorid));

create table invpo (
       id double not null unique auto_increment,
       vendorid double not null,
       ponumber char(20) not null,
       duedate date,
       locationid double not null,
       carrierserviceid double,
       tracknumber char(50),
       contact char(20),
       requisition char(20),
       ordernumber char(20),
       currencyid double not null default 0,
       gencompanyid double not null,
       complete int not null default 0,
       cancel int not null default 0,
       canceldate datetime,
       canceluserid double,
       entrydate datetime,
       entryuserid double,
       lastchangedate timestamp,
       lastchangeuserid double,
       primary key(id),key(ponumber),key(vendorid));

create table invpodetail (
       id double not null unique auto_increment,
       invpoid double not null,
       itemid double not null default 0,
       unitperpack double not null default 1,
       itemqty double not null,
       itemprice double not null,
       primary key(id), key(invpoid),key(itemid));

create table invpoquote (
       id double not null unique auto_increment,
       invpoid double not null,
       vendorid double not null,
       invpodetailid double not null default 0,
       itemqty decimal (10,2) not null,
       itemprice decimal(10,3) not null,
       primary key(id),key(invpoid),key(vendorid),key(invpodetailid));

create table invreceive (
       id double not null unique auto_increment,
       recsource int not null default 0,
       invpoid double not null default 0,
       receivedate datetime,
       itemid double not null,
       vendorid double not null,
       locationid double not null,
       itemqty decimal(10,2) not null,
       itemprice decimal(10,3) not null,
       itemqtyused decimal(10,2) not null default 0,
       conversion decimal(8,4) not null default 1,
       track char(30),
       receiveunitnameid double not null,
       passtoap int not null default 0,
       apbillid double not null default 0,
       gencompanyid double not null,
       cancel int not null default 0,
       canceldate datetime,
       canceluserid double,
       entrydate datetime,
       entryuserid double,
       lastchangedate timestamp,
       lastchangeuserid double,
       primary key(id),key(invpoid),key(itemid));


#*********
#ap
#*********

create table vendor (
     id double not null unique auto_increment,
     paytocompanyid double not null,
     orderfromcompanyid double not null,
     orderfromname char(30),
     paytermsid double not null default 0,
     paynone int not null default 0,
     defaultglacctid double,
     defaultbilldescription char(50),
     customeraccount char(20),
     gencompanyid double not null,
     cancel int not null default 0,
     canceldate datetime,
     canceluserid double,
     entrydate datetime,
     entryuserid double,
     lastchangedate timestamp,
     lastchangeuserid double,
     primary key(id), key(paytocompanyid), key(orderfromcompanyid));

#*********
#general
#*********

create table company (
     id double not null unique auto_increment,
     companyname char(80) not null,
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
     cancel int not null default 0,
     canceldate datetime,
     canceluserid double,
     entrydate datetime,
     entryuserid double,
     lastchangedate timestamp,
     lastchangeuserid double);

#*********
#ar
#*********

create table customer(
     id double not null unique auto_increment,
     companyid double not null,
     taxexemptid double not null default 0,
     creditlimit double not null default 0,
     salesglacctid double not null default 0,
     salesmanid double not null default 0,
     servicerepid double not null default 0,
     invoicetermsid double not null default 0,
     quotecommentid double not null default 0,
     interest int not null default 1,
     billtoattnname char(30),
     quoteattnname char(30),
     chargecode char(30),
     salestaxnum char(30),
     gencompanyid double not null default 0,
     cancel int default 0,
     canceldate datetime,
     canceluserid double,
     entrydate datetime,
     entryuserid double,
     lastchangedate timestamp,
     lastchangeuserid double,
     primary key(id), key(companyid), key(taxexemptid), key(salesglacctid), key(salesmanid), key(servicerepid), key(invoicetermsid), key(quotecommentid));


create table shipto (
     id double not null unique auto_increment,
     companyid double not null,
     shiptocompanyid double not null unique,
     defaultshipvia double not null default 1,
     cancel int not null default 0,
     canceldate datetime,
     canceluserid double,
     entrydate datetime,
     entryuserid double,
     lastchangedate timestamp,
     lastchangeuserid double,
     primary key(id), key(companyid), key(shiptocompanyid));


create table salesman (
     id double not null unique auto_increment,
     companyid double not null,
     payrollid double not null,
     commissionrate decimal(6,3) not null,
     commissionbase int not null,
     servicerep int not null default 0,
     salesman int not null default 0,
     gencompanyid double not null,
     cancel int not null default 0,
     canceldate datetime,
     canceluserid double,
     entrydate datetime,
     entryuserid double,
     lastchangedate timestamp,
     lastchangeuserid double,
     primary key(id), key(companyid), key(payrollid), key(servicerep), key(salesman), key(gencompanyid));


create table quotecomment (
     id double not null unique auto_increment,
     comments char(100) not null,
     cancel int default 0,
     canceldate datetime,
     canceluserid double,
     entrydate datetime,
     entryuserid double,
     lastchangedate timestamp,
     lastchangeuserid double);


create table invoiceterms (
     id double not null unique auto_increment,
     verbal char(30),
     discountpercent decimal(6,3) not null,
     discountdays int not null,
     discountdayofmonth int not null default 0,
     netduedays int not null,
     ar int not null default 0,
     ap int not null default 0,
     cancel int not null default 0,
     canceldate datetime,
     canceluserid double,
     entrydate datetime,
     entryuserid double,
     lastchangedate timestamp,
     lastchangeuserid double,
     primary key(id), key(ar), key(ap));


create table salestax (
     id double not null unique auto_increment,
     taxname char(30),
     taxrate decimal(7,4) not null,
     taxbase int not null,
     glacctid double not null,
     cancel int not null default 0,
     canceldate datetime,
     canceluserid double,
     entrydate datetime,
     entryuserid double,
     lastchangedate timestamp,
     lastchangeuserid double);


create table customersalestax (
     id double not null unique auto_increment,
     customerid double not null,
     salestaxid double,
     primary key(id), key(customerid), key(salestaxid));

create table taxexempt (
     id double not null unique auto_increment,
     exemptname char(30),
     cancel int not null default 0,
     canceldate datetime,
     canceluserid double,
     entrydate datetime,
     entryuserid double,
     lastchangedate timestamp,
     lastchangeuserid double);

#########
#     ORDER FILES:
#########

create table arorder (
       id double not null unique auto_increment,
       ordernumber double not null,
       ponumber char(30),
       orderbycompanyid double not null,
       shiptocompanyid double not null,
       status int not null default 0,
       customerbillcode char(20),
       companyid double not null,
       pricelevelid double not null,
       inventorylocationid double not null,
       cancel int not null default 0,
       canceldate datetime,
       canceluserid double,
       entrydate datetime,
       entryuserid double,
       duedate date,
       lastchangedate timestamp,
       lastchangeuserid double,
       primary key(id), key(ordernumber), key(ponumber), key(orderbycompanyid), key(shiptocompanyid), key(companyid), key(pricelevelid), key(inventorylocationid));


create table arordernotes (
       orderid double not null unique,
       note text,
       lastchangedate timestamp,
       lastchangeuserid double not null,
       primary key(orderid));

create table arorderdetail (
       id double not null unique auto_increment,
       orderid double not null,
       itemid double not null,
       linenumber int not null,
       qtyorder double not null,
       qtyship double not null,
       qtybill double not null,
       glaccountid double not null,
       taxflag int not null,
       costeach decimal(10,4),
       priceach decimal(10,4),
       entrydate datetime,
       entryuserid double not null,
       lastchangedate timestamp,
       lastchangeuserid double not null,
       primary key(id), key(orderid), key(itemid), key(linenumber), key(qtyorder), key(qtyship), key(qtybill), key(glaccountid));

create table arordershippackage (
     id double not null unique auto_increment,
     ordershipid double not null,
     weight double not null,
     cost double not null,
     tracknumber char(50) not null,
     arinvoiceid double not null default 0,
     primary key(id), key(ordershipid), key(tracknumber));

create table arordershipdetail (
       id double not null unique auto_increment,
       ordershipid double not null,
       orderdetailid double not null,
       shipqty double not null,
       entrydate datetime,
       entryuserid double not null,
       lastchangedate timestamp,
       lastchangeuserid double not null,
       primary key(id), key(orderdetailid), key(shipqty), key(ordershipid));

create table arordership (
       id double not null unique auto_increment,
       orderid double not null,
       carrierserviceid double not null,
       shipdate datetime,
       locationid double not null,
       entrydate datetime,
       entryuserid double not null,
       lastchangedate timestamp,
       lastchangeuserid double not null,
       primary key(id), key(orderid), key(shipdate), key(locationid));

create table arordertax (
       id double not null unique auto_increment,
       orderid double not null,
       taxrateid double not null,
       tax decimal(12,2),
       entrydate datetime,
       entryuserid double not null,
       lastchangedate timestamp,
       lastchangeuserid double not null,
       primary key(id), key(orderid), key(taxrateid));

create table arordertrack (
       id double not null unique auto_increment,
       orderid double not null,
       action int not null default 0,
       trackdate datetime,
       trackuserid double not null,
       lastchangedate timestamp,
       lastchangeuserid double not null,
       primary key (id),key(orderid), key(trackuserid));

create table arinvoice (
       id double not null unique auto_increment,
       invoicenumber double not null,
       ponumber char(30),
       wherefrom int not null default 2,
       orderid double not null default 0,
       orderbycompanyid double not null,
       shiptocompanyid double not null,
       status int not null default 0,
       customerbillcode char(20),
       shipcost decimal (10,2) not null default 0,
       invoicetotal decimal (12,2) not null default 0,
       invoicetermsid double not null,
       salesmanid double not null default 0,
       invoicedate date,
       duedate date,
       discountdate date,
       discountamount decimal(12,2) not null default 0,
       accruedinterest decimal(12,2) not null default 0,
       datelastinterestcalc date,
       gencompanyid double not null,
       cancel int not null default 0,
       canceldate datetime,
       canceluserid double,
       entrydate datetime,
       entryuserid double,
       lastchangedate timestamp,
       lastchangeuserid double,
       primary key(id), key(invoicenumber), key(ponumber), key(orderbycompanyid), key(invoicedate), key(shiptocompanyid), key(gencompanyid));

create table arinvoicenotes (
       invoiceid double not null unique,
       note text,
       hide int not null default 0,
       lastchangedate timestamp,
       lastchangeuserid double not null,
       primary key(invoiceid));

create table arinvoicedetail (
       id double not null unique auto_increment,
       invoiceid double not null,
       linenumber int not null,
       itemid double not null default 0,
       description char(100),
       qty double not null not null default 1,
       qtyunitnameid int not null,
       glaccountid double not null,
       taxflag int not null not null default 0,
       priceach decimal(10,4) not null default 0,
       priceunitnameid int not null,
       qtyunitperpriceunit decimal (10,4) not null default 0,
       totalprice decimal (10,2) not null default 0,
       entrydate datetime,
       entryuserid double,
       lastchangedate timestamp,
       lastchangeuserid double,
       primary key(id), key(invoiceid), key(linenumber), key(qty), key(glaccountid));

create table arinvoicedetailcost (
       id double not null unique auto_increment,
       invoiceid double not null,
       cost decimal(10,4) not null default 0,
       costglaccountid double not null,
       entrydate datetime,
       entryuserid double,
       lastchangedate timestamp,
       lastchangeuserid double,
       primary key(id), key(invoiceid));

create table arinvoicetaxdetail (
     id double  not null unique auto_increment,
     invoiceid double not null,
     taxid double not null,
     taxamount decimal (12,2) not null default 0,
     primary key(id),key(invoiceid),key(taxid));

create table arinvoicepaymentdetail (
     id double  not null unique auto_increment,
     invoiceid double not null,
     amount decimal (12,2) not null default 0,
     voucherid double not null,
     datereceived date,
     paymeth int not null default 1,
     interest int not null default 0,
     primary key(id),key(voucherid),key(invoiceid),key(paymeth));

create table carrier (
     id double not null unique auto_increment,
     companyid double not null unique,
     customernumber char(20),
     trackingurlbase char(150),
     trackingurlvarname char(30),
     primary key(id), key(companyid));

create table carrierservice (
     id double not null unique auto_increment,
     carrierid double not null,
     description char(30) not null,
     primary key(id), key(carrierid));

create table arcompany (
     id double not null unique,
     imageurl char(150),
     cash double not null default 0,
     checking double not null default 0,
     interest double not null default 0,
     discount double not null default 0,
     cost double not null default 0,
     inventory double not null default 0,
     shipliability double not null default 0,
     receivables double not null default 0,
     nextinvoicenum double not null default 1,
     servicecharge decimal(8,2) not null default 0,
     interestrate decimal(8,4) not null default 0,
     primary key(id));

Create table glpie(
     id double unique not null auto_increment,
     name char(30) not null,
     description char(100),
     begindate date,
     findate date,
     cancel int not null default 0,
     canceldate datetime,
     canceluserid double,
     entrydate datetime,
     entryuserid double,
     lastchangedate timestamp,
     lastchangeuserid double,
     primary key(id));

Create table glpieslice(
     id double not null unique auto_increment,
     glpieid double not null,
     name char(30),
     begindate date,
     findate date,
     lastchangedate timestamp,
     lastchangeuserid double not null,
     primary key(id),key(glpieid));

create table glpieslicedetail(
     id double not null unique auto_increment,
     glpiesliceid double not null,
     glaccountid double not null,
     companyid double not null,
     lastchangedate timestamp,
     lastchangeuserid double not null,
     primary key(id), key(glpiesliceid));

create table invcompany (
     id double not null unique,
     cash double not null default 0,
     sales double not null default 0,
     loss double not null default 0,
     cost double not null default 0,
     freight double not null default 0,
     tax double not null default 0,
     custoritemglacct int not null default 0,
     primary key(id));


create table extuser (
     id double not null unique auto_increment,
     name char(30) not null,
     password char(50) not null,
     customer int not null default 0,
     vendor int not null default 0,
     cancel int not null default 0,
     stylesheetid int not null default 1,
     canceldate datetime,
     canceluserid double,
     entrydate datetime,
     entryuserid double,
     lastchangedate timestamp,
     lastchangeuserid double,
     primary key(id));

create table zipcode(
     zip char(10) not null,
     state char(10) not null,
     city char(50) not null,
     longitude double,
     latitude double,
     key(zip));

create table apcompany (
     id double not null unique,
     payable double not null default 0,
     interestexpense double not null default 0,
     discount double not null default 0,
     discearn int not null default 1,
     usetransactiondate int not null default 0,
     key(id));

create table checkacct(
     id double not null unique auto_increment,
     name char(30),
     glaccountid double not null,
     lastchecknumberused decimal(20),
     defaultendorser char(50),
     gencompanyid double not null default 0,
     ap int not null default 0,
     pay int not null default 0,
     primary key(id), key(name));

create table apbill(
     id double not null unique auto_increment,
     invoicenumber char(20) not null,
     cancel int not null default 0,
     complete int not null default 0,
     paynone int not null default 0,
     total decimal(12,2) not null default 0,
     description char(50),
     dateofinvoice date,
     duedate date,
     discountamount decimal(12,2) not null default 0,
     discountdate date,
     vendorid double not null,
     comment text,
     gencompanyid double not null,
     wherefrom int not null default 1,
     entrydate datetime,
     entryuserid double,
     lastchangedate timestamp,
     lastchangeuserid double,
     canceldate datetime not null default '0000-00-00 00:00:00',
     canceluserid double,
     primary key(id), key(invoicenumber), key(wherefrom));

create table apbilldetail(
     id double not null unique auto_increment,
     apbillid double not null,
     amount decimal(12,2) not null,
     glaccountid double not null,
     invreceiveid double not null default 0,
     primary key(id), key(apbillid), key(glaccountid), key(invreceiveid));

create table apbillpayment(
     id double not null unique auto_increment,
     apbillid double not null,
     amount decimal(12,2) not null,
     checkid double not null,
     checkvoid int not null default 0,
     entrydate datetime,
     entryuserid double,
     lastchangedate timestamp,
     lastchangeuserid double,
     primary key(id), key(apbillid));


#########
#     CHECKS:
#        payment types:  0=check, 1=cash, 2=cc, 3=wire, 4=other
#        check #: check# if check, cc last 4 if cc, confirm# if wire
#        wherefrom is AP or PAYROLL
#########
create table chk(
       id double not null unique auto_increment,
       wherefrom int not null default 0,
       paytype int not null default 0,
       checkdate date,
       checkvoid int not null,
       amount decimal(12,2) not null,
       checkaccountid double not null default 0,
       checknumber decimal(15),
       cashdate date not null,
       entrydate datetime,
       entryuserid double,
       lastchangedate timestamp,
       lastchangeuserid double,
       primary key(id));

create table docmgmtcategory (
     id double not null auto_increment,
     name char(255) not null,
     primary key(id));

create table docmgmtdata (
     id double not null auto_increment,
     category double not null default 0,
     owner double not null default 0,
     realname char(255) not null,
     created datetime not null default '0000-00-00 00:00:00',
     description char(255),
     itemid double not null default 0,
     comment text,
     status double not null default 0,
     version char(10),
     final int not null default 0,
     primary key(id), key(itemid), key(owner));

create table docmgmtperms (
     fid double not null default 0,
     uid double not null default 0,
     rights double not null default 0,
     key(fid), key(uid), key(rights));

create table docmgmtlog (
     id double not null default 0,
     modified_on datetime not null default '0000-00-00 00:00:00',
     modified_by double not null default 0,
     oldversion char(10),
     newversion char(10),
     note text,
     key(id), key(modified_by));

create table premployee (
     id double not null unique auto_increment,
     companyid double not null,
     firstname char(30),
     lastname char(30),
     ssnumber char(11),
     dateofbirth date not null default '0000-00-00',
     hiredate date not null default '0000-00-00',
     terminatedate date not null default '0000-00-00',
     lastreviewid double not null default 0,
     paytype int not null default 0,
     payperiod int not null default 0,
     payperperiod decimal(10,2) not null default 0,
     lastpaychangedate datetime not null default '0000-00-00 00:00:00',
     glaccountid double not null,
     maritalstatus int not null default 0,
     federalexemptions int not null default 0,
     extrafitperpayperiod decimal(10,2) not null default 0,
     extrafitbasedon int not null default 0,
     eic int not null default 0,
     prstateid double not null default 0,
     stateexemptions int not null default 0,
     extrasitperpayperiod decimal(10,2) not null default 0,
     extrasitbasedon int not null default 0,
     prlocalid double not null default 0,
     localexemptions int not null default 0,
     extralitperpayperiod decimal(10,2) not null default 0,
     extralitbasedon int not null default 0,
     prcityid double not null default 0,
     cityexemptions int not null default 0,
     extracitperpayperiod decimal(10,2) not null default 0,
     extracitbasedon int not null default 0,
     workmanscomprate decimal(10,6) not null default 0,
     pensplanid1 double not null default 0,
     pensplandedamount1 decimal(10,4) not null default 0,
     pensplanbase1 int not null default 0,
     pensplanid2 double not null default 0,
     pensplandedamount2 decimal(10,4) not null default 0,
     pensplanbase2 int not null default 0,
     vacationhoursaccrued decimal(10,2) not null default 0,
     sickleavehoursaccrued decimal(10,2) not null default 0,
     prdedgroupid double not null default 0,
     status int not null default 0,
     gencompanyid double not null,
     cancel int not null default 0,
     canceldate datetime not null default '0000-00-00 00:00:00',
     canceluserid double,
     entrydate datetime not null,
     entryuserid double not null default 0,
     lastchangedate timestamp,
     lastchangeuserid double not null default 0,
     primary key(id));

create table prempldeduction (
     id double not null unique auto_increment,
     employeeid double not null,
     description char(50),
     amountperperiod decimal(10,4),
     glaccountid double not null,
     periodsremain int not null default 0,
     cancel int not null default 0,
     canceldate datetime not null default '0000-00-00 00:00:00',
     canceluserid double,
     entrydate datetime not null,
     entryuserid double not null default 0,
     lastchangedate timestamp,
     lastchangeuserid double not null default 0,
     primary key(id), key(employeeid));

create table premplweek (
     id double not null unique auto_increment,
     employeeid double not null,
     periodbegindate date not null,
     periodenddate date not null,
     prperiodid double not null,
     tipspay decimal(10,2) not null default 0,
     tipsaswages decimal(10,2) not null default 0,
     misctaxablepay decimal(10,2) not null default 0,
     misctaxablecomment char(30),
     miscnontaxablepay decimal(10,2) not null default 0,
     miscnontaxablecomment char(30),
     vacaccrue decimal(5,2) not null default 0,
     sickaccrue decimal(5,2) not null default 0,
     federaltax decimal(10,2) not null default 0,
     ficatax decimal(10,2) not null default 0,
     statetax decimal(10,2) not null default 0,
     localtax decimal(10,2) not null default 0,
     citytax decimal(10,2) not null default 0,
     prstateid double not null default 0,
     prcityid double not null default 0,
     prlocalid double not null default 0,
     eiccredit decimal(10,2) not null default 0,
     miscdeduction decimal(10,2) not null default 0,
     miscdeductioncomment char(30),
     medicarededuction decimal(10,2) not null default 0,
     calculatestatus int not null default 0,
     fuitax decimal(10,2) not null default 0,
     cficatax decimal(10,2) not null default 0,
     cmedicarededuction decimal(10,2) not null default 0,
     suitax decimal(10,2) not null default 0,
     netpay decimal(10,2) not null default 0,
     checkid double not null default 0,
     cancel int not null default 0,
     canceldate datetime not null default '0000-00-00 00:00:00',
     canceluserid double,
     entrydate datetime not null,
     entryuserid double not null default 0,
     lastchangedate timestamp,
     lastchangeuserid double not null default 0,
     primary key(id));

create table premplweekpaydetail (
     id double not null unique auto_increment,
     premplweekid double not null,
     prpaytypeid int not null default 1,
     prbendedid double not null default 0,
     qty decimal(8,2) not null default 0,
     rate decimal(8,4) not null default 0,
     amount decimal(10,2) not null default 0,
     glaccountid double not null,
     primary key(id));

create table prpaytype (
     id double not null unique auto_increment,
     name char(4) not null,
     description char(40) not null,
     multiplier decimal(5,2) not null default 1,
     vacation int not null default 0,
     sick int not null default 0,
     gencompanyid double not null,
     primary key(id));

create table premplweekdeddetail (
     id double not null unique auto_increment,
     premplweekid double not null,
     prempldeductionid double not null default 0,
     prbendedid double not null default 0,
     prpensionid double not null default 0,
     amount decimal(10,2) not null default 0,
     dedtype int not null default 0,
     primary key(id));

create table prperiod (
     id double not null unique auto_increment,
     numperyear int not null default 52,
     name char(15) not null,
     primary key (id));

create table prpaychange (
       id double not null unique auto_increment,
       employeeid double not null,
       oldpay decimal (10,2),
       newpay decimal (10,2),
       paystartdate date not null default '0000-00-00',
       lastchangedate timestamp,
       lastchangeuserid double not null default 0,
       primary key(id));

create table practive (
       id double not null unique auto_increment,
       employeeid double not null,
       status int not null default 0,
       statuschangedate date not null default '0000-00-00',
       lastchangedate timestamp,
       lastchangeuserid double not null default 0,
       primary key(id));



create table premplreview (
     id double not null unique auto_increment,
     employeeid double not null,
     evaluatorname char(50),
     evaldate date not null,
     premplreviewratingid double not null,
     comments blob,
     cancel int not null default 0,
     canceldate datetime not null default '0000-00-00 00:00:00',
     canceluserid double,
     entrydate datetime not null,
     entryuserid double not null default 0,
     lastchangedate timestamp,
     lastchangeuserid double not null default 0,
     primary key(id));

create table premplreviewrating (
    id double not null unique auto_increment,
    description char(50) not null,
    primary key(id));

create table prdepositchecks(
     checkid double not null,
     prperiodid double not null,
     periodbegindate date not null,
     periodenddate date not null,
     gencompanyid double not null,
     primary key(checkid), key(prperiodid));

create table prcompany (
     id double not null unique,
     fedtaxnum char(15) not null default '',
     w2companyname char(80),
     w2companyaddress1 char(100),
     w2companyaddress2 char(100),
     w2citystatezip char(100),
     stateunemplnum char(15),
     glcheckaccountid double not null default 0,
     glfitpayableid double not null default 0,
     glficapayableid double not null default 0,
     glficaexpenseid double not null default 0,
     glfuipayableid double not null default 0,
     glfuiexpenseid double not null default 0,
     glmedicarepayableid double not null default 0,
     glmedicareexpenseid double not null default 0,
     glsuipayableid double not null default 0,
     glsuiexpenseid double not null default 0,
     glmiscdedpayableid double not null default 0,
     gltaxexemptexpenseid double not null default 0,
     glworkmanscomppayableid double not null default 0,
     glworkmanscompexpenseid double not null default 0,
     post2payables int not null default 1,
     checkacctid double not null,
     shift2multiplier decimal(5,2) default 1,
     shift3multiplier decimal(5,2) default 1,
     sickleavehrsperyear decimal(5,2) default 0,
     maxsickleave decimal(7,2) default 0,
     minwagehr decimal(10,2) default 0,
     autoprintdeposit int not null default 0,
     depositvendorid double not null default 0,
     cancel int not null default 0,
     canceldate datetime not null default '0000-00-00 00:00:00',
     canceluserid double,
     entrydate datetime not null,
     entryuserid double not null default 0,
     lastchangedate timestamp,
     lastchangeuserid double not null default 0,
     primary key(id));

create table prcompanyperiod (
       id double not null unique auto_increment,
       prcompanyid double not null,
       prperiodid double not null,
       maxpayhr decimal(10,2) not null default 0,
       maxgross decimal(10,2) not null default 0,
       primary key(id), key(prcompanyid), key(prperiodid));

create table prvacation (
     id double not null unique auto_increment,
     yrsbeforeaccrue decimal(5,2) not null default 0,
     vacdaysperyear decimal(5,2) not null default 0,
     maxaccrue decimal(5,2) not null default 0,
     gencompanyid double not null,
     cancel int not null default 0,
     canceldate datetime not null default '0000-00-00 00:00:00',
     canceluserid double,
     entrydate datetime not null,
     entryuserid double not null default 0,
     lastchangedate timestamp,
     lastchangeuserid double not null default 0,
     primary key(id));

create table prfederal (
     id double not null unique auto_increment,
     maxwagesfica decimal(10,2) not null default 0,
     employeeficapercent decimal(8,4) not null default 0,
     companyficapercent decimal(8,4) not null default 0,
     maxwagesmedicare decimal(10,2) not null default 0,
     employeemedicarepercent decimal(8,4) not null default 0,
     companymedicarepercent decimal(8,4) not null default 0,
     maxwagesfui decimal(10,2) not null default 0,
     companyfuipercent decimal(8,4) not null default 0,
     eicsinglepercent1 decimal(8,4) not null default 0,
     eicsingleover1 decimal(10,2) not null default 0,
     eicsingletax2 decimal(10,2) not null default 0,
     eicsingleover2 decimal(10,2) not null default 0,
     eicsingletax3 decimal(10,2) not null default 0,
     eicsinglepercent3 decimal(8,4) not null default 0,
     eicsingleover3 decimal(10,2) not null default 0,
     eicmarriedpercent1 decimal(8,4) not null default 0,
     eicmarriedover1 decimal(10,2) not null default 0,
     eicmarriedtax2 decimal(10,2) not null default 0,
     eicmarriedover2 decimal(10,2) not null default 0,
     eicmarriedtax3 decimal(10,2) not null default 0,
     eicmarriedpercent3 decimal(8,4) not null default 0,
     eicmarriedover3 decimal(10,2) not null default 0,
     exemptionallow decimal(10,2) not null default 0,
     gencompanyid double not null unique,
     cancel int not null default 0,
     canceldate datetime not null default '0000-00-00 00:00:00',
     canceluserid double,
     entrydate datetime not null,
     entryuserid double not null default 0,
     lastchangedate timestamp,
     lastchangeuserid double not null default 0,
     primary key(id));

create table prfederaldetail (
     id double not null unique auto_increment,
     prfederalid double not null,
     maritalstatus int not null default 0,
     deductiontable int not null default 0,
     tax decimal(10,2) not null default 0,
     percent decimal(8,4) not null default 0,
     over decimal(10,2) not null default 0,
     primary key(id));

create table genstate (
     id double not null unique auto_increment,
     stateinit char(3) unique not null,
     statename char(30),
     primary key(id), key(stateinit));

create table prstate (
     id double not null unique auto_increment,
     gencompanyid double not null,
     genstateid double not null,
     taxnum char(20),
     suipercent decimal(8,4) not null default 0,
     suimax decimal(10,2) not null default 0,
     deductfed int not null default 0,
     feddeductmax decimal(10,2) not null default 0,
     exemptyr1 decimal(10,2) not null default 0,
     exemptyr2 decimal(10,2) not null default 0,
     exemptyr3 decimal(10,2) not null default 0,
     exemptyr4 decimal(10,2) not null default 0,
     glacctid double not null,
     vendorid double not null,
     maxexemptpercent decimal(8,4) default 0,
     maxexemptyear decimal(10,2) default 0,
     taxcreditexempt1 decimal(10,2) not null default 0,
     taxcreditexempt2 decimal(10,2) not null default 0,
     taxcreditexempt3 decimal(10,2) not null default 0,
     taxcreditexempt4 decimal(10,2) not null default 0,
     cancel int not null default 0,
     canceldate datetime not null default '0000-00-00 00:00:00',
     canceluserid double,
     entrydate datetime not null,
     entryuserid double not null default 0,
     lastchangedate timestamp,
     lastchangeuserid double not null default 0,
     primary key(id), key(genstateid));

create table prstatedetail (
     id double not null unique auto_increment,
     prstateid double not null,
     maritalstatus int not null default 0,
     deductiontable int not null default 0,
     tax decimal(10,2) not null default 0,
     percent decimal(8,4) not null default 0,
     over decimal(10,2) not null default 0,
     primary key(id));

create table prlocal (
     id double not null unique auto_increment,
     gencompanyid double not null,
     abrev char(3) not null,
     name char(30) not null,
     taxnum char(20) not null default 0,
     deductfed int not null default 0,
     feddeductmax decimal(10,2) not null default 0,
     exemptyr1 decimal(10,2) not null default 0,
     exemptyr2 decimal(10,2) not null default 0,
     exemptyr3 decimal(10,2) not null default 0,
     exemptyr4 decimal(10,2) not null default 0,
     glacctid double not null default 0,
     vendorid double not null,
     maxexemptpercent decimal(5,2) not null default 0,
     maxexemptyear decimal(10,2) not null default 0,
     taxcreditexempt1 decimal(10,2) not null default 0,
     taxcreditexempt2 decimal(10,2) not null default 0,
     taxcreditexempt3 decimal(10,2) not null default 0,
     taxcreditexempt4 decimal(10,2) not null default 0,
     cancel int not null default 0,
     canceldate datetime not null default '0000-00-00 00:00:00',
     canceluserid double,
     entrydate datetime not null,
     entryuserid double not null default 0,
     lastchangedate timestamp,
     lastchangeuserid double not null default 0,
     primary key(id));

create table prlocaldetail (
     id double not null unique auto_increment,
     prlocalid double not null,
     maritalstatus int not null default 0,
     deductiontable int not null default 0,
     tax decimal(10,2) not null default 0,
     percent decimal(8,4) not null default 0,
     over decimal(10,2) not null default 0,
     primary key(id));

create table prcity (
     id double not null unique auto_increment,
     gencompanyid double not null,
     abrev char(3) not null,
     name char(30) not null,
     taxnum char(20) not null default 0,
     deductfed int not null default 0,
     feddeductmax decimal(10,2) not null default 0,
     exemptyr1 decimal(10,2) not null default 0,
     exemptyr2 decimal(10,2) not null default 0,
     exemptyr3 decimal(10,2) not null default 0,
     exemptyr4 decimal(10,2) not null default 0,
     glacctid double not null default 0,
     vendorid double not null,
     maxexemptpercent decimal(5,2) not null default 0,
     maxexemptyear decimal(10,2) not null default 0,
     taxcreditexempt1 decimal(10,2) not null default 0,
     taxcreditexempt2 decimal(10,2) not null default 0,
     taxcreditexempt3 decimal(10,2) not null default 0,
     taxcreditexempt4 decimal(10,2) not null default 0,
     cancel int not null default 0,
     canceldate datetime not null default '0000-00-00 00:00:00',
     canceluserid double,
     entrydate datetime not null,
     entryuserid double not null default 0,
     lastchangedate timestamp,
     lastchangeuserid double not null default 0,
     primary key(id));

create table prcitydetail (
     id double not null unique auto_increment,
     prcityid double not null,
     maritalstatus int not null default 0,
     deductiontable int not null default 0,
     tax decimal(10,2) not null default 0,
     percent decimal(8,4) not null default 0,
     over decimal(10,2) not null default 0,
     primary key(id));

create table prbended (
     id double not null unique auto_increment,
     gencompanyid double not null,
     paytype int not null default 0,
     bendedtype int not null default 0,
     name char(30) not null,
     howfig int not null default 0,
     prdedgroupid double not null default 0,
     rate decimal(8,4) not null default 0,
     ceilingperyear decimal(10,2) not null default 0,
     expenseglacctid double not null default 0,
     payableglacctid double not null default 0,
     vendorid double not null default 0,
     cancel int not null default 0,
     canceldate datetime not null default '0000-00-00 00:00:00',
     canceluserid double,
     entrydate datetime not null,
     entryuserid double not null default 0,
     lastchangedate timestamp,
     lastchangeuserid double not null default 0,
     primary key(id), key(name));

create table prdedgroup (
       id double not null unique auto_increment,
       name char(30) not null,
       gencompanyid double not null,
       primary key(id));

create table prpension (
     id double not null unique auto_increment,
     gencompanyid double not null,
     name char(30) not null,
     w2plantype int not null,
     w2plansubtype char(1),
     employercontribhow int not null default 0,
     employercontribute decimal(8,4) not null default 0,
     employermaxmatchpercent decimal(8,4) not null default 0,
     mustbeinplan int not null default 0,
     calcbasis int not null default 3,
     prdedgroupid double not null default 0,
     paytype int not null default 0,
     payableglacctid double not null,
     expenseglacctid double not null,
     federalincometax int not null default 0,
     stateincometax int not null default 0,
     localincometax int not null default 0,
     cityincometax int not null default 0,
     employeefica int not null default 0,
     companyfica int not null default 0,
     fui int not null default 0,
     sui int not null default 0,
     workmanscomp int not null default 0,
     vendorid double not null default 0,
     cancel int not null default 0,
     canceldate datetime not null default '0000-00-00 00:00:00',
     canceluserid double,
     entrydate datetime not null,
     entryuserid double not null default 0,
     lastchangedate timestamp,
     lastchangeuserid double not null default 0,
     primary key(id), key(name));


###########################
# Estimating
###########################

create table estquotegenstock (
     id double not null unique auto_increment,
     name char(30) not null,
     gencompanyid double not null default 0,
     orderflag int not null default 0,
     cancel int not null default 0,
     canceldate datetime not null default '0000-00-00 00:00:00',
     canceluserid double not null default 0,
     entrydate datetime not null,
     entryuserid double not null default 0,
     lastchangedate timestamp,
     lastchangeuserid double,
     primary key(id), key(name), key(gencompanyid));

create table estquotesubstock (
     id double not null unique auto_increment,
     estquotegenstockid double not null,
     name char(50) not null,
     weight decimal(6,4) not null default 0,
     turnaround int not null default 0,
     orderflag int not null default 0,
     parts int not null default 1,
     inchesperm decimal(4,2) not null default 4,
     primary key(id), key(estquotegenstockid), key(name));

create table estquotesubstockcolors (
     id double not null unique auto_increment,
     substockid double not null,
     color char(50) not null,
     primary key(id), key(substockid),key(color));

create table estquotesubstockcost (
     id double not null unique auto_increment,
     substockid double not null,
     stocktype int not null,
     length decimal(10,4) not null,
     width decimal(10,4) not null,
     cost decimal(10,4) not null,
     costhow int not null,
     itemid double not null default 0,
     primary key(id), key(substockid), key(itemid), key(cost), key(length), key(width));

create table estquotegenink (
     id double not null unique auto_increment,
     maxcolors int not null default 1,
     regcharge decimal(4,2) not null default 0,
     namecov1 char(20) not null,
     namecov2 char(20) not null,
     namecov3 char(20) not null,
     namecov4 char(20) not null,
     namecov5 char(20) not null,
     namecov6 char(20) not null,
     namecov7 char(20) not null,
     namecov8 char(20) not null,
     namecov9 char(20) not null,
     covpct1 decimal(3,0) not null default 10,
     covpct2 decimal(3,0) not null default 10,
     covpct3 decimal(3,0) not null default 10,
     covpct4 decimal(3,0) not null default 10,
     covpct5 decimal(3,0) not null default 10,
     covpct6 decimal(3,0) not null default 10,
     covpct7 decimal(3,0) not null default 10,
     covpct8 decimal(3,0) not null default 10,
     covpct9 decimal(3,0) not null default 10,
     gencompanyid double not null default 0,
     primary key(id), key(gencompanyid));


create table estquoteink (
     id double not null unique auto_increment,
     name char(50) not null,
     jobprice decimal(6,2) not null default 0,
     mprice decimal(6,4) not null default 0,
     type int not null default 0,
     costbased int not null default 0,
     costper decimal(6,2) not null default 0,
     coverage int not null default 0,
     gencompanyid double not null default 0,
     primary key(id), key(name), key(gencompanyid));

create table estquoteworktypegen (
       id double not null unique auto_increment,
       name char(50) not null,
       gencompanyid double not null default 0,
       orderflag int not null default 0,
       cancel int not null default 0,
       canceldate datetime not null default '0000-00-00 00:00:00',
       canceluserid double not null default 0,
       entrydate datetime not null,
       entryuserid double not null default 0,
       lastchangedate timestamp,
       lastchangeuserid double,
       primary key(id));

create table estquoteworktype (
       id double not null unique auto_increment,
       name char(50) not null,
       genworktypeid double not null default 0,
       notes blob not null,
       orderflag int not null default 0,
       turnaroundqty decimal (10,0) not null default 0,
       turnarounddaysuptoqty int not null default 0,
       turnarounddaysoverqty int not null default 0,
       gencompanyid double not null default 0,
       cancel int not null default 0,
       canceldate datetime not null default '0000-00-00 00:00:00',
       canceluserid double not null default 0,
       entrydate datetime not null,
       entryuserid double not null default 0,
       lastchangedate timestamp,
       lastchangeuserid double,
       primary key(id));

create table estquoteworktypestdqty (
       id double not null unique auto_increment,
       quantity decimal (10,0) not null default 0,
       genworktypeid double not null default 0,
       primary key(id));

create table estquoteworktypeaddl (
       id double not null unique auto_increment,
       worktypeid double not null,
       question char(50) not null,
       amount decimal(5,2) not null default 0,
       validreply int not null default 0,
       minreply decimal (10,2) not null default 0,
       maxreply decimal (10,2) not null default 0,
       askonlyifmtblackink int not null default 0,
       askonlyifmtoneink int not null default 0,
       askonlyifmtoneside int not null default 0,
       askonlyminsizelength decimal (10,3) not null default 0,
       askonlyminsizewidth decimal (10,3) not null default 0,
       askonlymaxsizelength decimal (10,3) not null default 0,
       askonlymaxsizewidth decimal (10,3) not null default 0,
       askonlyifoneink int not null default 0,
       aboveqty decimal(10,2) not null default 0,
       calculatehow int not null default 0,
       cancel int not null default 0,
       canceldate datetime not null default '0000-00-00 00:00:00',
       canceluserid double not null default 0,
       entrydate datetime not null,
       entryuserid double not null default 0,
       lastchangedate timestamp,
       lastchangeuserid double,
       primary key(id));

create table estquotestdsize (
       id double not null unique auto_increment,
       length decimal(10,3) not null default 0,
       width decimal(10,3) not null default 0,
       orderflag int not null default 0,
       gencompanyid double not null default 0,
       primary key(id));

create table estquotepricelist (
       id double not null unique auto_increment,
       stdsizeid double not null default 0,
       worktypeid double not null default 0,
       name char(50) not null,
       maxinkside1 int not null default 1,
       maxinkside2 int not null default 0,
       trimchargepermpercut decimal(8,2) not null default 0,
       trimmincharge decimal(8,2) not null default 0,
       qtyperbox decimal(5) not null default 0,
       notes blob not null,
       point2id double not null default 0,
       gencompanyid double not null default 0,
       cancel int not null default 0,
       canceldate datetime not null default '0000-00-00 00:00:00',
       canceluserid double not null default 0,
       entrydate datetime not null,
       entryuserid double not null default 0,
       lastchangedate timestamp,
       lastchangeuserid double,
       primary key(id));

create table estquotepriceliststock (
       id double not null unique auto_increment,
       pricelistid double not null default 0,
       genstockid double not null default 0,
       substockid double not null default 0,
       addlchargeperm decimal(6,2) not null default 0,
       addlmincharge decimal (6,2) not null default 0,
       primary key(id));

create table estquotepriceliststockcolors(
       id double not null unique auto_increment,
       priceliststockid double not null default 0,
       substockcolorsid double not null default 0,
       primary key(id));


create table estquotepricelistprice (
       id double not null unique auto_increment,
       pricelistid double not null default 0,
       quantity decimal (10,0) not null default 0,
       priceperm decimal (6,2) not null default 0,
       primary key(id));


create table estquote (
       id double not null unique auto_increment,
       quotenum double not null default 0,
       subquotenum int not null default 0,
       customerid double not null,
       ponumber char(30),
       priority int not null default 0,
       duedate datetime not null,
       worktypeid double not null default 0,
       jobname char(30),
       jobdesc blob,
       attn char(30),
       custtrackingnum char(20),
       custchargecode char(30),
       formnumber char(20),
       itemid double not null default 0,
       itemcomposite int not null default 0,
       prpriceid double not null default 0,
       finishwidth decimal(4,4) not null default 0,
       finishheight decimal(4,4) not null default 0,
       flatwidth decimal(4,4) not null default 0,
       flatheight decimal(4,4) not null default 0,
       numpages double not null default 0,
       extuser double not null default 0,
       genuser double not null default 0,
       gencompanyid double not null,
       cancel int not null default 0,
       canceldate datetime not null default '0000-00-00 00:00:00',
       canceluserid double not null default 0,
       entrydate datetime not null,
       entryuserid double not null default 0,
       lastchangedate timestamp,
       lastchangeuserid double,
       primary key(id), key(customerid));

create table estquoteqty (
       id double not null unique auto_increment,
       quoteid double not null,
       qty double not null,
       primary key(id), key(quoteid));

create table estquotequest (
       id double not null unique auto_increment,
       quoteid double not null,
       questionid double not null,
       yesno int not null default 0,
       selection double not null default 0,
       text char(50),
       primary key(id), key(quoteid));

create table estquotestock (
       id double not null unique auto_increment,
       quoteid double not null,
       stockusageid double not null,
       stocklistid double not null,
       flatwidth decimal(4,4) not null default 0,
       flatheight decimal(4,4) not null default 0,
       numpages double not null default 0,
       notes blob,
       primary key(id), key(quoteid), key(stockusageid), key(stocklistid));

create table estquotestockink (
       id double not null unique auto_increment,
       quotestockid double not null,
       inkid double not null,
       side int not null default 1,
       inknum int not null default 0,
       coverage decimal(3,4) not null default 0,
       primary key(id), key(quotestockid), key(inkid));

create table estquotestocktool (
       id double not null unique auto_increment,
       quotestockid double not null,
       toolid double not null,
       primary key(id), key(quotestockid), key(toolid));

create table estquotefile (
       id double not null unique auto_increment,
       quoteid double not null,
       filename char(255) not null,
       primary key(id), key(filename));

create table estquoteship (
       id double not null unique auto_increment,
       quoteid double not null,
       shiptoid double not null,
       carrierserviceid double not null,
       shipqty double not null,
       primary key(id), key(quoteid), key(shiptoid), key(carrierserviceid));

create table estcostcenter (
       id double not null unique auto_increment,
       name char(50) not null,
       cctype int not null default 0,
       orderflag int not null default 0,
       cancel int not null default 0,
       primary key(id));

create table estcostcentersubtype (
       id double not null unique auto_increment,
       name char(50) not null,
       costcenterid double not null default 0,
       orderflag int not null default 0,
       cancel int not null default 0,
       primary key(id), key(costcenterid));

create table estmachine (
       id double not null unique auto_increment,
       name char(50) not null,
       costcentersubtypeid double not null,
       costmachperhr decimal(12,2) not null default 0,
       costoperperhr decimal(12,2) not null default 0,
       costasstperhr decimal(12,2) not null default 0,
       factoverhead decimal(3,2) not null default 0,
       genoverhead decimal(3,2) not null default 0,
       markup decimal(4,2) not null default 0,
       orderflag int not null default 0,
       gencompanyid double not null,
       cancel int not null default 0,
       primary key(id), key(costcentersubtypeid));

create table estnp (
       id double not null unique auto_increment,
       name char(50) not null,
       machineid double not null,
       gencompanyid double not null,
       cancel int not null default 0,
       primary key(id));

create table estnpworktype (
       id double not null unique auto_increment,
       estnpid double not null,
       estquoteworktypeid double not null,
       cancel int not null default 0,
       primary key(id));

create table estnpoperations (
       id double not null unique auto_increment,
       estnpid double not null,
       question blob not null,
       validreply int not null default 0,
       qtyperpkg decimal(5,0) not null default 1,
       weightpermpkg decimal(5,2) not null default 0,
       turnaround int not null default 0,
       orderflag int not null default 0,
       tooltip char(255) not null,
       cancel int not null default 0,
       canceldate datetime not null default '0000-00-00 00:00:00',
       canceluserid double not null default 0,
       entrydate datetime not null,
       entryuserid double not null default 0,
       lastchangedate timestamp,
       lastchangeuserid double,
       primary key(id));

create table estnpoperationsoptions (
       id double not null unique auto_increment,
       estnpoperationsid double not null,
       prompt char(50) not null,
       orderflag int not null default 0,
       primary key(id), key(estnpoperationsid));

create table estnpoperationsoptionsize (
       id double not null unique auto_increment,
       estnpoperationsid double not null,
       estquotestdsizeid double not null,
       priceamountaddl decimal(5,2) not null default 0,
       pricesetupaddl decimal(5,2) not null default 0,
       priceminimumaddl decimal(5,2) not null default 0,
       pricecalchow int not null,
       costhourssetup decimal(5,2) not null default 0,
       costhoursrun decimal(5,2) not null default 0,
       costnumoperators int not null default 0,
       costnumassistants int not null default 0,
       percenthoursmantomachine decimal(5,2) not null default 0,
       costcalchow int not null,
       minreply decimal(10,2) not null default 0,
       maxreply decimal(10,2) not null default 0,
       maxpass decimal(10,2) not null default 0,
       primary key(id), key(estnpoperationsid));

create table estnpoperationsoptionsizeaddl (
       id double not null unique auto_increment,
       estnpoperationsizeid double not null,
       itemid double not null default 0,
       qtyused double not null default 0,
       qtybasedon int not null,
       costeach decimal(5,2) not null default 0,
       primary key(id), key(estnpoperationsizeid));

create table estnpoperationsoptionsizeaddloptions (
       id double not null unique auto_increment,
       estnpoperationsizeid double not null,
       estnpoperationsoptionsid double not null,
       priceamountaddl decimal(5,2) not null default 0,
       pricesetupaddl decimal(5,2) not null default 0,
       priceminimumaddl decimal(5,2) not null default 0,
       costhourssetup decimal(5,2) not null default 0,
       costhoursrun decimal(5,2) not null default 0,
       costnumoperators int not null default 0,
       costnumassistants int not null default 0,
       primary key(id), key(estnpoperationsizeid));

create table estnpoperationsoptionsizeaddloptionsaddl (
       id double not null unique auto_increment,
       estnpoperationsoptionsizeaddloptionsid double not null,
       itemid double not null default 0,
       qtyused double not null default 0,
       qtybasedon int not null,
       costeach decimal(5,2) not null default 0,
       primary key(id), key(estnpoperationsoptionsizeaddloptionsid));

create table estprgeneral (
       gencompanyid double unique not null default 0,
       onetrimopcostsid double not null default 0,
       twotrimopcostsid double not null default 0,
       cutopcostsid double not null default 0,
       cancel int not null default 0,
       canceldate datetime not null default '0000-00-00 00:00:00',
       canceluserid double not null default 0,
       entrydate datetime not null,
       entryuserid double not null default 0,
       lastchangedate timestamp,
       lastchangeuserid double,
       primary key(gencompanyid));

create table estprprice (
       id double not null unique auto_increment,
       gencompanyid double not null default 0,
       name char(50) not null,
       worktypeid double not null default 0,
       estquotestdsizeid double not null,
       maxpages int not null default 0,
       minpages int not null default 0,
       opoffsetsheet int not null default 0,
       opoffsetweb int not null default 0,
       opdigital int not null default 0,
       opscreen int not null default 0,
       opflexo int not null default 0,
       opvended int not null default 0,
       qtyperbox decimal(5) not null default 0,
       notes blob,
       prpriceid double not null default 0,
       maxstocks int not null default 1,
       tools int not null default 0,
       cancel int not null default 0,
       canceldate datetime not null default '0000-00-00 00:00:00',
       canceluserid double not null default 0,
       entrydate datetime not null,
       entryuserid double not null default 0,
       lastchangedate timestamp,
       lastchangeuserid double,
       primary key(id));

create table estprpricestockusage (
       id double not null unique auto_increment,
       prpriceid double not null,
       name char(20) not null,
       proptionone double not null default 0,
       proptiontwo double not null default 0,
       proptionqty decimal(10) not null default 0,
       maxinksfront int not null default 0,
       mininksfront int not null default 0,
       maxinksback int not null default 0,
       mininksback int not null default 0,
       maxpages int not null default 0,
       inkcoveragedefault int not null default 1,
       stockusageid double not null default 0,
       primary key(id));

create table estprpricestockusagestock (
       id double not null unique auto_increment,
       stockusageid double not null default 0,
       proption int not null default 0,
       substockid double not null default 0,
       numberout int not null default 1,
       cuts int not null default 0,
       width decimal(10,3) not null default 0,
       length decimal(10,3) not null default 0,
       numberup int not null default 1,
       addlchargeperm decimal(10,2) not null default 0,
       addlminimum decimal (10,2) not null default 0,
       primary key(id));

create table estprpricestockusagecolors (
       id double not null unique auto_increment,
       stocklistid double not null default 0,
       substockcolorsid double not null default 0,
       primary key(id));

create table estprpriceoptioncosts (
       id double not null unique auto_increment,
       proption int not null default 0,
       stocklistid double not null default 0,
       hrsgeneralsetup decimal(6,3) not null default 0,
       hrscolorsetup decimal(6,3) not null default 0,
       runrate decimal(10,2) not null default 0,
       runrate2 decimal(10,2) not null default 0,
       runrate3 decimal(10,2) not null default 0,
       runqty decimal(8) not null default 0,
       runqty2 decimal(8) not null default 0,
       runrateqtytype int not null default 0,
       numoperators int not null default 0,
       numassistants int not null default 0,
       mantomachine int not null default 0,
       setupscrap decimal(5,2) not null default 0,
       runscrap decimal(5,2) not null default 0,
       runscrap2 decimal(5,2) not null default 0,
       scrapqty decimal(8) not null default 0,
       registrationslowdown decimal(2) not null default 0,
       maxinksperpass int not null default 1,
       estmachineid double not null default 0,
       primary key(id));

create table estprpriceoptionprice (
       id double not null unique auto_increment,
       proption int not null default 0,
       stockusageid double not null default 0,
       qty double not null default 0,
       amount decimal(10,2) not null default 0,
       qtycalchow int not null default 0,
       primary key(id));

create table estprpriceoptioncostsmaterials (
       id double not null unique auto_increment,
       proption int not null default 0,
       stocklistid double not null default 0,
       itemid double not null default 0,
       name char(30) not null,
       qty decimal(10,3) not null default 0,
       per int not null default 0,
       cost decimal(12,5) not null default 0,
       primary key(id));

create table estprpricetools (
       id double not null unique auto_increment,
       name char(30) not null,
       description char(100) not null,
       locationid double not null default 0,
       familyid double not null default 0,
       gencompanyid double not null default 0,
       firsttimeamount decimal(10,2) not null default 0,
       timesusedcounter int not null default 0,
       costperuse decimal(10,2) not null default 0,
       priceperuse decimal(10,2) not null default 0,
       costperpresshour decimal(10,2) not null default 0,
       priceperpresshour decimal(10,2) not null default 0,
       priceperpressqty decimal(10,2) not null default 0,
       tooltype int not null default 0,
       repeatlength decimal(5,3) not null default 0,
       across int not null default 0,
       around int not null default 0,
       cavityshape char(30) not null,
       cavitywidth char(30) not null,
       cavitydepth char(30) not null,
       gap char(30) not null,
       bearerwidth char(30) not null,
       gearpitchratio int not null default 0,
       teeth int not null default 0,
       addlsetuphrspress decimal(5,2) not null default 0,
       value decimal(10,2) not null default 0,
       cancel int not null default 0,
       canceldate datetime not null default '0000-00-00 00:00:00',
       canceluserid double not null default 0,
       entrydate datetime not null,
       entryuserid double not null default 0,
       lastchangedate timestamp,
       lastchangeuserid double,
       primary key(id));



###########################
# Estimating - Custom Carbonless
###########################

create table estquotecblgenstock (
     id double not null unique auto_increment,
     name char(30) not null,
     gencompanyid double not null default 0,
     orderflag int not null default 0,
     cancel int not null default 0,
     canceldate datetime not null default '0000-00-00 00:00:00',
     canceluserid double not null default 0,
     entrydate datetime not null,
     entryuserid double not null default 0,
     lastchangedate timestamp,
     lastchangeuserid double,
     primary key(id), key(name), key(gencompanyid));

create table estquotecblsubstock (
     id double not null unique auto_increment,
     estquotecblgenstockid double not null,
     name char(50) not null,
     weight decimal(6,4) not null default 0,
     turnaround int not null default 0,
     orderflag int not null default 0,
     parts int not null default 1,
     primary key(id), key(estquotecblgenstockid), key(name));

create table estquotecblsubstockcolors (
     id double not null unique auto_increment,
     substockid double not null,
     color char(50) not null,
     primary key(id), key(substockid),key(color));

create table estquotecblgenink (
     id double not null unique auto_increment,
     maxcolors int not null default 1,
     regcharge decimal(4,2) not null default 0,
     gencompanyid double not null default 0,
     primary key(id), key(gencompanyid));

create table estquotecblink (
     id double not null unique auto_increment,
     name char(50) not null,
     jobprice decimal(6,2) not null default 0,
     mprice decimal(6,4) not null default 0,
     type int not null default 0,
     gencompanyid double not null default 0,
     primary key(id), key(name), key(gencompanyid));

create table estquotecblworktype (
       id double not null unique auto_increment,
       name char(50) not null,
       turnaroundqty decimal (10,0) not null default 0,
       turnarounddaysuptoqty int not null default 0,
       turnarounddaysoverqty int not null default 0,
       gencompanyid double not null default 0,
       cancel int not null default 0,
       canceldate datetime not null default '0000-00-00 00:00:00',
       canceluserid double not null default 0,
       entrydate datetime not null,
       entryuserid double not null default 0,
       lastchangedate timestamp,
       lastchangeuserid double,
       primary key(id));

create table estquotecblworktypestdqty (
       id double not null unique auto_increment,
       quantity decimal (10,0) not null default 0,
       worktypeid double not null default 0,
       primary key(id));

create table estquotecblworktypeaddl (
       id double not null unique auto_increment,
       worktypeid double not null,
       question char(50) not null,
       amount decimal(5,2) not null default 0,
       validreply int not null default 0,
       minreply decimal (10,2) not null default 0,
       maxreply decimal (10,2) not null default 0,
       askonlyifmtblackink int not null default 0,
       askonlyifmtoneink int not null default 0,
       askonlyifmtoneside int not null default 0,
       askonlyminsizelength decimal (10,3) not null default 0,
       askonlyminsizewidth decimal (10,3) not null default 0,
       askonlymaxsizelength decimal (10,3) not null default 0,
       askonlymaxsizewidth decimal (10,3) not null default 0,
       askonlyifoneink int not null default 0,
       aboveqty decimal(10,2) not null default 0,
       calculatehow int not null default 0,
       cancel int not null default 0,
       canceldate datetime not null default '0000-00-00 00:00:00',
       canceluserid double not null default 0,
       entrydate datetime not null,
       entryuserid double not null default 0,
       lastchangedate timestamp,
       lastchangeuserid double,
       primary key(id));

create table estquotecblstdsize (
       id double not null unique auto_increment,
       length decimal(10,3) not null default 0,
       width decimal(10,3) not null default 0,
       orderflag int not null default 0,
       gencompanyid double not null default 0,
       primary key(id));

create table estquotecblpricelist (
       id double not null unique auto_increment,
       stdsizeid double not null default 0,
       worktypeid double not null default 0,
       name char(50) not null,
       maxinkside1 int not null default 1,
       maxinkside2 int not null default 0,
       trimchargepermpercut decimal(8,2) not null default 0,
       trimmincharge decimal(8,2) not null default 0,
       qtyperbox decimal(5) not null default 0,
       notes blob not null,
       point2id double not null default 0,
       gencompanyid double not null default 0,
       cancel int not null default 0,
       canceldate datetime not null default '0000-00-00 00:00:00',
       canceluserid double not null default 0,
       entrydate datetime not null,
       entryuserid double not null default 0,
       lastchangedate timestamp,
       lastchangeuserid double,
       primary key(id));

create table estquotecblpriceliststock (
       id double not null unique auto_increment,
       pricelistid double not null default 0,
       genstockid double not null default 0,
       substockid double not null default 0,
       addlchargeperm decimal(6,2) not null default 0,
       addlmincharge decimal (6,2) not null default 0,
       primary key(id));

create table estquotecblpriceliststockcolors(
       id double not null unique auto_increment,
       priceliststockid double not null default 0,
       substockcolorsid double not null default 0,
       primary key(id));


create table estquotecblpricelistprice (
       id double not null unique auto_increment,
       pricelistid double not null default 0,
       quantity decimal (10,0) not null default 0,
       priceperm decimal (6,2) not null default 0,
       primary key(id));

create table estquotecblbindery (
       id double not null unique auto_increment,
       name char(50) not null,
       gencompanyid double not null default 0,
       cancel int not null default 0,
       canceldate datetime not null default '0000-00-00 00:00:00',
       canceluserid double not null default 0,
       entrydate datetime not null,
       entryuserid double not null default 0,
       lastchangedate timestamp,
       lastchangeuserid double,
       primary key(id));

create table estquotecblbinderyaddl (
       id double not null unique auto_increment,
       estquotecblbinderyid double not null,
       name char(50) not null,
       question blob not null,
       validreply int not null default 0,
       qtyperpkg decimal(5,0) not null default 1,
       weightpermpkg decimal(5,2) not null default 0,
       turnaround int not null default 0,
       orderflag int not null default 0,
       tooltip char(255) not null,
       cancel int not null default 0,
       canceldate datetime not null default '0000-00-00 00:00:00',
       canceluserid double not null default 0,
       entrydate datetime not null,
       entryuserid double not null default 0,
       lastchangedate timestamp,
       lastchangeuserid double,
       primary key(id));

create table estquotecblbinderyaddloptions (
       id double not null unique auto_increment,
       binderyaddlid double not null default 0,
       prompt char(20) not null,
       numprompt decimal(10,2) not null default 0,
       orderflag int not null default 0,
       primary key(id),key(binderyaddlid));

create table estquotecblbinderyaddloptionsize (
       id double not null unique auto_increment,
       binderyaddlid double not null default 0,
       binderyaddloptionsid double not null default 0,
       estquotecblbinderyaddlsizeid double not null default 0,
       amountaddl decimal(5,2) not null default 0,
       setupaddl decimal(5,2) not null default 0,
       minimumaddl decimal(5,2) not null default 0,
       primary key(id),key(binderyaddlid));

create table estquotecblbinderyworktype (
       estquotecblbinderyid double not null,
       estquotecblworktypeid double not null,
       key(estquotecblbinderyid));

create table estquotecblbinderyaddlsize (
       id double not null unique auto_increment,
       estquotecblbinderyaddlid double not null,
       estquotecblstdsizeid double not null,
       amount decimal(5,2) not null default 0,
       setupcharge decimal(5,2) not null default 0,
       minreply decimal (10,2) not null default 0,
       maxreply decimal (10,2) not null default 0,
       minimumcharge decimal(5,2) not null default 0,
       calculatehow int not null default 0,
       maxpass int not null default 0,
       entrydate datetime not null,
       entryuserid double not null default 0,
       lastchangedate timestamp,
       lastchangeuserid double,
       primary key(id));

create table estquotecblquote (
       id double not null unique auto_increment,
       customerid double not null,
       contactname char(50),
       description char(255),
       stdsizeid double not null,
       cwidth decimal(10,3) not null,
       clength decimal(10,3) not null,
       substockid double not null,
       substockcolorid double not null,
       tightreg1 int not null default 0,
       tightreg2 int not null default 0,
       pricelistid double not null default 0,
       gencompanyid double not null,
       shippingservice char(3),
       shippingzipcode char(12),
       special char(255),
       quotenum char(20),
       extuser double not null default 0,
       genuser double not null default 0,
       cancel int not null default 0,
       canceldate datetime not null default '0000-00-00 00:00:00',
       canceluserid double not null default 0,
       entrydate datetime not null,
       entryuserid double not null default 0,
       lastchangedate timestamp,
       lastchangeuserid double,
       primary key(id), key(customerid));

create table estquotecblquoteqty (
       id double not null unique auto_increment,
       quoteid double not null,
       qty double not null,
       weight decimal(10,2) not null default 0,
       mcost decimal(10,2) not null default 0,
       shipcost decimal(10,2) not null default 0,
       turnaround int not null default 1,
       total decimal(10,2) not null,
       primary key(id), key(quoteid));

create table estquotecblquoteink (
       id double not null unique auto_increment,
       quoteid double not null,
       side int not null default 1,
       inkid double not null,
       pms char(4),
       primary key(id), key(quoteid));

create table estquotecblquotequest (
       id double not null unique auto_increment,
       quoteid double not null,
       questionid double not null,
       yesno int not null default 0,
       numbetween double not null default 0,
       text char(50) not null,
       primary key(id), key(quoteid));

create table estquotecblquotebindquest (
       id double not null unique auto_increment,
       quoteid double not null,
       questionid double not null,
       yesno int not null default 0,
       numbetween double not null default 0,
       text char(50) not null,
       primary key(id), key(quoteid));

create table estquotecblquotenotes (
       id double not null unique auto_increment,
       quotebold char(50) not null,
       quotetext blob not null,
       showwhen int not null default 0,
       orderflag int not null default 0,
       gencompanyid double not null,
       entrydate datetime not null,
       entryuserid double not null default 0,
       lastchangedate timestamp,
       lastchangeuserid double,
       primary key(id));

#Arai Change

create table invponotes (
       orderid double not null unique,
       note text,
       lastchangedate timestamp,
       lastchangeuserid double not null,
       primary key(orderid));
       
create table currency (
      id double NOT NULL auto_increment,
      countryname varchar(50) NOT NULL, 
      currencyname varchar(15) NOT NULL,
      currencysymbol char(3) NOT NULL,
      decimalplace double NOT NULL default 0,
      iso4217 char(3) NOT NULL,
      PRIMARY KEY(id));
