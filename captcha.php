<?
/* 
  captcha.php
  jQuery Fancy Captcha
  www.webdesignbeach.com
  
  Created by Web Design Beach.
  Copyright 2009 Web Design Beach. All rights reserved.
*/
session_start(); /* starts session to save generated random number */

$rand = rand(0,4);
$_SESSION[captcha] = $rand;
echo $rand;
?>