<?php
require ("../../functions.php");
require ("../../libraries/dompdf/autoload.inc.php");
use Dompdf\Dompdf;
$dompdf = new Dompdf();

$html = "<html><head><style>
body { font-family:Arial, Helvetica, sans-serif;
        text-transform: capitalize;
        margin : -25px 70px }
h3{ text-align:center; }
table, th, td{ border-collapse: collapse; }
th { text-align:center; }
th, td{ padding:5px; font-size:.9em }
img { object-fit:cover; }
</style>";

$html .= "<body><p align='center'><img src='../../layout/dist/img/kop-surat.png' width='800px' style='margin-top:-7px;'></p><hr/>";

$tgl_awal = @$_GET['tgl_awal'];
$tgl_akhir = @$_GET['tgl_akhir'];
if(empty($tgl_awal) or empty($tgl_akhir)){
    $query = "SELECT id_wil, wil, nama, alamat, no_hp, tgl_daftar from pendaftaran GROUP BY wil ORDER BY id_wil ASC";
    $label = "Semua Data Pendaftaran Tanpa Pemasangan";
  }else{
    $query = "SELECT id_wil, wil, nama, alamat, no_hp, tgl_daftar from pendaftaran WHERE (tgl_daftar BETWEEN '".$tgl_awal."' AND '".$tgl_akhir."') ORDER BY id_wil ASC";
    $tgl_awal = date('d-m-Y', strtotime($tgl_awal));
    $tgl_akhir = date('d-m-Y', strtotime($tgl_akhir));
    $label = 'Periode Tanggal '.$tgl_awal.' s/d '.$tgl_akhir;
  }

$html .= "<body><h3>Laporan Rincian Data Pendaftaran Tanpa Pemasangan</h3>
<h5 align='right' style='margin-right:48px'>".$label."</h5>";

$html .= '<table border="1" align="center">
 <tr>
 <th>ID Cabang</th>
 <th>Cabang</th>
 <th>Nama</th>
 <th>Alamat</th>
 <th>Nomor Telepon</th>
 <th>Tanggal Daftar</th>
 </tr>';

$result = $conn->query($query);	
$row = mysqli_num_rows($result);
if($row > 0){
    while($data = $result->fetch_array())
    {
    $html .= "<tr>
    <td style='text-align:center;'>".$data['id_wil']."</td>
    <td style='text-align:center;'>".$data['wil']."</td>
    <td>".$data['nama']."</td>
    <td>".$data['alamat']."</td>
    <td style='text-align:center;'>".$data['no_hp']."</td>
    <td style='text-align:center;'>".$data['tgl_daftar']."</td>
    </tr>";
    }
}else{ // Jika data tidak ada
    echo "<tr><td colspan='5'>Data tidak ditemukan</td></tr>";
}

$html .= "<table style='padding-top:50px; padding-right:60px;'>
<tbody>
    <tr>
        <td></td>
        <td valign='top' align='center' style='font-size:.9em'> Paringin, " . tgl_indo(date('Y-m-d')) . "</td>
    </tr>
    <tr>
        <td style='color:rgb(0,0,0,0.0);'>_____________________________________________________________________</td>
        <td valign='top' align='center'><br/>Plt. Direktur,<br/><br/><br/><br/><br/></td>
    </tr>
    <tr>
        <td></td>
        <td valign='top' align='center'><b><u>MURJANI</u></b><br/>NIK. 63 08 044</td>
    </tr>
</tbody>
</table>";

$html .= "</body></html>";
$dompdf->loadHtml($html);
// Setting ukuran dan orientasi kertas
$dompdf->setPaper('A4', 'landscape');
// Rendering dari HTML Ke PDF
$dompdf->render();
// Melakukan output file Pdf, attachment = 0 pdf akan dibuka sebelum di download
$dompdf->stream("[Report] Data Pendaftaran",array("Attachment"=>0));
?>