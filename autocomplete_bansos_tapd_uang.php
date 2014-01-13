<?
ob_start();
session_start();
include("$DOCUMENT_ROOT/s/config.php");

$sql = "SELECT h.ban_kode as ban_kode, h.ban_nama as nama, h.ban_jalan as jalan, h.ban_rt as rt, h.ban_rw as rw, p.nm_propinsi as prop, d.nm_dati2 as dati2, kec.nm_kecamatan as kecamatan, kel.nm_kelurahan as kelurahan, h.ban_kodepos as kodepos, h.ban_tlp as no_tlp, h.ban_hp as no_hp, b.bank_nama as rekening, h.ban_norek as no_rekening, h.ban_besaran_bansos as besaran_bansos, h.ban_ren_guna as rencana_penggunaan, bd.*, o.opd_nama as opd_tujuan FROM tbl_bansos h, tbl_propinsi p, tbl_dati2 d, tbl_kecamatan kec, tbl_kelurahan kel, tbl_bank b, tbl_opd o, tbl_berita_acara_detail bd, tbl_berita_acara ba WHERE h.kd_propinsi = p.kd_propinsi AND h.kd_dati2=d.kd_dati2 AND h.kd_kecamatan=kec.kd_kecamatan AND h.kd_kelurahan=kel.kd_kelurahan AND h.bank_kode=b.bank_kode AND h.opd_kode=o.opd_kode AND h.ban_eval_opd = '1' AND h.ban_eval_tapd = '0' AND h.jh_kode = '1' AND bd.hib_kode=h.ban_kode AND bd.kode=ba.kode AND ba.tipe='BANSOS' AND h.ban_nama LIKE '%".$_REQUEST['term']."%'";
#die($sql);

//$sql = "SELECT * FROM v_pemohon_hibah WHERE status_eval_opd = 0 AND pemohon LIKE '%".$_REQUEST['term']."%'";
$result=$db->Execute($sql);
$results = array();
$i=0;
while ($row = $result->Fetchrow()) {
    $results[$i]['label'] = $row['nama']." - ".$row['jalan'].' RT.'.$row['rt'].' / RW.'.$row['rw'].', Kel. '.ucwords(strtolower($row['kelurahan'])).' Kel. '.ucwords(strtolower($row['kecamatan'])).', '.ucwords(strtolower($row['dati2'])).' - '.ucwords(strtolower($row['prop']));
    $results[$i]['value'] = $row['nama'];
	$results[$i]['id'] = $row['ban_kode'];
	$results[$i]['alamat'] = $row['jalan'].' RT.'.$row['rt'].' / RW.'.$row['rw'].' Kel. '.ucwords(strtolower($row['kelurahan'])).' Kec. '.ucwords(strtolower($row['kecamatan'])).', '.ucwords(strtolower($row['dati2'])).' - '.ucwords(strtolower($row['prop']));
	$results[$i]['besaran_bansos'] = $row['besaran_bansos'];
	$results[$i]['besaran_opd'] = $row['besaran_opd'];
	$results[$i]['rencana_penggunaan'] = $row['rencana_penggunaan'];
	$results[$i]['keterangan'] = $row['keterangan'];

    $i++;
}

echo json_encode($results);

?>