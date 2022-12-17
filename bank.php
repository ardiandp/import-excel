<!DOCTYPE html>
<html>
<head>
	<title>Mari Belajar Coding</title>
	<?php
	include 'koneksi.php';
	?>
</head>
<body>

	<table>
		<!--form upload file-->
		<form method="post" enctype="multipart/form-data" >
			<tr>
				<td>Pilih File Bank</td>
				<td><input name="filebank" type="file" required="required" placeholder="Imput Data Bank"></td>
			</tr>
			<tr>
				<td></td>
				<td><input name="upload" type="submit" value="Import"></td>
			</tr>
		</form>
	</table>
	<?php
	if (isset($_POST['upload'])) {

		require('spreadsheet-reader-master/php-excel-reader/excel_reader2.php');
		require('spreadsheet-reader-master/SpreadsheetReader.php');

		//upload data excel kedalam folder uploads
		$target_dir = "uploads/".basename($_FILES['filebank']['name']);
		
		move_uploaded_file($_FILES['filebank']['tmp_name'],$target_dir);

		$Reader = new SpreadsheetReader($target_dir);

		foreach ($Reader as $Key => $Row)
		{
			// import data excel mulai baris ke-2 (karena ada header pada baris 1)
			if ($Key < 1) continue;			
			$query=mysql_query("INSERT INTO bca_cv(tanggal,keterangan,cabang,jumlah,status,saldo) VALUES ('".$Row[0]."', '".$Row[1]."','".$Row[2]."','".$Row[3]."','".$Row[4]."',,'".$Row[5]."')");
		}
		if ($query) {
				echo "Import data berhasil";
			}else{
				echo mysql_error();
			}
	}
	?>
	<h2>Data Mutasi Bank</h2>
	<table border="1">
		<tr>
			<th>No</th>
			<th>Tanggal</th>
			<th>Keterangan</th>
			<th>Cabang</th>			
			<th>Jumlah</th>
			<th>Status</th>
			<th>Saldo</th>
		</tr>
		<?php 		
		$no=1;
		$data = mysql_query("select * from bca_cv");
		while($d = mysql_fetch_array($data)){
			?>
			<tr>
				<td><?=$no++; ?></td>
				<td><?=$d['tanggal']; ?></td>
				<td><?=$d['keterangan']; ?></td>
				<td><?=$d['cabang']; ?></td>				
				<td><?=$d['jumlah']; ?></td>
				<td><?=$d['status']; ?></td>
				<td><?=$d['saldo']; ?></td>
			</tr>
			<?php 
		}
		?>
	</table>
</body>
</html>