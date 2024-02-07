<?php
/**
* @Author   [Hilmy] - 20090108
* @desc     Membuat laporan berupa file MS-EXCEL, menggunakan versi IE > 6
* @param    $data       -> Data dalam bentuk HTML
* @param    $nama_file  -> Nama file MS-EXCEL yang akan dibuat
* @return   PDF File
*/
//function SapuaCore_userapi_generateExcelFile($args)
//{
    set_time_limit(36000);
    ini_set("memory_limit","128M");
    
    //extract($args);

    // We'll be outputting an Excel file
    if ($export_excel1 != '') { // jika ke excel
		header("Content-type: application/x-msexcel"); 
		//header ('Content-Type: application/vnd.ms-excel');
	}
    else {
		//khusus ods
		header("Content-Type: application/vnd.oasis.opendocument.spreadsheet ods; charset=utf-8");
	}
    
    // It will be called as the name sent by the parameter $nama_file
    header('Content-Disposition: attachment; filename="' . $nama_file . '"');
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false);

    print_r($data);
    
    return true;
//}
?>
