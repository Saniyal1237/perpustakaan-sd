<?php
include 'includes/header.php';
$logAbsen = $pdo->query("SELECT absensi.*, siswa.nama, siswa.kelas FROM absensi JOIN siswa ON absensi.nisn = siswa.nisn")->fetchAll();
?>

<div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
    <div class="flex justify-between items-center pb-4 mb-4 border-b">
        <h3 class="text-xl font-bold text-gray-800">Laporan Bulanan Perpustakaan SD Tambaksawa</h3>
        <button onclick="window.print()" class="bg-gray-800 text-white font-semibold px-5 py-2 rounded-xl text-sm">Cetak Laporan</button>
    </div>
    <table class="w-full text-left border-collapse text-xs border border-gray-200">
        <tr class="bg-gray-100 font-semibold">
            <th class="p-3 border">Waktu</th><th class="p-3 border">NISN</th><th class="p-3 border">Nama Siswa</th><th class="p-3 border">Kelas</th>
        </tr>
        <?php foreach ($logAbsen as $l): ?>
        <tr>
            <td class="p-3 border"><?= $l['waktu']; ?></td>
            <td class="p-3 border"><?= $l['nisn']; ?></td>
            <td class="p-3 border"><?= $l['nama']; ?></td>
            <td class="p-3 border">Kelas <?= $l['kelas']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php include 'includes/footer.php'; ?>