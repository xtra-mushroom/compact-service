<?php 
require "../functions.php";
$openDaftar = "menu-open";
$activeDaftar = "active"; $activeCariDaftar = "active";
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include_once ("../partials/head.php");
    include_once ("../partials/cssdatatables.php");
    ?>
</head>
<?php include_once ("../database.php") ?>

<body class="hold-transition sidebar-mini layout-fixed">
<script src="../libraries/sweetalert2/dist/sweetalert2.min.js"></script>
    <div class="wrapper">
        <!-- Navbar right-->
        <?php include_once ("../partials/navbar.php") ?>

        <!-- Sidebar -->
        <?php include_once ("../partials/sidebar.php") ?>

        <!-- Content -->
        <div class="content-wrapper">
            <section class="content-header">
<?php 
                if(isset($_SESSION['hasil'])){
                    if($_SESSION['hasil']){
?>
                    <script>
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: '<?php echo $_SESSION["pesan"] ?>',
                        showConfirmButton: true
                        })
                    </script>
<?php 
                    } else {
?>
                    <script>
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: '<?php echo $_SESSION["pesan"] ?>',
                        showConfirmButton: true
                        })
                    </script>
<?php
                    }
                    unset($_SESSION['pesan']);
                    unset($_SESSION['hasil']);
                }
?>
                <div class="container-fluid">
                    <div class="row mb-1">
                        <div class="col-sm-7">
                            <h1 class="mr-4">
                                Cari Data & Cetak Kwitansi Pendaftaran
                                <button type="button" class="btn btn-sm btn-danger rounded-circle" data-container="body" data-toggle="popover" data-placement="bottom" data-content='Data yang ditampilkan bukan data pelanggan secara real-time, cari data pelanggan di menu "Data Pelanggan"'>
                                    <i class="bi bi-exclamation-diamond-fill"></i>
                                </button>
                            </h1>
                            
                        </div>
                        <div class="col-sm-5">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item active">Pendaftaran</li>
                                <li class="breadcrumb-item">Cari & Cetak</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="myTable" class="table table-sm table-bordered table-hover">
                                            <thead align="center">
                                                <tr>
                                                    <th>Actions</th>
                                                    <th>Nomor Pendaftaran</th>
                                                    <th>Nomor Sambungan</th>
                                                    <th>Tanggal Daftar</th>
                                                    <th>Nomor KTP</th>
                                                    <th>Nama</th>
                                                    <th>Jenis Kelamin</th>
                                                    <th>Alamat</th>
                                                    <th>Nomor HP</th>
                                                    <th>Wilayah</th>
                                                </tr>
                                            </thead>
                                            
                                            <tbody>
                                                <?php
                                                $database = new Database();
                                                $db = $database->getConnection();

                                                $sqlDaftar = "SELECT * FROM pendaftaran";
                                                $resultDaftar = $db->prepare($sqlDaftar);
                                                $resultDaftar->execute();
                                                
                                                while ($data = $resultDaftar->fetch(PDO::FETCH_ASSOC)) {
                                                    $no = $data['no_pend'];
                                                ?>

                                                <tr>
                                                    <td align="center">
                                                        <a href="edit.php?no_pend=<?= $no ?>" class="btn btn-sm btn-success">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </a>
                                                        <a href="report/kwitansi.php?no_pend=<?= $no ?>" class="btn btn-sm btn-warning" target="_blank">
                                                            <i class="bi bi-printer"></i>
                                                        </a>
                                                    </td>
                                                    <td align="center"><?= $no ?></td>
                                                    <td><?= $data['no_ds'] ?></td>
                                                    <td align='center'><?= $data['tgl_daftar'] ?></td>
                                                    <td><?= $data['no_ktp'] ?></td>
                                                    <td><?= $data['nama'] ?></td>
                                                    <td><?= $data['jenis_kel'] ?></td>

                                                    <?php  
                                                    // agar yang tampil adalah nama kecamatannya
                                                    $valueKec = $data['kecamatan'];
                                                    $queryKec = "SELECT * FROM kecamatan WHERE id='$valueKec'";
                                                    $resultKec = $conn->query($queryKec);
                                                    $dataKec = $resultKec->fetch_assoc();
                                                    if($data['kecamatan'] == $dataKec['id']){
                                                        $namaKec = $dataKec['nama'];
                                                    }
                                                    // agar yang tampil adalah nama desanya
                                                    $valueDesa = $data['desa'];
                                                    $queryDesa = "SELECT * FROM desa WHERE id='$valueDesa'";
                                                    $resultDesa = $conn->query($queryDesa);
                                                    $dataDesa = $resultDesa->fetch_assoc();
                                                    if($data['desa'] == $dataDesa['id']){
                                                        $namaDesa = $dataDesa['nama'];
                                                    }
                                                    ?>  

                                                    <td><?= $data['alamat'] . ', ' . $namaDesa . ', ' . $namaKec ?></td>
                                                    <td align='center'><?= $data['no_hp'] ?></td>
                                                    <td align='center'><?= $data['wil'] ?></td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>                                            
                                </div>  
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <?php
    include_once ("../partials/importjs.php");
    include_once ("../partials/scriptsdatatables.php");
    ?>

    <script>
        $(function () {
        $('[data-toggle="popover"]').popover()
        })
    </script>

</body>
</html>