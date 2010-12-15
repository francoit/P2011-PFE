<?php
# This file is part of GNU Enterprise.
#
# GNU Enterprise is free software; you can redistribute it 
# and/or modify it under the terms of the GNU General Public 
# License as published by the Free Software Foundation; either 
# version 2, or(at your option) any later version.
#
# GNU Enterprise is distributed in the hope that it will be 
# useful, but WITHOUT ANY WARRANTY; without even the implied 
# warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR 
# PURPOSE. See the GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public 
# License along with program; see the file COPYING. If not, 
# write to the Free Software Foundation, Inc., 59 Temple Place 
# - Suite 330, Boston, MA 02111-1307, USA.
#
# Copyright 2000, 2001, 2002 Free Software Foundation
#
# FILE:
# gcomm.php
#
# DESCRIPTION:
# the base file for GNUe RPC Abstraction for PHP
#
# NOTES: 
#


function gcomm_attach($interface, $params) {
# just provide xmlrpc at the moment
    if ($interface!="xmlrpc") return false;
    
    # $driver=new ez_xmlrpc_driver($params);
    $driver=new xmlrpc_driver($params);
    return $driver;
   
}


function gcomm_bind($interface,$params) {
    print "The server side is not implemented";
}


/* XMLRPC from useful.inc */

include_once( "xmlrpc.inc");

class xmlrpc_driver {
    var $params;
    var $link;
    function xmlrpc_driver($params) {
        $this->params=$params;
        # check params and return error if a parameter is missing
        # (todo)

        # create link
        $this->link=new xmlrpc_client($params["PATH"],
                                      $params["HOST"],
                                      $params["PORT"]);
        $this->link->setDebug(0);  // 1
    }
    function request($func) {
        return new gnurpc_proxyobj($this,$func);
    }
    function execute($func,$param) {
        
        # build params
        $nparam=array();
        if (is_array($param)) {
            reset($param);
            while ($x=each($param)) {
                $x=$x[1];
                $nparam[]=xmlrpc_encode($x);
//                 if (is_string($x)) {
//                     $nparam[]=new xmlrpcval( $x, "string");
//                 }
//                 if (is_int($x)) {
//                     $nparam[]=new xmlrpcval( $x, "integer");
//                 }
//                 if (is_float($x)) {
//                     $nparam[]=new xmlrpcval( $x, "float");
//                 }
            }
        } else {  // seems to be one single parameter
            if ($param!=None)
                $nparam[]=xmlrpc_encode($param);  
        };
        # else check if it is a single value
        
        # build message
        $call=new xmlrpcmsg($func,$nparam);
        # execute
        $response = $this->link->send( $call );
        if (!$response) { die("<H1>send failed</H1>"); }
        # check for errors
        if ( $response->faultCode() )
            {
                print( "The server returned an error (" . 
                       $response->faultCode() . "): ". 
                       $response->faultString() .
                       "<br>" );                
                return None;
            }
        else
            {
                return $this->xmlrpc2php($response->value());                
            }
        
    }
    // function xmlrpc_decode could be used instead, but doesn't support object
    // handles
    function xmlrpc2php($var) {
        if ($var->kindof()=="struct") {
            $nvar=array();
            while (list($key,$value)=$var->structeach()) {          
                $nvar[$key]=$this->xmlrpc2php($value);                    
            } 
            return $nvar;
        } else { 
            $nvar=array();
            if ($var->kindof()=="array") {
                $nvar=array();
                for ($i=0;$i<$var->arraysize();$i++) {
                    $nvar[]=$var->arraymem($i);            
                }
                return $nvar;
            } else {
                $nvar=$var->scalarval();
                // check for object handles
                // implement a better check
                if ((is_string($nvar))&&(strlen($nvar)==40)) {
                    $nvar=new gnurpc_proxyobj($this,"[".$nvar."]");
                    }
                return $nvar;
            }
            
        }
    }
} 
/*
include( "devens_xmlrpc.inc");

class xmlrpc_driver {
    var $params;
    var $link;
    function xmlrpc_driver($params) {
        $this->params=$params;
        # check params and return error if a parameter is missing
        # (todo)

        # create link
        $this->path=$params["PATH"];
	$this->server=$params["HOST"].":".$params["PORT"];
    }
    function request($func) {
        return new gnurpc_proxyobj($this,$func);
    }
    function execute($func,$param) {
      # build params
      $nparam=array();
      if (is_array($param)) {
	reset($param);
	while ($x=each($param)) {
	  $x=$x[1];
	  $nparam[]=XMLRPC_prepare($x);
	  // if (is_string($x)) {
	  //   $nparam[]=new xmlrpcval( $x, "string");
	  // }
	  // if (is_int($x)) {
	  //   $nparam[]=new xmlrpcval( $x, "integer");
	  // }
	  // if (is_float($x)) {
	  //   $nparam[]=new xmlrpcval( $x, "float");
	  // }
	}
      } else {  // seems to be one single parameter
	if ($param!=None)
	  $nparam[]=XMLRPC_prepare($param);  
      };
      # else check if it is a single value
        
      # execute
      $response = XMLRPC_request($this->server,$this->path, $func, $nparam);

      if (!$response) { die("<H1>send failed</H1>"); }
      return $response;
      # check for errors
      if ( $response->faultCode() )
	{
	  print( "The server returned an error (" . 
		 $response->faultCode() . "): ". 
		 $response->faultString() .
		 "<br>" );                
	  return None;
	}
      else
	{
	  return $this->xmlrpc2php($response->value());                
	}
        
    }
    // function xmlrpc_decode could be used instead, but doesn't support object
    // handles
    function xmlrpc2php($var) {
        if ($var->kindof()=="struct") {
            $nvar=array();
            while (list($key,$value)=$var->structeach()) {          
                $nvar[$key]=$this->xmlrpc2php($value);                    
            } 
            return $nvar;
        } else { 
            $nvar=array();
            if ($var->kindof()=="array") {
                $nvar=array();
                for ($i=0;$i<$var->arraysize();$i++) {
                    $nvar[]=$var->arraymem($i);            
                }
                return $nvar;
            } else {
                $nvar=$var->scalarval();
                // check for object handles
                // implement a better check
                if ((is_string($nvar))&&(strlen($nvar)==40)) {
                    $nvar=new gnurpc_proxyobj($this,"[".$nvar."]");
                    }
                return $nvar;
            }
            
        }
    }
} */

class gnurpc_proxyobj {
    var $handle;
    function gnurpc_proxyobj($driver,$handle) {
        $this->handle=$handle;
        $this->driver=$driver;
    }
    function execute($method,$params) {
        return $this->driver->execute($this->handle.".".$method,$params);
    }
    function getHandle() {
        return $this->handle;
    }
    function getDriver() {
        return $this->driver;
    }
}

?>