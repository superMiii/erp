<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />
</head>

<body>
	<style type="text/css" media="all">
		/*
@page land {size: landscape;}
@media print {
input.noPrint { display: none; }
}
@page
        {
            size: auto;   /* auto is the initial value 
            margin: 0mm;   this affects the margin in the printer settings 
        */
		* {
			size: landscape;
		}

		@page {
			size: Letter;
			margin: 0mm;
			/* this affects the margin in the printer settings */
		}

		.huruf {
			FONT-FAMILY: Tahoma, Verdana, Arial, Helvetica, sans-serif;
		}

		.miring {
			font-style: italic;

		}

		.wrap {
			margin: 0 auto;
			text-align: left;
		}

		.ceKotak {
			background-color: #f0f0f0;
			border-bottom: #80c0e0 1px solid;
			border-top: #80c0e0 1px solid;
			border-left: #80c0e0 1px solid;
			border-right: #80c0e0 1px solid;
		}

		.garis {
			background-color: #000000;
			width: 100%;
			height: 50%;
			font-size: 100px;
			border-style: solid;
			border-width: 0.01px;
			border-collapse: collapse;
			spacing: 1px;
		}

		.garis td {
			background-color: #FFFFFF;
			border-style: solid;
			border-width: 0.01px;
			font-size: 10px;
			FONT-WEIGHT: normal;
			padding: 1px;
		}

		.garisy {
			background-color: #000000;
			width: 100%;
			height: 50%;
			border-style: solid;
			border-width: 0.01px;
			border-collapse: collapse;
			spacing: 1px;
		}

		.garisy td {
			background-color: #FFFFFF;
			border-style: solid;
			border-width: 0.01px;
			padding: 1px;
		}

		.garisx {
			background-color: #000000;
			width: 100%;
			height: 50%;
			border-style: none;
			border-collapse: collapse;
			spacing: 1px;
		}

		.garisx td {
			background-color: #FFFFFF;
			border-style: none;
			font-size: 10px;
			FONT-WEIGHT: normal;
			padding: 1px;
		}

		.judul {
			font-size: 12px;
			FONT-WEIGHT: normal;
		}

		.nmper {
			margin-top: 0;
			font-size: 10px;
			FONT-WEIGHT: normal;
		}

		.isi {
			font-size: 10px;
			font-weight: normal;
		}

		.eusinya {
			font-size: 8px;
			font-weight: normal;
		}

		.garisbawah {
			border-bottom: #000000 0.1px solid;
		}

		.garisatas {
			border-top: #000000 0.1px solid;
		}

		.gariskiri {
			border-left: #000000 1px solid;
		}

		.gariskanan {
			border-right: #000000 1px solid;
		}

		.nobawah {
			border-bottom: #000000 0px solid;
		}
	</style>
	<style type="text/css" media="print">
		.noDisplay {
			display: none;
		}

		.pagebreak {
			page-break-before: auto;
		}
	</style>
	<?php
	if ($data->e_customer_name == '') {
		$data->e_customer_name = $data->customer_name;
	}
	if ($data->e_customer_npwpcode == '' or $data->e_customer_npwpcode == null) $data->e_customer_npwpcode = '00.000.000.0.000.000';
	if ($data->d_pajak != '') {
		$tmp = explode("-", $data->d_pajak);
		$thn = $tmp[0];
		$th = $tmp[0];
		$bl = $tmp[1];
		$hr = $tmp[2];
		$data->d_pajak = $hr . " " . bulan($bl) . " " . $th;
	}
	?>
	<table width="95%" class="nmper" border="0">
		<tr>
			<td colspan=8 class=" gariskiri gariskanan garisatas garisbawah"><br>&nbsp;&nbsp;&nbsp;P E M B E L I<br>
				&nbsp;&nbsp;&nbsp;Nama&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;<?php echo $data->e_customer_name; ?><br>
				&nbsp;&nbsp;&nbsp;Alamat&nbsp;&nbsp;&nbsp;:&nbsp;<?php echo $data->e_customer_address . " " . $data->e_city_name; ?><br>
				&nbsp;&nbsp;&nbsp;NPWP&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;<?php echo $data->e_customer_npwpcode; ?>
			</td>
		</tr>
		<tr>
			<td colspan=8 align="center" class="judul gariskiri gariskanan"><br>N O T A &nbsp;R E T U R
			</td>
		</tr>
		<tr>
			<td colspan=8 align="right" class="gariskiri gariskanan">Nomor <?php echo $data->i_kn_id; ?>&nbsp;&nbsp;
			</td>
		</tr>
		<tr>
			<td align="left" colspan="6" class="gariskiri garisbawah">&nbsp;&nbsp;&nbsp;(Atas Faktur Pajak No. <?php echo $data->i_pajak; ?></td>
			<?php if ($data->d_pajak == '') { ?>
				<td align="right" colspan="2" class="gariskanan garisbawah">Tanggal &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)&nbsp;&nbsp;
				<?php } else { ?>
				<td align="right" colspan="2" class="gariskanan garisbawah">Tanggal <?php echo $data->d_pajak . ' )'; ?><?php } ?>&nbsp;&nbsp;
				</td>
		</tr>
		</td>
		</tr>
		<tr>
			<td colspan="8" class="gariskiri gariskanan">&nbsp;&nbsp;&nbsp;K E P A D A &nbsp;&nbsp; P E N J U A L<br>
				&nbsp;&nbsp;&nbsp;Nama&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp; <?php echo $company->e_company_name; ?><br>
				&nbsp;&nbsp;&nbsp;Alamat&nbsp;&nbsp;&nbsp;:&nbsp;<?php echo $company->e_company_address; ?><br>
				&nbsp;&nbsp;&nbsp;NPWP&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;<?php echo $company->e_company_npwp_code; ?><br>
			</td>
		</tr>
		<tr>
			<td align="center" class="gariskiri garisbawah garisatas">No <br> Urut
			</td>
			<td align="center" class="gariskiri garisbawah garisatas">Nama Barang Kena Pajak/<br>Barang Mewah yang<br>Dikembalikan
			</td>
			<td align="center" class="gariskiri garisbawah garisatas">Diskon 1
			</td>
			<td align="center" class="gariskiri garisbawah garisatas">Diskon 2
			</td>
			<td align="center" class="gariskiri garisbawah garisatas">Diskon 3
			</td>
			<td align="center" class="gariskiri garisbawah garisatas">Kuantitas
			</td>
			<td align="center" class="gariskiri garisbawah garisatas">Harga Satuan<br>Menurut Faktur Pajak<br>(Rp.)
			</td>
			<td align="center" class="gariskiri garisbawah garisatas gariskanan">Harga jual yang<br>dikembalikan<br>(Rp.)
			</td>
		</tr>
		<?php
		$i = 0;
		$total = 0;
		$jdis = 0;
		$jds = 0;
		$dppp = 0;
		$pppn = 0;
		$el = 0;
		foreach ($detail->result() as $rowi) {
			$i++;
			$hrg  = number_format(($rowi->v_unit_price), 2);
			$prod  = $rowi->i_product;
			$name  = $rowi->e_product_name;
			$motif  = $rowi->e_remark;
			$d1  = number_format($rowi->n_ttb_discount1);
			$d2  = number_format($rowi->n_ttb_discount2);
			$d3  = number_format($rowi->n_ttb_discount3);
			$orde  = number_format($rowi->n_quantity);
			$sab  = $rowi->n_quantity * ($rowi->v_unit_price);
			$sub  = number_format(($rowi->n_quantity * ($rowi->v_unit_price)), 2);
			$total  = $total + $sab;
			$jds = $rowi->n_ttb_discount1 + $rowi->n_ttb_discount2 + $rowi->n_ttb_discount3;
			$jdis += ($jds / 100) * $sab;
			$dppp = $total - $jdis;
			$pppn = $dppp * (($data->n_ppn_r) / 100);
			$el = $dppp + $pppn;
		?>
			<tr>
				<td align="center" style="width:5%" class="gariskiri"><?php echo $i; ?>&nbsp;&nbsp;</td>
				<td align="left" style="width:39%" class="gariskiri">&nbsp;<?php echo $name; ?></td>
				<td align="right" style="width:5%" class="gariskiri"><?php echo $d1; ?>&nbsp;&nbsp;</td>
				<td align="right" style="width:5%" class="gariskiri"><?php echo $d2; ?>&nbsp;&nbsp;</td>
				<td align="right" style="width:5%" class="gariskiri"><?php echo $d3; ?>&nbsp;&nbsp;</td>
				<td align="right" style="width:7%" class="gariskiri"><?php echo $orde; ?>&nbsp;&nbsp;</td>
				<td align="right" style="width:17%" class="gariskiri"><?php echo $hrg; ?>&nbsp;&nbsp;</td>
				<td align="right" style="width:17%" class="gariskiri gariskanan"><?php echo $sub; ?>&nbsp;&nbsp;</td>
			</tr>
		<?php }
		do { ?>
			<tr>
				<td align="right" class="gariskiri">&nbsp;</td>
				<td align="left" class="gariskiri">&nbsp;</td>
				<td align="right" class="gariskiri">&nbsp;</td>
				<td align="right" class="gariskiri">&nbsp;</td>
				<td align="right" class="gariskiri">&nbsp;</td>
				<td align="right" class="gariskiri">&nbsp;</td>
				<td align="right" class="gariskiri">&nbsp;</td>
				<td align="right" class="gariskiri gariskanan">&nbsp;</td>
			</tr>
		<?php
			$i++;
		} while ($i <= 25); ?>
		<tr>
			<td align="right" class="gariskiri">&nbsp;</td>
			<td align="left" class="gariskiri">&nbsp;</td>
			<td align="right" class="gariskiri">&nbsp;</td>
			<td align="right" class="gariskiri">&nbsp;</td>
			<td align="right" class="gariskiri">&nbsp;</td>
			<td align="right" class="gariskiri">&nbsp;</td>
			<td align="left" class="gariskiri">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Jumlah</td>
			<td align="right" class="gariskiri gariskanan"><?php echo number_format($data->v_gross, 2) ?>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td align="right" class="gariskiri garisbawah">&nbsp;</td>
			<td align="left" class="gariskiri garisbawah">&nbsp;</td>
			<td align="right" class="gariskiri garisbawah">&nbsp;</td>
			<td align="right" class="gariskiri garisbawah">&nbsp;</td>
			<td align="right" class="gariskiri garisbawah">&nbsp;</td>
			<td align="right" class="gariskiri garisbawah">&nbsp;</td>
			<td align="left" class="gariskiri garisbawah">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Potongan</td>
			<td align="right" class="gariskiri gariskanan garisbawah"><?php echo number_format($jdis, 2) ?>&nbsp;&nbsp;</td>
		</tr>
		<?php
		$tmp = explode("-", $data->d_kn);
		$th = $tmp[0];
		$bl = $tmp[1];
		$hr = $tmp[2];
		$data->d_kn = $hr . " " . bulan($bl) . " " . $th;

		$net  = $data->v_gross - $data->v_discount;
		$pjk = 0.11 * $net;
		$jj = $net + $pjk;
		$kota = $data->e_city_name . ",  " . $data->d_kn . "         ";
		?>
		<tr>
			<td colspan="7" class="gariskiri garisbawah">&nbsp;&nbsp;&nbsp;Jumlah Harga Jual yang dikembalikan :</td>
			<td align="right" class="gariskanan gariskiri garisbawah" width="195px"><?php echo number_format($dppp, 2); ?>&nbsp;&nbsp;</td>
		</tr>
		<?php if ($data->f_ttb_plusppn == 't') { ?>
			<tr>
				<td colspan="7" class="gariskiri garisbawah">&nbsp;&nbsp;&nbsp;Jumlah pajak yang dikurangkan :<br>
					&nbsp;&nbsp;&nbsp;a. Pajak Pertambahan Nilai (<?php echo number_format($data->n_ppn_r); ?> %)<br>
					&nbsp;&nbsp;&nbsp;b. Pajak Penjualan atas Barang Mewah</td>
				<td align="right" width="195px" class="gariskiri gariskanan garisbawah"><br><?php echo number_format($pppn, 2); ?>&nbsp;&nbsp;<br></td>
			</tr>
			<tr>
				<td colspan="7" class="gariskiri garisbawah">&nbsp;&nbsp;&nbsp;Total :</td>
				<td align="right" class="gariskanan gariskiri garisbawah" width="195px"><?php echo number_format($el, 2); ?>&nbsp;&nbsp;</td>
			</tr>
		<?php } ?>
		<tr>
			<td colspan="4" class="gariskiri">&nbsp;</td>
			<td colspan="4" class="gariskanan">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="6" class="gariskiri garisbawah">&nbsp;</td>
			<td align="center" colspan="2" class="gariskanan garisbawah"><?php echo $kota; ?><br>
				Pembeli<br><br><br><br>
				(...........................................)
			</td>
		</tr>
		<tr>
			<td colspan="8" class="gariskanan gariskiri garisbawah">&nbsp;&nbsp;&nbsp;Lembar Ke.1 : untuk Pengusaha Kena Pajak yang menerbitkan Faktur Pajak<br>
				&nbsp;&nbsp;&nbsp;Lembar Ke.2 : untuk Pembeli
			</td>
		</tr>
	</table>
	<div class="noDisplay">
		<center><b><a href="#" onClick="window.print()">Print</a></b></center>
	</div>