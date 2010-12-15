<?
	/*********************
		send to ups program
		Send Vars
			$OriginPostalCode
			$DestZipCode
			$PackageWeight
			$upsProduct
			
		OUT VARS
			$result[0]
			
		UPS PRODUCT CODE (this should be in a drop down menu)
		 	Next Day Air Early AM	 	1DM
		 	Next Day Air	 		 	1DA
 			Next Day Air Saver	 		1DP
 			2nd Day Air AM	 			2DM
			2nd Day Air	 				2DA
 			3 Day Select	 			3DS
			Ground	 					GND
 			Canada Standard	 			STD
	 		Worldwide Express	 		XPR
 			Worldwide Express Plus	 	XDM
 			Worldwide Expedited	 		XPD

		UPS RATE CHART
			Regular+Daily+Pickup
			On+Call+Air
			One+Time+Pickup
			Letter+Center
			Customer+Counter

		Container Chart
			Customers Packaging			00
			UPS Letter Envelope			01
				or
			UPS Tube
			UPS Express Box				21
			UPS Worldwide 25kg Box		22
			UPS Worldwide 10 kg Box		23
			
		ResCom UPS Table
			Residential					1
			Commercial					2
			
	$upsAction = "3"; //3 Price a Single Product OR 4 Shop entire UPS product range
	$upsProduct = "GND"; //set UPS Product Code See Chart Above
	$OriginPostalCode = "08053"; //zip code from where the client will ship from
	$DestZipCode = "44830"; //set where product is to be sent 
	$PackageWeight = "30"; //weight of product
	$OrigCountry = "US"; //country where client will ship from
	$DestCountry = "US"; //set to country whaere product is to be sent
	$RateChart = "Regular+Daily+Pickup"; //set to how customer wants UPS to collect the product
	$Container = "00"; //Set to Client Shipping package type
	$ResCom = "1"; //See ResCom Table
	
	***********************/

function getupsrate($upsAction, $upsProduct, $OriginPostalCode, $DestZipCode, $PackageWeight, $OrigCountry, $DestCountry, $RateChart, $Container, $ResCom) {
	$port = 80;
	$them = "www.ups.com";
	$workFile = "/using/services/rave/qcostcgi.cgi";
	$workString = "?";
	$workString .= "accept_UPS_license_agreement=yes";
	$workString .= "&";
	$workString .= "10_action=$upsAction";
	$workString .= "&";
	$workString .= "13_product=$upsProduct";
	$workString .= "&";
	$workString .= "14_origCountry=$OrigCountry";
	$workString .= "&";
	$workString .= "15_origPostal=$OriginPostalCode";
	$workString .= "&";
	$workString .= "19_destPostal=$DestZipCode";
	$workString .= "&";
	$workString .= "22_destCountry=$DestCountry";
	$workString .= "&";
	$workString .= "23_weight=$PackageWeight";
	$workString .= "&";
	$workString .= "47_rateChart=$RateChart";
	$workString .= "&";
	$workString .= "48_container=$Container";
	$workString .= "&";
	$workString .= "49_residential=$ResCom";
	$request = "$workFile$workString";
	$fp = fsockopen("$them", 80, &$errno, &$errstr, 30);
	if(!$fp) {
        	echo "$errstr ($errno)<br>\n";
		return 0;
	} else {
		fputs($fp,"GET $request HTTP/1.0\n\n");
			while(!feof($fp)) {
				$result = fgets($fp,500);
				$result = explode("%", $result);
				$errcode = substr("$result[0]", -1);
					if ($errcode == "3") {
						return "$result[8]"+SHIPPING_SURCHARGE;
					};
					if ($errcode == "4") {
						return "$result[8]"+SHIPPING_SURCHARGE;
					};
					if ($errcode == "5") {
						return "$result[8]"+SHIPPING_SURCHARGE;
					};
					if ($errcode == "6") {
						return "$result[8]"+SHIPPING_SURCHARGE;
					};		
			};
		fclose($fp);
	};
};




class Ups {  //alternate calc method.

/*

    Sample usage:
    $rate = new Ups;
    $rate->upsProduct("1DM");    // See upsProduct() function for codes
    $rate->origin("08033", "US"); // Use ISO country codes!
    $rate->dest("90210", "US");      // Use ISO country codes!
    $rate->rate("RDP");        // See the rate() function for codes
    $rate->container("CP");    // See the container() function for codes
    $rate->weight("2");
    $rate->rescom("RES");    // See the rescom() function for codes
    $quote = $rate->getQuote();
    echo $quote;

  */

    function upsProduct($prod){
       /*

         1DM == Next Day Air Early AM
     1DA == Next Day Air
     1DP == Next Day Air Saver
     2DM == 2nd Day Air Early AM
     2DA == 2nd Day Air
     3DS == 3 Day Select
     GND == Ground
     STD == Canada Standard
     XPR == Worldwide Express
     XDM == Worldwide Express Plus
     XPD == Worldwide Expedited

    */
      $this->upsProductCode = $prod;
    }

    function origin($postal, $country){
      $this->originPostalCode = $postal;
      $this->originCountryCode = $country;
    }

    function dest($postal, $country){
      $this->destPostalCode = $postal;
          $this->destCountryCode = $country;
    }

    function rate($foo){
      switch($foo){
        case "RDP":
          $this->rateCode = "Regular+Daily+Pickup";
          break;
        case "OCA":
          $this->rateCode = "On+Call+Air";
          break;
        case "OTP":
          $this->rateCode = "One+Time+Pickup";
          break;
        case "LC":
          $this->rateCode = "Letter+Center";
          break;
        case "CC":
          $this->rateCode = "Customer+Counter";
          break;
      }
    }

    function container($foo){
          switch($foo){
        case "CP":            // Customer Packaging
          $this->containerCode = "00";
          break;
               case "ULE":        // UPS Letter Envelope
          $this->containerCode = "01";
          break;
        case "UT":            // UPS Tube
          $this->containerCode = "03";
          break;
        case "UEB":            // UPS Express Box
          $this->containerCode = "21";
          break;
        case "UW25":        // UPS Worldwide 25 kilo
          $this->containerCode = "24";
          break;
        case "UW10":        // UPS Worldwide 10 kilo
          $this->containerCode = "25";
          break;
      }
    }

    function weight($foo){
      $this->packageWeight = $foo;
    }

    function rescom($foo){
          switch($foo){
        case "RES":            // Residential Address
          $this->resComCode = "1";
          break;
        case "COM":            // Commercial Address
          $this->resComCode = "2";
          break;
          }
    }

    function getQuote(){
          $upsAction = "3"; // You want 3.  Don't change unless you are sure.
      $url = join("&",
               array("http://www.ups.com/using/services/rave/qcostcgi.cgi?accept_UPS_license_agreement=yes",
                     "10_action=$upsAction",
                     "13_product=$this->upsProductCode",
                     "14_origCountry=$this->originCountryCode",
                     "15_origPostal=$this->originPostalCode",
                     "19_destPostal=$this->destPostalCode",
                     "22_destCountry=$this->destCountryCode",
                     "23_weight=$this->packageWeight",
                     "47_rateChart=$this->rateCode",
                     "48_container=$this->containerCode",
                     "49_residential=$this->resComCode"
           )
                );
      $fp = fopen($url, "r");
      while(!feof($fp)){
        $result = fgets($fp, 500);
        $result = explode("%", $result);
        $errcode = substr($result[0], -1);
        switch($errcode){
          case 3:
            $returnval = $result[8];
                break;
          case 4:
            $returnval = $result[8];
            break;
          case 5:
            $returnval = $result[1];
            break;
          case 6:
            $returnval = $result[1];
            break;
        }
      }
      fclose($fp);
          if(! $returnval) { $returnval = "error"; }
      return $returnval;
    }
  }


?> 
