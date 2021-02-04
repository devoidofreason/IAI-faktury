<?php
	require_once '../connect.php';
	$data = $db->getFaktura($_GET['id']);
	require '../vendor/autoload.php';
	$rows = '';
	$k = 1;
	$netto = 0;
	$brutto = 0;
	foreach ($data['pozycje'] as $i) {
		$rows .= '<tr>';
			$rows .= '<td>';
			$rows .= strval($k++);
			$rows .= '</td>';
			$rows .= '<td>';
			$rows .= $i->opis;
			$rows .= '</td>';
			$rows .= '<td>';
			$rows .= $i->ilosc;
			$rows .= '</td>';
			$rows .= '<td>';
			$rows .= $i->netto;
			$rows .= 'zł</td>';
			$rows .= '<td>';
			$rows .= $i->vat;
			$rows .= '%</td>';
			$nettotmp = floatval($i->netto);
			$bruttotmp = round($nettotmp * (1 + (floatval($i->vat)/100)), 2);
			$rows .= '<td>';
			$rows .= $bruttotmp;
			$rows .= 'zł</td>';
		$rows .= '</tr>';
		$netto += $nettotmp;
		$brutto += $bruttotmp;
	}
	$html = '
		<style>
			.column {
  				float: left;
  				width: 50%;
			}
			.row:after {
  				content: "";
  				display: table;
  				clear: both;
			}
			table {
  				font-family: arial, sans-serif;
  				border-collapse: collapse;
 				width: 100%;
			}
			td, th {
  				border: 1px solid #dddddd;
  				text-align: left;
  				padding: 8px;
			}
			tr:nth-child(even) {
  				background-color: #dddddd;
			}
		</style>
		<div class="row">
			<div class="column">
				<img src="../icon.jpg">
			</div>
			<div class="column" style="text-align: right;">
				Miejsce wystawienia: Szczecin
				<br>Data wystawienia: '. $data['faktura'][0]->data .'
			</div>
		</div>
		<h1><u>Faktura nr: '. $data['faktura'][0]->ID .' / '. $data['faktura'][0]->data .'</u></h1>
		<div class="row">
			<div class="column">
				<b>Sprzedawca</b>
				<br>IAI
				<br>aleja Piastów 30
				<br>71-064 Szczecin
				<br>NIP: PL5252767146
				<br>Telefon: 91 443 66 00
				<br>E-mail: office@idosell.com
			</div>
			<div class="column">
				<b>Nabywca</b>
				<br>'. $data['faktura'][0]->imie .' '. $data['faktura'][0]->nazwisko .'
				<br>'. $data['faktura'][0]->adres .'
				<br>'. $data['faktura'][0]->kod .' '. $data['faktura'][0]->miasto .'
				<br>NIP: '. $data['faktura'][0]->nip .'
				<br>Telefon: '. $data['faktura'][0]->telefon .'
				<br>E-mail: '. $data['faktura'][0]->email .'
			</div>
		</div>
		<br><br><br>
		<table>
			<tr>
    			<th>Lp.</th>
    			<th>Nazwa towaru/usługi</th>
    			<th>Ilość</th>
    			<th>Cena netto</th>
    			<th>VAT</th>
    			<th>Cena brutto</th>
  			</tr>
  			'. $rows .'
		</table>
		<br><br><br>
		<table>
			<tr>
    			<th>Wartość netto</th>
    			<th>Wartość brutto</th>
  			</tr>
  			<tr>
  				<td>'. $netto .'zł</td>
  				<td>'. $brutto .'zł</td>
  			</tr>
  		</table>
  		<br>
  		Do zapłaty: '. $brutto .'zł
  		<br><br><br><br><br>
  		<div class="row">
			<div style="text-align: center;" class="column">
				-----------------------------------------------------
				<br>
				<br>
				Podpis osoby odbierającej
			</div>
			<div style="text-align: center;" class="column">
				-----------------------------------------------------
				<br>
				<br>
				Podpis osoby wystawiającej
			</div>
		</div>
	';
	$mpdf = new \Mpdf\Mpdf();
	$mpdf->WriteHTML($html);
	$mpdf->Output();
?>