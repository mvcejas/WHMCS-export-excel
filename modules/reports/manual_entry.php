<?php
if (!defined("WHMCS"))
	die("This file cannot be accessed directly");

if(isset($_POST['manualentry'])){
	$datepaid    = $_POST['datepaid'];
	$descrizione = $_POST['descrizione'];
	$fatt        = $_POST['fatt'];
	$importo     = $_POST['importo'];
	$cod         = $_POST['cod'];
	$c_entrate   = $_POST['c_entrate'];
	$c_uscite    = $_POST['c_uscite'];
	$b_entrate   = $_POST['b_entrate'];
	$b_uscite    = $_POST['b_uscite'];
	$p_lordo     = $_POST['p_lordo'];
	$p_netto     = $_POST['p_netto'];
	$p_uscite    = $_POST['p_uscite'];

	$datepaid = date('Y-m-d',strtotime(str_replace('/','-',$datepaid)));

	$query = array(
			'descrizione' => $descrizione,
			'fatt'        => $fatt,
			'importo'     => $importo,
			'cod'         => $cod,
			'c_entrate'   => $c_entrate,
			'c_uscite'    => $c_uscite,
			'b_entrate'   => $b_entrate,
			'b_uscite'    => $b_uscite,
			'p_lordo'     => $p_lordo,
			'p_netto'     => $p_netto,
			'p_uscite'    => $p_uscite,
			'notes'       => $notes,
			'datepaid'    => $datepaid);

	insert_query('manualentry',$query);
}

$reportdata["title"] = "Manual Entry";
$reportdata["description"] = "This module will allow you to manually enter data for <a href=\"?report=excel_export\">excel reporting</a>. (<a href=\"".$_SERVER['PHP_SELF']."?report=manual_entry&records=1\">Show Records</a> | <a href=\"".$_SERVER['PHP_SELF']."?report=manual_entry\">Show Form</a>)";

if(!isset($_GET['records'])){
	$reportdata["headertext"] = '
		<form method="post" action="">
			<table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
				<tr><td class="fieldlabel">Date Paid</td><td class="fieldarea"><input type="text" name="datepaid" value="" class="datepick"></td></tr>
				<tr><td class="fieldlabel">Descrizione</td><td class="fieldarea"><input type="text" name="descrizione" value=""></td></tr>
				<tr><td class="fieldlabel">Fatt. N.</td><td class="fieldarea"><input type="text" name="fatt" value=""></td></tr>
				<tr><td class="fieldlabel">Importo</td><td class="fieldarea"><input type="text" name="importo" value=""></td></tr>
				<tr><td class="fieldlabel">Cod.</td><td class="fieldarea"><select name="cod"><option value="B">Banca</option><option value="C">Cassa</option><option value="P">Paypal</option></select></td></tr>
				<tr><td class="fieldlabel">Cassa</td><td class="fieldarea"><label>entrate</label><input type="text" name="c_entrate" value=""> <label>uscite</label><input type="text" name="c_uscite" value=""></td></tr>
				<tr><td class="fieldlabel">Banca</td><td class="fieldarea"><label>entrate</label><input type="text" name="b_entrate" value=""> <label>uscite</label><input type="text" name="b_uscite" value=""></td></tr>
				<tr><td class="fieldlabel">Paypal</td><td class="fieldarea"><label>ent lordo</label><input type="text" name="p_lordo" value=""> <label>ent netto</label><input type="text" name="p_netto" value=""> <label>ent uscite</label><input type="text" name="p_uscite" value=""></td></tr>
				<tr><td class="fieldlabel">Notes</td><td class="fieldarea"><textarea name="notes" style="width:400px;height:80px;"></textarea></td></tr>
			</table>
			<p align=center><input type="submit" name="manualentry" value="Submit" class="button"></p>
		</form>';
}
else{
	$result = mysql_query("SELECT * FROM manualentry");
	$records = '';
	if(mysql_num_rows($result)){
		while($row = mysql_fetch_array($result)){
			$records .= '
				<tr bgcolor="#ffffff" style="text-align:center;">
					<td>'.$row['descrizione'].'</td>
					<td>'.$row['fatt'].'</td>
					<td>'.$row['importo'].'</td>
					<td>'.$row['cod'].'</td>
					<td>'.$row['c_entrate'].'</td>
					<td>'.$row['c_uscite'].'</td>
					<td>'.$row['b_entrate'].'</td>
					<td>'.$row['b_uscite'].'</td>
					<td>'.$row['p_lordo'].'</td>
					<td>'.$row['p_netto'].'</td>
					<td>'.$row['p_uscite'].'</td>
				</tr>';
		}
	}
	else{
		$records .= '
			<tr bgcolor="#ffffff" style="text-align:center;">
				<td colspan="11">No Data Found For This Report</td>
			</tr>';
	}

	$reportdata["headertext"] = '
		<table width="100%" cellspacing="1" bgcolor="#cccccc">
				<thead>
					<tr style="background:#aaa;color:#FFF;font-weight:bold;font-size:20px;text-align:center;">
						<td colspan="4">&nbsp;</td>
						<td colspan="2">Cassa "C"</td>
						<td colspan="2">Banca "B"</td>
						<td colspan="3">Paypal "P"</td>
					</tr>
					<tr bgcolor="#efefef" style="text-align:center;font-weight:bold;">
						<td>Descrizione</td>
						<td>Fatt. N.</td>
						<td>Importo</td>
						<td>Code</td>
						<td>entrate</td>
						<td>uscite</td>
						<td>entrate</td>
						<td>uscite</td>
						<td>ent lordo</td>
						<td>ent netto</td>
						<td>uscite</td>
					</tr>
			</thead>
			<tbody>'.$records.'</tbody>
		</table>';
}

/////////////////////////////////////////////
$report = '';

