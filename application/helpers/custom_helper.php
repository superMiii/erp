<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//-- check logged user
function cek_session()
{
	$ci = &get_instance();
	$username = $ci->session->userdata('i_user');
	if ($username == '') {
		$ci->session->sess_destroy();
		redirect(base_url() . 'auth');
	}

	$set_language = $ci->session->userdata('language');
	if ($set_language) {
		$ci->lang->load('app_lang', $set_language);
	} else {
		$ci->lang->load('app_lang', 'english');
	}
}

function cek_login()
{
	$ci = &get_instance();
	$username = $ci->session->userdata('i_user');
	if ($username != '') {
		redirect(base_url());
	}
}

function get_company()
{
	$ci = &get_instance();
	$id_user = $ci->session->userdata('i_user');
	if ($id_user != '') {
		$ci->load->model('Mcustom');
		$query = $ci->Mcustom->get_company($id_user);
	} else {
		$query = '';
	}
	return $query;
}

function get_department()
{
	$ci = &get_instance();
	$id_user = $ci->session->userdata('i_user');
	if ($id_user != '') {
		$ci->load->model('Mcustom');
		$query = $ci->Mcustom->get_department($id_user);
	} else {
		$query = '';
	}
	return $query;
}

function get_level()
{
	$ci = &get_instance();
	$id_user = $ci->session->userdata('i_user');
	$i_department = $ci->session->userdata('i_department');
	if ($id_user != '' && $i_department != '') {
		$ci->load->model('Mcustom');
		$query = $ci->Mcustom->get_level($id_user, $i_department);
	} else {
		$query = '';
	}
	return $query;
}

function get_menu()
{
	$ci = &get_instance();
	$id_user = $ci->session->userdata('i_user');
	$i_department = $ci->session->userdata('i_department');
	$i_level = $ci->session->userdata('i_level');
	if ($id_user != '' && $i_department != '' && $i_level != '') {
		$ci->load->model('Mcustom');
		$query = $ci->Mcustom->get_menu($id_user, $i_department, $i_level);
	} else {
		$query = '';
	}
	return $query;
}

function get_sub_menu($id_menu)
{
	$ci = &get_instance();
	$id_user = $ci->session->userdata('i_user');
	$i_department = $ci->session->userdata('i_department');
	$i_level = $ci->session->userdata('i_level');
	if ($id_user != '') {
		$ci->load->model('Mcustom');
		$query = $ci->Mcustom->get_sub_menu($i_department, $i_level, $id_menu);
	} else {
		$query = '';
	}
	return $query;
}

if (!function_exists('check_status')) {
	function check_status($id, $dfrom, $dto)
	{
		$ci = get_instance();
		$id_user = $ci->session->userdata('i_user');
		$ci->load->model('Mcustom');
		$option = $ci->Mcustom->cek_status($id_user, $id, $dfrom, $dto)->row();
		return $option;
	}
}

function time_ago($timestamp)
{
	date_default_timezone_set('Asia/Jakarta');
	$time_ago 		 = strtotime($timestamp);
	$current_time 	 = time();
	$time_difference = $current_time - $time_ago;
	$seconds 		 = $time_difference;
	$minutes         = round($seconds / 60);        /* value 60 is seconds  */
	$hours           = round($seconds / 3600);       /*value 3600 is 60 minutes * 60 sec  */
	$days            = round($seconds / 86400);      /*86400 = 24 * 60 * 60;  */
	$weeks           = round($seconds / 604800);     /* 7*24*60*60;  */
	$months          = round($seconds / 2629440);    /*((365+365+365+365+366)/5/12)*24*60*60  */
	$years           = round($seconds / 31553280);   /*(365+365+365+365+366)/5 * 24 * 60 * 60  */
	if ($seconds <= 60) {
		return "Just Now";
	} else if ($minutes <= 60) {
		if ($minutes == 1) {
			return "one minute ago";
		} else {
			return "$minutes minutes ago";
		}
	} else if ($hours <= 24) {
		if ($hours == 1) {
			return "an hour ago";
		} else {
			return "$hours hrs ago";
		}
	} else if ($days <= 7) {
		if ($days == 1) {
			return "yesterday";
		} else {
			return "$days days ago";
		}
	} else if ($weeks <= 4.3) {  /*4.3 == 52/12*/
		if ($weeks == 1) {
			return "a week ago";
		} else {
			return "$weeks weeks ago";
		}
	} else if ($months <= 12) {
		if ($months == 1) {
			return "a month ago";
		} else {
			return "$months months ago";
		}
	} else {
		if ($years == 1) {
			return "one year ago";
		} else {
			return "$years years ago";
		}
	}
}

if (!function_exists('check_role')) {
	function check_role($id_menu, $id)
	{
		$ci = get_instance();
		$id_user = $ci->session->userdata('i_user');
		$ci->load->model('Mcustom');
		$query = $ci->Mcustom->cek_role($id_user, $id_menu, $id)->row();

		return $query;
	}
}

function formatSizeUnits($filename)
{
	if ($filename != '' || $filename != null) {
		$file_path = 'assets/upload/' . $filename;
		$bytes     = filesize($file_path);

		if ($bytes >= 1073741824) {
			$bytes = number_format($bytes / 1073741824, 2) . ' GB';
		} elseif ($bytes >= 1048576) {
			$bytes = number_format($bytes / 1048576, 2) . ' MB';
		} elseif ($bytes >= 1024) {
			$bytes = number_format($bytes / 1024, 2) . ' KB';
		} elseif ($bytes > 1) {
			$bytes = $bytes . ' bytes';
		} elseif ($bytes == 1) {
			$bytes = $bytes . ' byte';
		} else {
			$bytes = '0 bytes';
		}

		return $bytes;
	} else {
		return '0 KB';
	}
}

function formatSize($path, $filename)
{
	if (($filename != '' && $path != '') || ($filename != null && $path != null)) {
		$file_path = $path . $filename;
		$bytes     = filesize($file_path);

		if ($bytes >= 1073741824) {
			$bytes = number_format($bytes / 1073741824, 2) . ' GB';
		} elseif ($bytes >= 1048576) {
			$bytes = number_format($bytes / 1048576, 2) . ' MB';
		} elseif ($bytes >= 1024) {
			$bytes = number_format($bytes / 1024, 2) . ' KB';
		} elseif ($bytes > 1) {
			$bytes = $bytes . ' bytes';
		} elseif ($bytes == 1) {
			$bytes = $bytes . ' byte';
		} else {
			$bytes = '0 bytes';
		}

		return $bytes;
	} else {
		return '0 KB';
	}
}

//-- current date time function
if (!function_exists('current_datetime')) {
	function current_datetime()
	{
		$ci = get_instance();
		$query   = $ci->db->query("SELECT current_timestamp as c");
		$row     = $query->row();
		$waktu   = $row->c;
		return $waktu;
	}
}

//-- current date function
if (!function_exists('current_date')) {
	function current_date()
	{
		$ci = get_instance();
		$query   = $ci->db->query("SELECT current_date  as c");
		$row     = $query->row();
		$tanggal   = $row->c;
		return $tanggal;
	}
}

if (!function_exists('add_js')) {
	function add_js($file = '')
	{
		$str = '';
		$ci = &get_instance();
		$footer_js  = $ci->config->item('footer_js');

		if (empty($file)) {
			return;
		}

		if (is_array($file)) {
			if (!is_array($file) && count($file) <= 0) {
				return;
			}
			foreach ($file as $item) {
				$footer_js[] = $item;
			}
			$ci->config->set_item('footer_js', $footer_js);
		} else {
			$str = $file;
			$footer_js[] = $str;
			$ci->config->set_item('footer_js', $footer_js);
		}
	}
}


if (!function_exists('add_css')) {
	function add_css($file = '')
	{
		$str = '';
		$ci = &get_instance();
		$header_css = $ci->config->item('header_css');

		if (empty($file)) {
			return;
		}

		if (is_array($file)) {
			if (!is_array($file) && count($file) <= 0) {
				return;
			}
			foreach ($file as $item) {
				$header_css[] = $item;
			}
			$ci->config->set_item('header_css', $header_css);
		} else {
			$str = $file;
			$header_css[] = $str;
			$ci->config->set_item('header_css', $header_css);
		}
	}
}

if (!function_exists('add_key')) {
	function add_key($file = '')
	{
		$str = '';
		$ci = &get_instance();
		$key = $ci->config->item('key');

		if (empty($file)) {
			return;
		}

		if (is_array($file)) {
			if (!is_array($file) && count($file) <= 0) {
				return;
			}
			foreach ($file as $item) {
				$key[] = $item;
			}
			$ci->config->set_item('key', $key);
		} else {
			$str = $file;
			$key[] = $str;
			$ci->config->set_item('key', $key);
		}
	}
}

if (!function_exists('put_headers')) {
	function put_headers()
	{
		$str = '';
		$ci = &get_instance();
		$header_css  = $ci->config->item('header_css');

		foreach ($header_css as $item) {
			$str .= '<link rel="stylesheet" type="text/css" href="' . base_url() . '' . $item . '"/>' . "\n";
		}
		return $str;
	}
}
if (!function_exists('put_footer')) {
	function put_footer()
	{
		$str = '';
		$ci = &get_instance();
		$key  = $ci->config->item('key');
		$item_key = '<script>';
		foreach ($key as $item) {
			$item_key .= $item;
		}
		$item_key .= '</script>';
		$footer_js  = $ci->config->item('footer_js');
		foreach ($footer_js as $item) {
			$str .= '<script src="' . base_url() . '' . $item . '"></script>' . "\n";
		}
		/* return $item_key . "\n" . $str; */
		return $str;
	}
}

function encrypt_password($string)
{
	$output = false;
	$secret_key     = 'merubahpassword';
	$secret_iv      = 'menjadilieur';
	$encrypt_method = 'aes-256-cbc';
	$key    = hash("sha256", $secret_key);
	$iv     = substr(hash("sha256", $secret_iv), 0, 16);
	$result = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
	$output = base64_encode($result);
	$output = str_replace('=', '', $output);
	return $output;
}

function decrypt_password($string)
{
	$output = false;
	$secret_key     = 'merubahpassword';
	$secret_iv      = 'menjadilieur';
	$encrypt_method = 'aes-256-cbc';
	$key    = hash("sha256", $secret_key);
	$iv = substr(hash("sha256", $secret_iv), 0, 16);
	$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
	return $output;
}

function encrypt_url($string)
{
	$output = false;
	$secret_key     = 'dukanaonteuapal';
	$secret_iv      = 'nanaonan';
	$encrypt_method = 'aes-256-cbc';
	$key    = hash("sha256", $secret_key);
	$iv     = substr(hash("sha256", $secret_iv), 0, 16);
	$result = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
	$output = base64_encode($result);
	$output = str_replace('=', '', $output);
	return $output;
}

function decrypt_url($string)
{
	$output = false;
	$secret_key     = 'dukanaonteuapal';
	$secret_iv      = 'nanaonan';
	$encrypt_method = 'aes-256-cbc';
	$key    = hash("sha256", $secret_key);
	$iv = substr(hash("sha256", $secret_iv), 0, 16);
	$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
	return $output;
}

function replace($str = '', $sp = '')
{
	$replace_string = '';

	if (!empty($str)) {
		$q_separator = preg_quote($sp, '#');

		$trans = array(
			'_' => $sp,
			'&.+?;' => '',
			'[^\w\d -]' => '',
			'\s+' => $sp,
			'(' . $q_separator . ')+' => $sp
		);

		$str = strip_tags($str);

		foreach ($trans as $key => $val) {
			$str = preg_replace('#' . $key . '#i' . (UTF8_ENABLED ? 'u' : ''), $val, $str);
		}

		$str = strtolower($str);
		$replace_string = trim(trim($str, $sp));
	}

	return $replace_string;
}


function get_setting($config_name)
{
	$ci = &get_instance();
	return $ci->db->query("select config_value from tbl_config where config_name = '$config_name'")->row()->config_value;
}

/* if (!function_exists('get_grade')) {
	function get_grade()
	{
		$ci = &get_instance();
		$i_company = $ci->session->userdata('i_company');
		return $ci->db->query("SELECT i_product_grade FROM tr_product_grade WHERE f_default = 't' AND i_company = '$i_company' ")->row()->i_product_grade;
	}
} */

if (!function_exists('get_area_pusat')) {
	function get_area_pusat()
	{
		$ci = &get_instance();
		$i_company = $ci->session->userdata('i_company');
		return $ci->db->query("SELECT i_area FROM tr_area WHERE i_company = '$i_company' AND f_area_pusat = 't' ")->row()->i_area;
	}
}

if (!function_exists('get_periode')) {
	function get_periode()
	{
		$ci = get_instance();
		$i_company = $ci->session->userdata('i_company');
		$cek = $ci->db->query("SELECT i_periode FROM public.tm_periode WHERE i_company = '$i_company' and f_all='t' ");
		if ($cek->num_rows() > 0) {
			return $cek->row()->i_periode;
		} else {
			return date('Ym');
		}
	}
}

if (!function_exists('get_periode2')) {
	function get_periode2()
	{
		$ci = get_instance();
		$i_company = $ci->session->userdata('i_company');
		$cek = $ci->db->query("SELECT i_periode FROM public.tm_periode WHERE i_company = '$i_company' and f_kasbank='t' ");
		if ($cek->num_rows() > 0) {
			return $cek->row()->i_periode;
		} else {
			return date('Ym');
		}
	}
}

if (!function_exists('get_periode3')) {
	function get_periode3()
	{
		$ci = get_instance();
		$i_company = $ci->session->userdata('i_company');
		$cek = $ci->db->query("SELECT i_periode FROM public.tm_periode WHERE i_company = '$i_company' and f_stock='t' ");
		if ($cek->num_rows() > 0) {
			return $cek->row()->i_periode;
		} else {
			return date('Ym');
		}
	}
}

if (!function_exists('get_min_date')) {
	function get_min_date()
	{
		$ci = get_instance();
		$i_company = $ci->session->userdata('i_company');
		$cek = $ci->db->query("SELECT i_periode FROM public.tm_periode WHERE i_company = '$i_company'  and f_all='t' ");
		if ($cek->num_rows() > 0) {
			$i_periode = $cek->row()->i_periode;
			$periode = substr($i_periode, 0, 4) . '-' . substr($i_periode, 4, 2) . '-01';
			return $periode;
		} else {
			return date('Y-m-d');
		}
	}
}

if (!function_exists('get_min_date2')) {
	function get_min_date2()
	{
		$ci = get_instance();
		$i_company = $ci->session->userdata('i_company');
		$cek = $ci->db->query("SELECT i_periode FROM public.tm_periode WHERE i_company = '$i_company'  and f_kasbank='t' ");
		if ($cek->num_rows() > 0) {
			$i_periode = $cek->row()->i_periode;
			$periode = substr($i_periode, 0, 4) . '-' . substr($i_periode, 4, 2) . '-01';
			return $periode;
		} else {
			return date('Y-m-d');
		}
	}
}

if (!function_exists('get_min30')) {
	function get_min30()
	{
		$min30 = date('Y-m-d', strtotime("-30 day", strtotime(date('Y-m-d'))));
		return $min30;
	}
}

if (!function_exists('tx_tgl')) {
	function tx_tgl()
	{
		$ci = get_instance();
		$cek = $ci->db->query("SELECT d_tgl FROM public.tx_tgl WHERE f_tx = 't' ");
		if ($cek->num_rows() > 0) {
			$d_tg = $cek->row()->d_tgl;
			return $d_tg;
		} else {
			return date('Y-m-d');
		}
	}
}

function format_rupiah($angka)
{
	$hasil_rupiah = "Rp " . number_format($angka, 2, '.', ',');
	return $hasil_rupiah;
}

if (!function_exists('bulan')) {
	function bulan($a)
	{
		if ($a == '') $b = '';
		if ($a == '01') $b = 'Januari';
		if ($a == '02') $b = 'Februari';
		if ($a == '03') $b = 'Maret';
		if ($a == '04') $b = 'April';
		if ($a == '05') $b = 'Mei';
		if ($a == '06') $b = 'Juni';
		if ($a == '07') $b = 'Juli';
		if ($a == '08') $b = 'Agustus';
		if ($a == '09') $b = 'September';
		if ($a == '10') $b = 'Oktober';
		if ($a == '11') $b = 'November';
		if ($a == '12') $b = 'Desember';
		return $b;
	}
}

function penyebut($nilai)
{
	$nilai = abs($nilai);
	$huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
	$temp = "";
	if ($nilai < 12) {
		$temp = " " . $huruf[$nilai];
	} else if ($nilai < 20) {
		$temp = penyebut($nilai - 10) . " belas";
	} else if ($nilai < 100) {
		$temp = penyebut($nilai / 10) . " puluh" . penyebut($nilai % 10);
	} else if ($nilai < 200) {
		$temp = " seratus" . penyebut($nilai - 100);
	} else if ($nilai < 1000) {
		$temp = penyebut($nilai / 100) . " ratus" . penyebut($nilai % 100);
	} else if ($nilai < 2000) {
		$temp = " seribu" . penyebut($nilai - 1000);
	} else if ($nilai < 1000000) {
		$temp = penyebut($nilai / 1000) . " ribu" . penyebut($nilai % 1000);
	} else if ($nilai < 1000000000) {
		$temp = penyebut($nilai / 1000000) . " juta" . penyebut($nilai % 1000000);
	} else if ($nilai < 1000000000000) {
		$temp = penyebut($nilai / 1000000000) . " milyar" . penyebut(fmod($nilai, 1000000000));
	} else if ($nilai < 1000000000000000) {
		$temp = penyebut($nilai / 1000000000000) . " trilyun" . penyebut(fmod($nilai, 1000000000000));
	}
	return $temp;
}

function terbilang($nilai)
{
	if ($nilai < 0) {
		$hasil = "minus " . trim(penyebut($nilai));
	} else {
		$hasil = trim(penyebut($nilai));
	}
	return $hasil;
}

function konci()
{
	return 'readonly';
}

function koncix()
{
	return '';
}


function formatYMD($tgl)
{
	return date('Y-m-d', strtotime($tgl));
}


function formatYM($tgl)
{
	return date('Ym', strtotime($tgl));
}

function formatym2($tgl)
{
	return date('ym', strtotime($tgl));
}

function formatY($tgl)
{
	return date('Y', strtotime($tgl));
}

function to_pg_array($set) {
    settype($set, 'array'); // can be called with a scalar or array
    $result = array();
    foreach ($set as $t) {
        if (is_array($t)) {
            $result[] = to_pg_array($t);
        } else {
            $t = str_replace('"', '\\"', $t); // escape double quote
            if (! is_numeric($t)) // quote only non-numeric values
                $t = '"' . $t . '"';
            $result[] = $t;
        }
    }
    return implode(",", $result); // format
}