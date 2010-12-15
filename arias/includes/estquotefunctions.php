<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
     function estquotestockadd($name,$orderflag) {
          global $conn, $lang, $userid;
          checkpermissions('est');
          if ($conn->Execute("insert into estquotegenstock (name,orderflag, entrydate, entryuserid, lastchangeuserid) VALUES (".sqlprep($name).",".sqlprep($orderflag).", NOW(), ".sqlprep($userid).", ".sqlprep($userid).")") === false) {
               echo texterror("Error adding stock.");
               return 0;
          } else {
               echo textsuccess("Stock added successfully.");
               return 1;
          };
     };

     function formestquotestockadd() {
     	  global $conn, $lang;
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Stock Name:</td><td><input type="text" name="name" size="30" maxlength="30"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Sort Priority:</td><td><input type="text" name="orderflag" size="10" maxlength="10" value="50"'.INC_TEXTBOX.'></td></tr>';
          return 1;
     };

     function estquotestockupd($name,$id,$orderflag,$lastchangedate) {
          global $conn, $lang, $userid;
          checkpermissions('est');
          $recordSet=&$conn->Execute("select count(*) from estquotegenstock where id=".sqlprep($id)." and lastchangedate=".sqlprep($lastchangedate));
          if (!$recordSet->EOF) {
               if ($recordSet->fields[0]==0) {
                    showwhochanged($id,"stock","id");
                    return 0;
               };
          };
          if ($conn->Execute('update estquotegenstock set name='.sqlprep($name).', orderflag='.sqlprep($orderflag).', lastchangeuserid='.sqlprep($userid).', lastchangedate=NOW() where id='.sqlprep($id)) === false) {
               echo texterror("Error updating stock.");
               return 0;
          } else {
               echo textsuccess("Stock updated successfully.");
               return 1;
          };
     };

     function formestquotestockupd($genstockid,$name,$orderflag) {
          global $conn, $lang, $userid;
          $recordSet=&$conn->Execute('select name,lastchangedate,orderflag from estquotegenstock where id='.sqlprep($genstockid));
          if (!$recordSet->EOF) {
                $name=$recordSet->fields[0];
                $lastchangedate=$recordSet->fields[1];
                $orderflag=$recordSet->fields[2];
          } else { // how could this happen??
            die(texterror("Could not find stock to edit"));
          };
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Stock Name:</td><td><input type="text" name="name" size="30" maxlength="30" value="'.$name.'"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Sort Priority:</td><td><input type="text" name="orderflag" size="10" maxlength="10" value="'.$orderflag.'"'.INC_TEXTBOX.'></td></tr>';
          echo '<input type="hidden" name="lastchangedate" value="'.$lastchangedate.'">';
          echo '<input type="hidden" name="genstockid" value="'.$genstockid.'">';

          return 1;
     };

     function formestquotesubstockadd($name,$weight,$turnaround,$substockname,$suborderflag,$parts,$inchesperm) {
              global $conn, $lang,$userid;
              if (!$suborderflag) $suborderflag=50;
              echo '<tr><th> Stock = '.$name.'</th></tr>';
              echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Subgroup Name:</td><td><input type="text" name="substockname" size="50" maxlength="50" value="'.$substockname.'"'.INC_TEXTBOX.'></td></tr>';
              echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Weight/M 8.5 x 11 Sheets:</td><td><input type="text" name="weight" size="10" maxlength="10" value="'.$weight.'"'.INC_TEXTBOX.'></td></tr>';
              echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Turnaround Days:</td><td><input type="text" name="turnaround" size="3" maxlength="3" value="'.$turnaround.'"'.INC_TEXTBOX.'></td></tr>';
              echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Sort Priority:</td><td><input type="text" name="suborderflag" size="10" maxlength="10" value="'.$suborderflag.'"'.INC_TEXTBOX.'></td></tr>';
              echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Number of Parts:</td><td><input type="text" name="parts" size="1" maxlength="1" value="'.$parts.'"'.INC_TEXTBOX.'></td></tr>';
              echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Inches Height per 1000 sheets:</td><td><input type="text" name="inchesperm" size="5" maxlength="5" value="'.$inchesperm.'"'.INC_TEXTBOX.'></td></tr>';
    };

    function estproptionid2name($proption) {
        switch ($proption) {
            case SD_OFFSET_SHEET:
                 return 'Offset - Sheet';
                 break;
            case SD_OFFSET_WEB:
                 return 'Offset - Web';
                 break;
            case SD_DIGITAL:
                 return 'Digital';
                 break;
            case SD_SCREEN:
                 return 'Screen';
                 break;
            case SD_FLEXO:
                 return 'Flexo';
                 break;
            case SD_VENDED:
                 return 'Vended';
                 break;
            default:
                 return $proption;
                 break;
        };
    };

    function estcctype2name($cctype) {
        switch ($cctype) {
            case SD_CCTYPE_PREFLIGHT:
                 return $lang['STR_PREFLIGHT'];
                 break;
            case SD_CCTYPE_PROOF:
                 return $lang['STR_PROOF'];
                 break;
            case SD_CCTYPE_PREPRESS:
                 return $lang['STR_PREPRESS'];
                 break;
            case SD_CCTYPE_INK:
                 return $lang['STR_INK'];
                 break;
            case SD_CCTYPE_PAPER:
                 return $lang['STR_PAPER'];
                 break;
            case SD_CCTYPE_PRINT:
                 return $lang['STR_PRINT'];
                 break;
            case SD_CCTYPE_FINISH:
                 return $lang['STR_FINISH'];
                 break;
            case SD_CCTYPE_QC:
                 return $lang['STR_QC'];
                 break;
            case SD_CCTYPE_SHIP:
                 return $lang['STR_SHIP'];
                 break;
        };
    };

    function estproptionsheetorroll($proption) {
       switch ($proption) {
          case SD_OFFSET_SHEET:
             $stocktype=SD_SHEET;
          case SD_OFFSET_WEB:
             $stocktype=SD_ROLL;
          case SD_DIGITAL:
             $stocktype=SD_SHEET;
          case SD_SCREEN:
             $stocktype=SD_SHEET;
          case SD_FLEXO:
             $stocktype=SD_ROLL;
             break;
       };
    };

    function estcalc($quoteid,$qty,$showcost=0,$costcenter=0) {
        global $conn, $lang,$userid,$active_company;
        $recordSet=&$conn->Execute('select worktypeid,prpriceid,numpages,finishwidth,finishheight from estquote where id='.sqlprep($quoteid));
        if (!$recordSet||$recordSet->EOF) die(texterror($lang['STR_QUOTE_NOT_FOUND']));
        $worktypeid=$recordSet->fields[0];
        $numpages=$recordSet->fields[2];
        extract(estcalcpaperinfo($quoteid)); //get paper info
        $sides=estcalcinksides($quoteid); //get num of sides
        extract(estcalcpresssheets($parts,$qty,$numpages,$sides,$numberup,$numberout));
        $distinctnbinks=estcalcdistinctnbinks($quoteid); //number of distinct non-black inks
        $the85equiv=estcalc85equiv($width,$length); //num of equivalent 8.5x11 sheets
        extract(estcalcnpopprice($quoteid,$qty,$parts,$numberup,$sides,$distinctnbinks,$the85equiv));
        extract(estcalcnpopcost($quoteid,$qty,$pqty,$parts,$numberup,$sides,$height,$totlength));
        extract(estcalcprintcost($quoteid,$qty,$pqty,$sqty));
        ${'price'.SD_CCTYPE_PRINT}=estcalcprintprice($quoteid,$qty,$pqty,$parts);
        if (!$showcost&&!$costcenter) {
            return ${'price'.SD_CCTYPE_PRINT};
        } elseif (!$showcost&&$costcenter) {
            $price=array();
            for ($i=1;$i<=9;$i++) $price[$i]=${'price'.$i};
            return $price;
        } elseif ($showcost&&!$costcenter) {
            $price=array();
            $price['price']=$price1+$price2+$price3+$price4+$price5+$price6+$price7+$price8+$price9;
            $price['costhrs']=$costhrs1+$costhrs2+$costhrs3+$costhrs4+$costhrs5+$costhrs6+$costhrs7+$costhrs8+$costhrs9;
            $price['costlabor']=$costlabor1+$costlabor2+$costlabor3+$costlabor4+$costlabor5+$costlabor6+$costlabor7+$costlabor8+$costlabor9;
            $price['costequip']=$costequip1+$costequip2+$costequip3+$costequip4+$costequip5+$costequip6+$costequip7+$costequip8+$costequip9;
            $price['costmatl']=$costmatl1+$costmatl2+$costmatl3+$costmatl4+$costmatl5+$costmatl6+$costmatl7+$costmatl8+$costmatl9;
            $price['costovhd']=$costfactovhd1+$costfactovhd2+$costfactovhd3+$costfactovhd4+$costfactovhd5+$costfactovhd6+$costfactovhd7+$costfactovhd8+$costfactovhd9+$costgenovhd1+$costgenovhd2+$costgenovhd3+$costgenovhd4+$costgenovhd5+$costgenovhd6+$costgenovhd7+$costgenovhd8+$costgenovhd9;
            $price['costmarkup']=$costmarkup1+$costmarkup2+$costmarkup3+$costmarkup4+$costmarkup5+$costmarkup6+$costmarkup7+$costmarkup8+$costmarkup9;
            $price['costtotal']=$costtotal1+$costtotal2+$costtotal3+$costtotal4+$costtotal5+$costtotal6+$costtotal7+$costtotal8+$costtotal9;
            return $price;
        } elseif ($showcost&&$costcenter) {
            $price=array();
            for ($i=1;$i<=9;$i++) {
                $price['price'.$i]=${'price'.$i};
                $price['costhrs'.$i]=${'costhrs'.$i};
                $price['costlabor'.$i]=${'costlabor'.$i};
                $price['costequip'.$i]=${'costequip'.$i};
                $price['costmatl'.$i]=${'costmatl'.$i};
                $price['costovhd'.$i]=${'costfactovhd'.$i}+${'costgenovhd'.$i};
                $price['costmarkup'.$i]=${'costmarkup'.$i};
                $price['costtotal'.$i]=${'costtotal'.$i};
            };
            return $price;
        };
    };

    function estcalcnpopprice($quoteid,$qty,$parts,$numberup,$sides,$distinctnbinks,$the85equiv) {
        global $conn, $lang,$userid,$active_company;
        $recordSet=&$conn->Execute('select estnpoperationsoptionsize.pricesetupaddl,estnpoperationsoptionsizeaddloptions.pricesetupaddl,estnpoperationsoptionsize.priceamountaddl,estnpoperationsoptionsizeaddloptions.priceamountaddl,estnpoperationsoptionsize.priceminimumaddl,estnpoperationsoptionsizeaddloptions.priceminimumaddl,estnpoperationsoptionsize.pricecalchow,greatest(estquotequest.yesno,estquotequest.selection,estquotequest.text),estcostcenter.cctype from estquotequest,estnpoperations,estnp,estmachine,estnpoperationsoptionsize,estprprice,estquote,estcostcenter,estcostcentersubtype,estquotestdsize as a, estquotestdsize as b left join estnpoperationsoptionsizeaddloptions on estquotequest.selection=estnpoperationsoptionsizeaddloptions.estnpoperationsoptionsid and estnpoperationsoptionsizeaddloptions.estnpoperationsizeid=estnpoperationsoptionsize.id where estquote.id='.sqlprep($quoteid).' and estquote.prpriceid=estprprice.id and estnpoperationsoptionsize.estquotestdsizeid=a.id and estprprice.estquotestdsizeid=b.id and a.length>=b.length and a.width>=b.width and estnpoperationsoptionsize.estnpoperationsid=estnpoperations.id and estquotequest.questionid=estnpoperations.id and estnpoperations.estnpid=estnp.id and estmachine.costcentersubtypeid=estcostcentersubtype.id and estnp.machineid=estmachine.id and estcostcenter.id=estcostcentersubtype.costcenterid and estquotequest.quoteid='.sqlprep($quoteid).' and estmachine.gencompanyid='.sqlprep($active_company).' and estnp.gencompanyid='.sqlprep($active_company).' group by estquotequest.id');
        while ($recordSet&&!$recordSet->EOF) {
            $setup=$recordSet->fields[0]+$recordSet->fields[1];
            $amount=$recordSet->fields[2]+$recordSet->fields[3];
            $minimum=$recordSet->fields[4]+$recordSet->fields[5];
            $calchow=$recordSet->fields[6];
            $answer=$recordSet->fields[7];
            $cctype=$recordSet->fields[8];
            switch ($calchow) {
                case SD_PER_JOB:
                    $amount=$amount;
                    break;
                case SD_PER_M:
                    $amount*=$qty/1000;
                    break;
                case SD_PER_M_PER_PART:
                    $amount*=$qty/1000*$parts;
                    break;
                case SD_PER_M_PER_SIDE:
                    $amount*=$qty/1000*$sides;
                    break;
                case SD_EXTRA_TIMES:
                    $amount*=$answer;
                    break;
                case SD_PER_M_DIV_ANSWER_TIMES_CHOICE:
                    $amount*=$qty/1000/$answer;
                    break;
                case SD_TIMES_COLORS:
                    $amount*=$distinctnbinks;
                    break;
                case SD_PER_M_DIV_NUMUP:
                    $amount*=$qty/1000*$numberup;
                    break;
                case SD_PER_EQUIV_SHEETS:
                    $amount*=$qty/1000*$parts*$sides*$the85equiv;
                    break;
                case SD_NO_CHARGE:
                    $amount=0;
                    break;
            };
            $amount+=$setup;
            if ($amount<$minimum) $amount=$minimum;
            ${'totamount'.$cctype}+=$amount;
            $recordSet->MoveNext();
        };
        $totamount=array();
        for ($i=1;$i<=9;$i++) $totamount['price'.$i]=num_format(${'totamount'.$i},PREFERRED_DECIMAL_PLACES);
        return $totamount;
    };

    function estcalcnpopcost($quoteid,$qty,$pqty,$parts,$numberup,$sides,$height,$totlength) {
        global $conn, $lang,$userid,$active_company;
        $cost=array();
        $recordSet=&$conn->Execute('select estnpoperationsoptionsize.costhourssetup,estnpoperationsoptionsizeaddloptions.costhourssetup,estnpoperationsoptionsize.costhoursrun,estnpoperationsoptionsizeaddloptions.costhoursrun,estnpoperationsoptionsize.costcalchow,greatest(estquotequest.yesno,estquotequest.selection,estquotequest.text),estmachine.factoverhead,estmachine.genoverhead,estmachine.markup,estmachine.costmachperhr,estmachine.costoperperhr,estnpoperationsoptionsize.costnumoperators,estnpoperationsoptionsizeaddloptions.costnumoperators,estnpoperationsoptionsize.percenthoursmantomachine/100,estmachine.costasstperhr,estnpoperationsoptionsize.costnumassistants,estnpoperationsoptionsizeaddloptions.costnumassistants,estcostcenter.cctype from estquotequest,estnpoperations,estnp,estmachine,estnpoperationsoptionsize,estprprice,estquote,estcostcenter,estcostcentersubtype,estquotestdsize as a,estquotestdsize as b left join estnpoperationsoptionsizeaddloptions on estquotequest.selection=estnpoperationsoptionsizeaddloptions.estnpoperationsoptionsid and estnpoperationsoptionsizeaddloptions.estnpoperationsizeid=estnpoperationsoptionsize.id where estquote.id='.sqlprep($quoteid).' and estcostcenter.id=estcostcentersubtype.costcenterid and estcostcentersubtype.id=estmachine.costcentersubtypeid and estquote.prpriceid=estprprice.id and estnpoperationsoptionsize.estquotestdsizeid=a.id and estprprice.estquotestdsizeid=b.id and a.length>=b.length and a.width>=b.width and estnpoperationsoptionsize.estnpoperationsid=estnpoperations.id and estquotequest.questionid=estnpoperations.id and estnpoperations.estnpid=estnp.id and estnp.machineid=estmachine.id and estquotequest.quoteid='.sqlprep($quoteid).' and estmachine.gencompanyid='.sqlprep($active_company).' and estnp.gencompanyid='.sqlprep($active_company).' group by estquotequest.id order by estcostcenter.cctype');
        while ($recordSet&&!$recordSet->EOF) {
            $basemachinecost=$recordSet->fields[9]+($recordSet->fields[10]*$recordSet->fields[13]*($recordSet->fields[11]+$recordSet->fields[12]))+($recordSet->fields[14]*$recordSet->fields[13]*($recordSet->fields[15]+$recordSet->fields[16]));
            $setupmachinecost=$recordSet->fields[9]+($recordSet->fields[10]*($recordSet->fields[11]+$recordSet->fields[12]))+($recordSet->fields[14]*($recordSet->fields[15]+$recordSet->fields[16]));
            $cctype=$recordSet->fields[17];
            $hourssetup=$recordSet->fields[0]+$recordSet->fields[1];
            $costsetup=$hourssetup*$setupmachinecost;
            $rate=$recordSet->fields[2];
            if ($rate==0||$recordSet->fields[3]<$rate) $rate=$recordSet->fields[3];
            if ($rate==0) $rate=1;
            $rate=num_format($qty/$rate,2);
            $calchow=$recordSet->fields[4];
            $answer=$recordSet->fields[5];
            $factoverhead=$recordSet->fields[6]/100;
            $genoverhead=$recordSet->fields[7]/100;
            $markup=$recordSet->fields[8]/100;
            switch ($calchow) {
                case SD_QTY_DIV_M:
                    $amount=$qty/1000/$rate;
                    break;
                case SD_TIMES_ONE:
                    $amount=$rate;
                    break;
                case SD_QTY_TIMES_PARTS_DIV_M:
                    $amount=$rate*$qty/1000*$parts;
                    break;
                case SD_QTY_TIMES_SIDES_DIV_M:
                    $amount=$rate*$qty/1000*$sides;
                    break;
                case SD_TIMES_REPLY:
                    $amount=$rate*$answer;
                    break;
                case SD_QTY_DIV_REPLY:
                    if ($answer==0) $answer=1;
                    $amount=$rate*$qty/1000/$answer;
                    break;
                case SD_PER_M_DIV_NUMUP:
                    $amount=$rate*$qty/1000/$numberup;
                    break;
                case SD_TIMES_PQTY:
                    $amount=$rate*$pqty/1000;
                    break;
                case SD_TIMES_PQTY_TIMES_INCHES:
                    $amount=$pqty/1000*$height/$rate;
                    break;
                case SD_TIMES_PQTY_TIMES_LENGTH:
                    $amount=$pqty/1000*$totlength/$rate;
                    break;
                case SD_TIMES_PQTY_TIMES:
                    $amount=0;
                    break;
            };
            $cost['costhrs'.$cctype]+=num_format($hourssetup+$amount,2);
            $cost['costlabor'.$cctype]+=num_format(($amount*($basemachinecost-$recordSet->fields[9]))+($hourssetup*($setupmachinecost-$recordSet->fields[9])),PREFERRED_DECIMAL_PLACES);
            $cost['costequip'.$cctype]+=num_format($recordSet->fields[7]*$cost['costhrs'.$cctype],PREFERRED_DECIMAL_PLACES);
            $cost['costmatl'.$cctype]+=estcalcnpopcostmaterials($quoteid,$cctype);
            $cost['costfactovhd'.$cctype]+=num_format(($cost['costequip'.$cctype]+$cost['costlabor'.$cctype])*$factoverhead,PREFERRED_DECIMAL_PLACES);
            $cost['costgenovhd'.$cctype]+=num_format((($cost['costequip'.$cctype]+$cost['costlabor'.$cctype])+$cost['costfactovhd'.$cctype]+$cost['costmatl'.$cctype])*$genoverhead,PREFERRED_DECIMAL_PLACES);
            $cost['costmarkup'.$cctype]+=num_format((($cost['costequip'.$cctype]+$cost['costlabor'.$cctype])+$cost['costfactovhd'.$cctype]+$cost['costmatl'.$cctype]+$cost['costgenovhd'.$cctype])*$markup,PREFERRED_DECIMAL_PLACES);
            $cost['costtotal'.$cctype]=num_format($cost['costlabor'.$cctype]+$cost['costequip'.$cctype]+$cost['costfactovhd'.$cctype]+$cost['costmatl'.$cctype]+$cost['costgenovhd'.$cctype]+$cost['costmarkup'.$cctype],PREFERRED_DECIMAL_PLACES);
            $recordSet->MoveNext();
        };
        return $cost;
    };

    function estcalcprintprice($quoteid,$qty,$pqty,$parts) {
        global $conn, $lang,$userid,$active_company;
        $recordSet=&$conn->SelectLimit('select estprpriceoptionprice.amount, estprpricestockusagestock.addlchargeperm, estprpricestockusagestock.addlminimum, estprpriceoptionprice.qtycalchow, estquotestock.flatwidth, estquotestock.flatheight, estquotestock.id, estquotestock.numpages from estquote, estquotestock, estprprice, estprpriceoptionprice, estprpricestockusage, estprpricestockusagestock, estquotesubstock, estquotestdsize where estquotestdsize.id=estprprice.estquotestdsizeid and estprpriceoptionprice.stockusageid=estprpricestockusage.id and estprpricestockusagestock.stockusageid=estprpricestockusage.id and estprpricestockusage.prpriceid=estprprice.id and estquotestock.quoteid=estquote.id and estquote.prpriceid=estprprice.id and estquote.id='.sqlprep($quoteid).' and estprpriceoptionprice.qty>=1000 and estprpricestockusage.maxinksfront>='.sqlprep(estnuminksside($quoteid,1)).' and estprpricestockusage.maxinksback>='.sqlprep(estnuminksside($quoteid,2)).' and estprpriceoptionprice.proption=greatest(estprpricestockusage.proptionone*(estprpricestockusage.proptionqty>='.$qty.'),estprpricestockusage.proptiontwo*('.$qty.'>estprpricestockusage.proptionqty)) and estprpriceoptionprice.qty>='.sqlprep($qty).' order by estprpriceoptionprice.qty',1);
        if ($recordSet&&!$recordSet->EOF) {
            $amount=$recordSet->fields[0];
            $addlcharge=$recordSet->fields[1];
            $minimum=$recordSet->fields[2];
            $calchow=$recordSet->fields[3];
            $width=$recordSet->fields[4];
            $length=$recordSet->fields[5];
            $quotestockid=$recordSet->fields[6];
            $numpages=$recordSet->fields[7];
            $the85equiv=estcalc85equiv($width,$length);
            $distinctnbinks=estcalcdistinctnbinkspart($quoteid,$quotestockid);
            $sides=estcalcinksidespart($quoteid,$quotestockid);
            switch ($calchow) {
                case SD_PRESSQTY:
                    $calcqty=$pqty;
                    break;
                case SD_FINALQTY:
                    $calcqty=$qty;
                    break;
                case SD_PRESSQTY_TIMES_SIDES:
                    $calcqty=$pqty*$sides;
                    break;
                case SD_FINALQTY_TIMES_PAGES:
                    $calcqty=$qty*$pages;
                    break;
                case SD_LETTER_EQUIV_SHEETS_TIMES_QTY:
                    $calcqty=ceil($pqty*$the85equiv);
                    break;
                case SD_FINALQTY_TIMES_PAGES_TIMES_SIDES:
                    $calcqty=$qty*$pages*$sides;
                    break;
                case SD_FINALQTY_TIMES_SIDES_TIMES_PARTS:
                    $calcqty=$qty*$sides*$parts;
                    break;
                case SD_FINALQTY_TIMES_PARTS:
                    $calcqty=$qty*$parts;
                    break;
                case SD_PRINTQTY_TIMES_COLORS:
                    $calcqty=$pqty*$distinctnbinks;
                    break;
                case SD_PRINTQTY_TIMES_FEETLENGTH:
                    $calcqty=$pqty*$length;
                    break;
            };
            $pricerun=$amount*$calcqty/1000;
            if ($minimum>$pricerun) $pricerun=$minimum;
            $totprice+=$pricerun;
        };
        return num_format($totprice,PREFERRED_DECIMAL_PLACES);
    };

    function estcalcprintcost($quoteid,$qty,$pqty,$sqty) {
        global $conn, $lang,$userid,$active_company;
        $cost=array();
        $recordSet=&$conn->Execute('select estprpriceoptioncosts.hrsgeneralsetup, estprpriceoptioncosts.hrscolorsetup, sum(estprpricetools.addlsetuphrspress), estprpriceoptioncosts.runrateqtytype, estquotestock.flatwidth, estquotestock.flatheight, estquotestock.id, estquotestock.numpages, estmachine.factoverhead, estmachine.genoverhead, estmachine.markup, estmachine.costmachperhr, estmachine.costoperperhr, estprpriceoptioncosts.numoperators,estprpriceoptioncosts.mantomachine/100,estmachine.costasstperhr,estprpriceoptioncosts.numassistants, estprpricestockusagestock.id from estquote, estquotestock, estprprice, estprpriceoptioncosts, estprpricestockusage, estprpricestockusagestock, estquotesubstock, estmachine, estquotestdsize left join estquotestocktool on estquotestocktool.quotestockid=estquotestock.id left join estprpricetools on estprpricetools.id=estquotestocktool.toolid where estquotestdsize.id=estprprice.estquotestdsizeid and estprpriceoptioncosts.stocklistid=estprpricestockusagestock.id and estmachine.id=estprpriceoptioncosts.estmachineid and estprpricestockusagestock.stockusageid=estprpricestockusage.id and estprpricestockusage.prpriceid=estprprice.id and estquotestock.quoteid=estquote.id and estquote.prpriceid=estprprice.id and estquote.id='.sqlprep($quoteid).' group by estprpriceoptioncosts.id');
        while ($recordSet&&!$recordSet->EOF) {
            $quotestockid=$recordSet->fields[6];
            $distinctnbinks=estcalcdistinctnbinkspart($quoteid,$quotestockid);
            $hourssetup=$recordSet->fields[0]+($recordSet->fields[1]*$distinctnbinks)+$recordSet->fields[2];
            $basemachinecost=($recordSet->fields[11]+$recordSet->fields[12])*$recordSet->fields[13]*$recordSet->fields[14]+($recordSet->fields[11]+$recordSet->fields[15])*$recordSet->fields[16]*$recordSet->fields[14];
            $setupmachinecost=($recordSet->fields[11]+$recordSet->fields[12])*$recordSet->fields[13]+($recordSet->fields[11]+$recordSet->fields[15])*$recordSet->fields[16];
            $costsetup=$setupmachinecost*$hourssetup;
            $calchow=$recordSet->fields[3];
            $width=$recordSet->fields[4];
            $length=$recordSet->fields[5];
            $quotestockid=$recordSet->fields[6];
            $numpages=$recordSet->fields[7];
            $factoverheadpress=$recordSet->fields[8]/100;
            $genoverheadpress=$recordSet->fields[9]/100;
            $markuppress=$recordSet->fields[10]/100;
            $stocklistid=$recordSet->fields[17];
            $the85equiv=estcalc85equiv($width,$length);
            $sides=estcalcinksidespart($quoteid,$quotestockid);
            switch ($calchow) {
                case SD_PRESSQTY:
                    $calcqty=$pqty;
                    break;
                case SD_FINALQTY:
                    $calcqty=$qty;
                    break;
                case SD_PRINTQTY_TIMES_SIDES:
                    $calcqty=$pqty*$sides;
                    break;
                case SD_FINALQTY_TIMES_PAGES:
                    $calcqty=$qty*$pages;
                    break;
                case SD_LETTER_EQUIV_SHEETS_TIMES_QTY:
                    $calcqty=ceil($pqty*$the85equiv);
                    break;
                case SD_FINALQTY_TIMES_PAGES_TIMES_SIDES:
                    $calcqty=$qty*$pages*$sides;
                    break;
                case SD_FINALQTY_TIMES_SIDES_TIMES_PARTS:
                    $calcqty=$qty*$sides*$parts;
                    break;
                case SD_FINALQTY_TIMES_PARTS:
                    $calcqty=$qty*$parts;
                    break;
                case SD_PRINTQTY_TIMES_COLORS:
                    $calcqty=$pqty*$distinctnbinks;
                    break;
                case SD_PRINTQTY_TIMES_FEETLENGTH:
                    $calcqty=$pqty*$length;
                    break;
            };
            unset($factoverheadpaper);
            unset($genoverheadpaper);
            unset($markuppaper);
            $recordSet2=&$conn->Execute('select estmachine.factoverhead,estmachine.genoverhead,estmachine.markup from estcostcentersubtype,estmachine,estcostcenter where estcostcentersubtype.id=estmachine.costcentersubtypeid and estcostcentersubtype.costcenterid=estcostcenter.id and estcostcenter.cancel=0 and estcostcenter.subtype.cancel=0 and estmachine.cancel=0 and estmachine.gencompanyid='.sqlprep($active_company).' and estcostcenter.cctype='.sqlprep(SD_CCTYPE_PAPER));
            if ($recordSet2&&!$recordSet2->EOF) {
                $factoverheadpaper=$recordSet2->fields[0]/100;
                $genoverheadpaper=$recordSet2->fields[1]/100;
                $markuppaper=$recordSet2->fields[2]/100;
            };
            $recordSet2=&$conn->Execute('select estquotesubstockcost.cost,estquotesubstockcost.costhow,estquotesubstockcost.width,estquotesubstockcost.length,estquotesubstock.weight from estquotestock,estprpricestockusagestock,estquotesubstockcost,estquotesubstock where estquotestock.stocklistid=estprpricestockusagestock.id and estquotesubstock.id=estquotesubstockcost.substockid and estprpricestockusagestock.substockid=estquotesubstockcost.substockid and estquotestock.id='.sqlprep($quotestockid));
            if ($recordSet2&&!$recordSet2->EOF) { //paper costs
                $pwidth=$recordSet2->fields[2];
                $plength=$recordSet2->fields[3];
                $costpaper=$sqty*$recordSet2->fields[0];
                switch ($recordSet2->fields[1]) { //calchow
                     case SD_PER_M:
                          $costpaper/=1000;
                          break;
                     case SD_PER_LB:
                          if ($recordSet2->fields[4]) $costpaper/=$recordSet2->fields[4]/1000;
                          break;
                     case SD_PER_EACH:
                          //no addl calc
                          break;
                     case SD_PER_MSI:
                          $costpaper/=1000*$pwidth*$plength;
                          break;
                };
            };
            $tarea=$pqty*$pwidth*$plength;
            unset($costink);
            unset($factoverheadink);
            unset($genoverheadink);
            unset($markupink);
            $recordSet2=&$conn->Execute('select estmachine.factoverhead,estmachine.genoverhead,estmachine.markup from estcostcentersubtype,estmachine,estcostcenter where estcostcentersubtype.id=estmachine.costcentersubtypeid and estcostcentersubtype.costcenterid=estcostcenter.id and estcostcenter.cancel=0 and estcostcenter.subtype.cancel=0 and estmachine.cancel=0 and estmachine.gencompanyid='.sqlprep($active_company).' and estcostcenter.cctype='.sqlprep(SD_CCTYPE_INK));
            if ($recordSet2&&!$recordSet2->EOF) {
                $factoverheadink=$recordSet2->fields[0]/100;
                $genoverheadink=$recordSet2->fields[1]/100;
                $markupink=$recordSet2->fields[2]/100;
            };
            $recordSet2=&$conn->Execute('select estquoteink.costper,estquoteink.coverage from estquotestock,estquotestockink,estquoteink where estquotestock.id=estquotestockink.quotestockid and estquotestockink.inkid=estquoteink.id and estquotestock.id='.sqlprep($quotestockid));
            while ($recordSet2&&!$recordSet2->EOF) { //ink costs
                $costink+=$tarea/$recordSet2->fields[1]*$recordSet2->fields[0];
                $recordSet2->MoveNext();
            };
            $recordSet2=&$conn->Execute('select sum(greatest(estprpriceoptioncosts.runrate*('.$calcqty.'<estprpriceoptioncosts.runqty), estprpriceoptioncosts.runrate2*('.$calcqty.' between estprpriceoptioncosts.runqty and estprpriceoptioncosts.runqty2), estprpriceoptioncosts.runrate3*('.$calcqty.' > estprpriceoptioncosts.runqty2))) from estquote, estquotestock, estprprice, estprpriceoptioncosts, estprpricestockusage, estprpricestockusagestock where estprpriceoptioncosts.stocklistid=estprpricestockusagestock.id and estprpricestockusagestock.stockusageid=estprpricestockusage.id and estprpricestockusage.prpriceid=estprprice.id and estquotestock.quoteid=estquote.id and estquote.prpriceid=estprprice.id and estprpricestockusagestock.id='.sqlprep($stocklistid).' and estquote.id='.sqlprep($quoteid));
            if ($recordSet2&&!$recordSet2->EOF) if ($recordSet2->fields[0]>0) $runtime=num_format($qty/$recordSet2->fields[0],2);
            unset($costtool);
            $recordSet2=&$conn->Execute('select estprpricetools.costperuse,estprpricetools.costperpresshour from estquotestock,estquotestocktool,estprpricetools where estquotestock.id=estquotestocktool.quotestockid and estquotestocktool.toolid=estprpricetools.id and estquotestock.id='.sqlprep($quotestockid));
            while ($recordSet2&&!$recordSet2->EOF) { //tool costs
                $costtool+=$recordSet2->fields[0]+($recordSet->fields[1]*$runtime);
                $recordSet2->MoveNext();
            };
            unset($costmaterials);
            $recordSet2=&$conn->Execute('select sum(estproptioncostsmaterials.cost*estproptioncostsmaterials.per) from estquote, estquotestock, estprprice, estprpriceoptioncostsmaterials, estprpricestockusage, estprpricestockusagestock where estprpriceoptioncostsmaterials.stocklistid=estprpricestockusagestock.id and estprpricestockusagestock.stockusageid=estprpricestockusage.id and estprpricestockusage.prpriceid=estprprice.id and estquotestock.quoteid=estquote.id and estquote.prpriceid=estprprice.id and estprpricestockusagestock.id='.sqlprep($stocklistid).' and estquote.id='.sqlprep($quoteid));
            if ($recordSet2&&!$recordSet2->EOF) $costmaterials=$recordSet2->fields[0];

            $costrun=$runtime*$basemachinecost;
            $costlabor=$costsetup+$costrun;
            $factoverhead=(($costlabor+$costtool)*$factoverheadpress)+($costink*$factoverheadink)+($costpaper*$factoverheadpaper);
            $genoverhead=(((($costlabor+$costtool)*$factoverheadpress)+$costmaterials)*$genoverheadpress)+(($costink*$factoverheadink)*$genoverheadink)+(($costpaper*$factoverheadpaper)*$genoverheadpaper);
            $markup=((((($costlabor+$costtool)*$factoverheadpress)+$costmaterials)*$genoverheadpress)*$markuppress)+((($costink*$factoverheadink)*$genoverheadink)*$markupink)+((($costpaper*$factoverheadpaper)*$genoverheadpaper)*$markuppaper);
            $cost['costhrs'.SD_CCTYPE_PRINT]+=$hourssetup+$runtime;
            $cost['costlabor'.SD_CCTYPE_PRINT]+=$costlabor;
            $cost['costequip'.SD_CCTYPE_PRINT]+=$recordSet->fields[11]*$hourssetup+$runtime;
            $cost['costfactovhd'.SD_CCTYPE_PRINT]+=$costmaterials+$costpaper+$costink+$costtool;
            $cost['costgenovhd'.SD_CCTYPE_PRINT]+=$factoverhead;
            $cost['costgenovhd'.SD_CCTYPE_PRINT]+=$genoverhead;
            $cost['costmarkup'.SD_CCTYPE_PRINT]+=$markup;
            $recordSet->MoveNext();
        };
        $cost['costtotal'.SD_CCTYPE_PRINT]=array_sum($cost);
        return $cost;
    };

    function estcalcnpopcostmaterials($quoteid,$cctype) {
        global $conn, $lang,$userid,$active_company;
        $recordSet=&$conn->Execute('select sum(estnpoperationsoptionsizeaddl.costeach*estnpoperationsoptionsizeaddl.qtyused) from estquotequest,estnpoperations,estnpoperationsoptionsize,estnpoperationsoptionsizeaddl,estnp,estmachine,estcostcentersubtype,estcostcenter where estnpoperations.estnpid=estnp.id and estnp.machineid=estmachine.id and estmachine.costcentersubtypeid=estcostcentersubtype.id and estcostcentersubtype.costcenterid=estcostcenter.id and estcostcenter.cctype='.sqlprep($cctype).' and estnpoperationsoptionsize.estnpoperationsid=estnpoperations.id and estquotequest.questionid=estnpoperations.id and estnpoperationsoptionsizeaddl.estnpoperationsizeid=estnpoperationspotionsize.id and estquotequest.quoteid='.sqlprep($quoteid));
        if ($recordSet&&!$recordSet->EOF) $costmat+=$recordSet->fields[0];
        $recordSet=&$conn->Execute('select sum(estnpoperationsoptionsizeaddloptionsaddl.costeach*estnpoperationsoptionsizeaddloptionsaddl.qtyused) from estquotequest,estnpoperations,estnp,estmachine,estcostcentersubtype,estcostcenter,estnpoperationsoptionsize,estprprice,estquote left join estnpoperationsoptionsizeaddloptions on estquotequest.selection=estnpoperationsoptionsizeaddloptions.estnpoperationsoptionsid and estnpoperationsoptionsizeaddloptions.estnpoperationsizeid=estnpoperationsoptionsize.id left join estnpoperationsoptionsizeaddloptionsaddl on estnpoperationsoptionsizeaddloptionsaddl.estnpoperationsoptionsizeaddloptionsid=estnpoperationsoptionsizeaddloptions.id where estnpoperations.estnpid=estnp.id and estnp.machineid=estmachine.id and estmachine.costcentersubtypeid=estcostcentersubtype.id and estcostcentersubtype.costcenterid=estcostcenter.id and estcostcenter.cctype='.sqlprep($cctype).' and estquote.id='.sqlprep($quoteid).' and estnpoperationsoptionsize.estnpoperationsid=estnpoperations.id and estquotequest.questionid=estnpoperations.id and estquotequest.quoteid='.sqlprep($quoteid));
        if ($recordSet&&!$recordSet->EOF) $costmat+=$recordSet->fields[0];
        return $costmat;
    };

    function estcalcpaperinfo($quoteid) { //get misc paper info for quote
        global $conn, $lang,$userid,$active_company;
        $recordSet=&$conn->Execute('select estprpricestockusagestock.numberout,estprpricestockusagestock.numberup,estprpricestockusagestock.cuts,estquotestdsize.width,estquotestdsize.length,estquotesubstock.inchesperm from estquote,estprprice,estprpricestockusage,estprpricestockusagestock,estquotesubstock,estquotestdsize where estquotestdsize.id=estprprice.estquotestdsizeid and estquote.id='.sqlprep($quoteid).' and estprprice.id=estquote.prpriceid and estprpricestockusage.prpriceid=estprprice.id and estprpricestockusagestock.stockusageid=estprpricestockusage.id and estquotesubstock.id=estprpricestockusagestock.substockid');
        if ($recordSet&&!$recordSet->EOF) {
            $numberout=$recordSet->fields[0];
            $numberup=$recordSet->fields[1];
            $cuts=$recordSet->fields[2];
            $width=$recordSet->fields[3];
            $length=$recordSet->fields[4];
            $height=$recordSet->fields[5];
        };
        if ($numberup==0) $numberup=1;
        $recordSet=&$conn->Execute('select max(estquotesubstock.parts), sum(estquotestdsize.length) from estquote,estprprice,estprpricestockusage,estprpricestockusagestock,estquotesubstock,estquotestdsize where estquotestdsize.id=estprprice.estquotestdsizeid and estquote.id='.sqlprep($quoteid).' and estprprice.id=estquote.prpriceid and estprpricestockusage.prpriceid=estprprice.id and estprpricestockusagestock.stockusageid=estprpricestockusage.id and estquotesubstock.id=estprpricestockusagestock.substockid');
        if ($recordSet&&!$recordSet->EOF) {
            $parts=$recordSet->fields[0];
            $totlength=$recordSet->fields[1];
        };
        $stuff=array("parts" => $parts, "numberout" => $numberout, "numberup" => $numberup, "cuts" => $cuts, "width" => $width, "length" => $length, "height" => $height, "totlength" => $totlength);
        return $stuff;
    };

    function estcalcpresssheets($parts,$qty,$pages,$sides,$numberup,$numberout) {
        if ($parts==1) {
            $presssheets=$qty*$pages/$sides/$numberup;
            $stocksheets=$qty*$pages/$sides/$numberup/$numberout;
        } else {
            $presssheets=$qty*$parts/$numberup;
            $stocksheets=$qty*$parts/$numberup/$numberout;
        };
        return array('presssheets' => $presssheets, 'stocksheets' => $stocksheets);
    };

    function estcalcinksides($quoteid) { //find total number of sides on all parts of quote
        global $conn, $lang,$userid,$active_company;
        $recordSet=&$conn->Execute('select count(distinct estquotestockink.quotestockid,estquotestockink.side) from estquote,estquotestock,estquotestockink where estquote.id=estquotestock.quoteid and estquotestock.id=estquotestockink.quotestockid and estquote.id='.sqlprep($quoteid));
        if ($recordSet&&!$recordSet->EOF) $sides=$recordSet->fields[0];
        return $sides;
    };

    function estcalcinksidespart($quoteid, $quotestockid) { //find total number of sides on a single part of quote
        global $conn, $lang,$userid,$active_company;
        $sides=1;
        $recordSet=&$conn->Execute('select estquotestockink.side from estquote,estquotestock,estquotestockink where estquote.id=estquotestock.quoteid and estquotestock.id=estquotestockink.quotestockid and estquotestockink.quotestockid='.sqlprep($quotestockid).' and estquote.id='.sqlprep($quoteid));
        if ($recordSet&&!$recordSet->EOF) $sides=$recordSet->fields[0];
        return $sides;
    };

    function estcalcdistinctnbinks($quoteid) { //find number of distinct non-black inks on a quote
        global $conn, $lang,$userid,$active_company;
        $recordSet=&$conn->Execute('select count(distinct estquotestockink.inkid) from estquote,estquotestock,estquotestockink,estquoteink where estquoteink.id=estquotestockink.inkid and estquote.id=estquotestock.quoteid and estquotestockink.type!='.sqlprep(SD_INK_BLACK).' and estquotestock.id=estquotestockink.quotestockid and estquote.id='.sqlprep($quoteid));
        if ($recordSet&&!$recordSet->EOF) $distinctnbinks=$recordSet->fields[0];
        return $distinctnbinks;
    };

    function estcalcdistinctnbinkspart($quoteid,$quotestockid) { //find number of distinct non-black inks on a quote for a part
        global $conn, $lang,$userid,$active_company;
        $distinctnbinks=0;
        $recordSet=&$conn->Execute('select count(distinct estquotestockink.inkid) from estquote,estquotestock,estquotestockink,estquoteink where estquoteink.id=estquotestockink.inkid and estquote.id=estquotestock.quoteid and estquotestockink.type!='.sqlprep(SD_INK_BLACK).' and estquotestock.id=estquotestockink.quotestockid and estquotestock.id='.sqlprep($quotestockid).' and estquote.id='.sqlprep($quoteid));
        if ($recordSet&&!$recordSet->EOF) $distinctnbinks=$recordSet->fields[0];
        return $distinctnbinks;
    };

    function estnuminksside($quoteid,$side) { //find number of inks on a side
        global $conn, $lang,$userid,$active_company;
        $recordSet=&$conn->Execute('select count(distinct estquotestockink.inkid) from estquote,estquotestock,estquotestockink where estquote.id=estquotestock.quoteid and estquotestock.id=estquotestockink.quotestockid and estquote.id='.sqlprep($quoteid).' and estquotestockink.side='.sqlprep($side));
        if ($recordSet&&!$recordSet->EOF) $distinctinks=$recordSet->fields[0];
        return $distinctinks;
    };

    function estcalc85equiv($width,$length) { //find max number of 8.5x11 sheets that will fit on the given size (usually the final sheet size of quote)
        $one=floor($width/8.5)*floor($length/11);
        $two=floor($length/8.5)*floor($width/11);
        if (!$one&&!$two) { //if sheet is < 8.5x11
            $small=1;
            $one=floor(8.5/$width)*floor(11/$length);
            $two=floor(8.5/$length)*floor(11/$width);
        };
        if ($two>$one) $two=$one;
        if ($small) $one=1/$one;
        return $one;
    };
?>
