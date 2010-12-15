<?PHP

// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)

// loading functions and libraries
function random($max) {
	// create random number between 0 and $max
	srand((double)microtime() * 1000000);
	$r = round(rand(0, $max));
	if ($r!=0) $r=$r-1;
	return $r;
};

function rotateBg() {
	// rotate background login interface
	global $backgrounds, $bgImage, $i;
	$c = count($backgrounds);
	if ($c==0) return;
	$r = random($c);
	if ($backgrounds[$r]==''&&$i<10) {
		$i++;
		rotateBg();
	} elseif ($i>=10) {
		if (!$bgImage||$bgImage=='') {
			$bgImage='bg_lock.gif';
		} else {
			$bgImage=$bgImage;
		}
	} else {
		$bgImage = $backgrounds[$r];
	};
	return $bgImage;
};

if ($noDetailedMessages) $strUserNotExist=$strUserNotAllowed=$strPwNotFound=$strPwFalse=$strNoPassword=$strNoAccess;
if ($bgRotate) {
	$i = 0;
	$bgImage = rotateBg();
};


// check if login is necesary
if (!$entered_login && !$entered_password) {
	session_start();
} else {
	// use entered data
		session_start();
		session_unregister("login");
		session_unregister("password");

		$login = $entered_login;
		$password = $entered_password;
		session_register("login");
		session_register("password");
}

if (!$login) { // no login available
	include('authentication/interface.php');
	exit;
};
if (!$password) { // no password available
	$message = 'No password';
	include('authentication/interface.php');
	exit;
};

	if (!$external_login) { //if logging in a local user
		//the next statement checks 2 passwords, encrypted and unencrypted.
        if (LOGIN_CASE_INSENSITIVE) {
		    $recordSet = &$conn->Execute("select id,stylesheetid from genuser WHERE UCASE(name) = UCASE('$login') and (UCASE(password) = UCASE(".sqlprep($password).") or password = ".sqlprep(pwencrypt($password)).") and active=1");
        } else {
		    $recordSet = &$conn->Execute("select id,stylesheetid from genuser WHERE name = '$login' and (password = ".sqlprep($password)." or password = ".sqlprep(pwencrypt($password)).") and active=1");
        };
		if (!$recordSet->EOF) {
			if (ALLOW_LOGIN_INTERNAL) {
				$ID = $recordSet->fields[0];
				$styleid = $recordSet->fields[1];
				//$supervisor = $recordSet->fields[2];
				$recordSet2 = &$conn->Execute("select filename from genstylesheet WHERE id = '$styleid'");
                if (!$recordSet2->EOF) $user_stylesheet=rtrim($recordSet2->fields[0]);
				session_register("user_stylesheet");
				session_register("supervisor");
		} else {
				$message = 'Login Failed';
				include('authentication/interface.php');
				exit;
			};
		} else {
			$message = 'Login Failed';
			include('authentication/interface.php');
			exit;
		};
	} else {
		//the next statement checks 2 passwords, encrypted and unencrypted.
        if (LOGIN_CASE_INSENSITIVE) {
    		$recordSet = &$conn->Execute("select extuser.id,extuser.stylesheetid,customer.id,vendor.id,customer.gencompanyid,vendor.gencompanyid from extuser left join customer on extuser.customer=customer.id and customer.cancel=0 left join vendor on extuser.vendor=vendor.id and vendor.cancel=0 WHERE UCASE(extuser.name) = UCASE('$login') and (UCASE(extuser.password) = UCASE(".sqlprep($password).") or extuser.password = ".sqlprep(pwencrypt($password)).") and extuser.cancel=0");
        } else {
    		$recordSet = &$conn->Execute("select extuser.id,extuser.stylesheetid,customer.id,vendor.id,customer.gencompanyid,vendor.gencompanyid from extuser left join customer on extuser.customer=customer.id and customer.cancel=0 left join vendor on extuser.vendor=vendor.id and vendor.cancel=0 WHERE extuser.name = '$login' and (extuser.password = ".sqlprep($password)." or extuser.password = ".sqlprep(pwencrypt($password)).") and extuser.cancel=0");
        };
		if (!$recordSet->EOF) {
			$ID = $recordSet->fields[0];
			$styleid = $recordSet->fields[1];
	        if (ALLOW_LOGIN_CUSTOMER) $custcompanyid = $recordSet->fields[2];
			if (ALLOW_LOGIN_VENDOR) $vendcompanyid = $recordSet->fields[3];
			if (!$custcompanyid&&!$vendcompanyid) {
				$message = 'Login Failed';
				include('authentication/interface.php');
				exit;
			};
            if ($custcompanyid) {
                $active_company=$recordSet->fields[4];
            } else {
                $active_company=$recordSet->fields[5];
            };
			$extlogon = 1;
		    session_register("extlogon");
     		session_register("custcompanyid");
		    session_register("vendcompanyid");
     		session_register("active_company");
            session_register("external_login");
		    $recordSet2 = &$conn->Execute("select filename from genstylesheet WHERE id = '$styleid'");
      		if (!$recordSet2->EOF) $user_stylesheet=$recordSet2->fields[0];
			session_register("user_stylesheet");
		} else {
			$message = 'Login Failed';
			include('authentication/interface.php');
			exit;
		};
	};

// restore values
if ($dbOld) $db = $dbOld;
if ($messageOld) $message = $messageOld;
?>
