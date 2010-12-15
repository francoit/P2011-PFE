#*********
# Sample Chart of Account for Aria
#*********

#*********
# Special Thank To
# Kurt Howerton
# Senior Consultant
# Grabstein Consulting
# grbstein@yahoo.com
# http://www.xmission.com/~grbstein
# This files is from http://www.xmission.com/~grbstein/downloads/coa_demo_mysql.sql
# with modification to Suit Aria Better.
#*********

use aria;
DELETE FROM glaccount;
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (1, '10000','Petty Cash',10,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (2, '10100','Cash on Hand',10,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (3, '10200','Regular Checking Account',10,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (4, '10300','Payroll Checking Account',10,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (5, '10400','Savings Account',10,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (6, '11000','Accounts Receivable',10,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (7, '11100','Contracts Receivable',10,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (8, '11400','Other Receivables',10,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (9, '11500','Allowance for Doubtful Ac',10,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (10, '12000','Inventory',10,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (11, '14000','Prepaid Expenses',13,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (12, '14100','Employee Advances',13,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (13, '14200','Notes Receivable-Current',13,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (14, '14700','Other Current Assets',13,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (15, '15000','Furniture and Fixtures',11,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (16, '15100','Equipment',11,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (17, '15200','Vehicles',11,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (18, '15300','Other Depreciable Property',11,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (19, '15400','Leasehold Improvements',11,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (20, '15500','Buildings',11,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (21, '15600','Building Improvements',11,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (22, '16900','Land',11,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (23, '17000','Accum. Depreciation-Furnit',10,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (24, '17100','Accum. Depreciation-Equip',10,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (25, '17200','Accum. Depreciation-Vehic',10,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (26, '17300','Accum. Depreciation-Other',10,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (27, '17400','Accum. Depreciation-Lease',10,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (28, '17500','Accum. Depreciation-Buildi',10,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (29, '17600','Accum. Depreciation-Bldg I',10,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (30, '19000','Deposits',13,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (31, '19100','Organization Costs',13,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (32, '19150','Accum Amortiz - Organiz C',13,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (33, '19200','Notes Receivable- Noncurre',13,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (34, '19900','Other Noncurrent Assets',13,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (35, '20000','Accounts Payable',21,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (36, '23000','Accrued Expenses',23,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (37, '23100','Sales Tax Payable',23,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (38, '23200','Wages Payable',23,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (39, '23300','401 K Deductions Payable',23,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (40, '23400','Federal Payroll Taxes Payab',23,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (41, '23500','FUTA Tax Payable',23,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (42, '23600','State Payroll Taxes Payable',23,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (43, '23700','SUTA Tax Payable',23,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (44, '23800','Local Payroll Taxes Payable',23,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (45, '23900','Income Taxes Payable',23,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (46, '24000','Other Taxes Payable',23,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (47, '24100','Current Portion Long-Term',23,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (48, '24300','Contracts Payable-Current',23,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (49, '24700','Other Current Liabilities',23,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (50, '24800','Suspense-Clearing Account',23,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (51, '27000','Notes Payable-Noncurrent',22,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (52, '27100','Contracts Payable- Noncurr',22,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (53, '27400','Other Long-Term Liabilities',22,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (54, '39002','Beginning Balance Equity',30,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (55, '39003','Common Stock',30,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (56, '39004','Paid-in Capital',30,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (57, '39005','Retained Earnings',40,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (58, '39007','Dividends Paid',40,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (59, '40000','Sales',50,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (60, '40000-C','Sales-Construction',50,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (61, '40000-R','Sales-Retail',50,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (62, '40800','Interest Income',90,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (63, '41000','Other Income',90,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (64, '45400','Finance Charge Income',90,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (65, '48000','Sales Returns and Allowanc',60,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (66, '49000','Sales Discounts',60,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (67, '50000','Product Cost',70,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (68, '50000-C','Product Cost-Construction',70,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (69, '50000-R','Product Cost-Retail',70,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (70, '57000','Direct Labor',70,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (71, '57000-C','Direct Labor - Construction',70,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (72, '57000-R','Direct Labor - Retail',70,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (73, '57200','Materials Cost',70,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (74, '57200-C','Materials Cost - Constructio',70,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (75, '57200-R','Materials Cost - Retail',70,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (76, '57300','Subcontractors',70,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (77, '57300-C','Subcontractors',70,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (78, '57500','Freight',80,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (79, '58000','Other',80,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (80, '58500','Inventory Adjustments',80,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (81, '59000','Purchase Returns and Allow',80,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (82, '59500','Purchase Discounts',80,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (83, '60000','Advertising Expense',80,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (84, '60500','Amortization Expense',80,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (85, '61000','Auto Expenses',80,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (86, '61500','Bad Debt Expense',80,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (87, '62000','Bank Charges',80,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (88, '62500','Cash Over and Short',80,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (89, '63000','Charitable Contributions Ex',80,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (90, '63500','Commissions and Fees Exp',80,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (91, '64000','Depreciation Expense',80,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (92, '64500','Dues and Subscriptions Exp',80,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (93, '65000','Employee Benefit Programs',80,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (94, '65500','Freight Expense',80,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (95, '66000','Gifts Expense',80,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (96, '66500','Income Tax Expense',80,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (97, '67000','Insurance Expense',80,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (98, '67500','Interest Expense',80,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (99, '68000','Laundry and Cleaning Exp',80,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (100, '68500','Legal and Professional Exp',80,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (101, '69000','Licenses Expense',80,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (102, '69500','Loss on NSF Checks',80,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (103, '70000','Maintenance Expense',80,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (104, '70500','Meals and Entertainment Ex',80,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (105, '71000','Office Expense',80,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (106, '71500','Other Taxes',80,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (107, '72000','Payroll Tax Exp',80,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (108, '72500','Penalties and Fines Exp',80,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (109, '73000','Pension/Profit-Sharing Plan',80,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (110, '73100','Printing Offsite Expense',80,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (111, '73500','Postage Expense',80,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (112, '74000','Rent or Lease Expense',80,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (113, '74500','Repairs Expense',80,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (114, '75000','Salaries Expense',80,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (115, '75100','Salaries Bonus Expense',80,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (116, '75200','Salaries Commsion Expense',80,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (117, '75500','Supplies Expense',80,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (118, '76000','Telephone Expense',80,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (119, '76500','Travel Expense',80,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (120, '77000','Utilities Expense',80,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (121, '77500','Wages Expense',80,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (122, '89000','Other Expense',80,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (123, '89500','Purchase Disc- Expense Ite',80,0,0);
INSERT INTO glaccount (id, name, description, accounttypeid, companyid, summaryaccountid) VALUES (124, '90000','Gain/Loss on Sale of Assets',80,0,0);
