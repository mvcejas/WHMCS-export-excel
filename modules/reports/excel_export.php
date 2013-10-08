<?php
if (!defined("WHMCS"))
	die("This file cannot be accessed directly");

$reportdata["title"] = "Export Report To Microsoft Excel";
$reportdata["description"] = "This report can be used to generate an excel file report of invoices. (<a href=\"".$_SERVER['PHP_SELF']."?report=manual_entry\">Add Manual Entries</a>)";
$reportdata["headertext"] = '
<form method="post" action="exceldownload.php">
<table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
<tr><td class="fieldlabel">Date Range</td><td class="fieldarea"><input type="text" name="datefrom" value="'.fromMySQLDate(date("Y-m-d",mktime(0,0,0,date("m")-1,date("d"),date("Y")))).'" class="datepick" /> to &nbsp;&nbsp;&nbsp;&nbsp; <input type="text" name="dateto" value="'.fromMySQLDate(date("Y-m-d")).'" class="datepick" /></td></tr>
</table>
<p align=center><input type="submit" value="Download File" class="button"></p>
</form>';
$report = '';