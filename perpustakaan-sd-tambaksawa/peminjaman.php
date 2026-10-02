<?php
include 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['pinjam'])) {
        $stmt = $pdo->prepare("INSERT INTO peminjaman (nisn, kode_buku, tanggal_pinjam, batas_waktu, status) VALUES (?, ?, ?, ?, 'Dipinjam')");
        $stmt->execute([$_POST['nisn'], $_POST['kode_buku'], $_POST['tanggal_pinjam'], $_POST['batas_waktu']]);
    } elseif (isset($_POST['kembali'])) {
        $stmt = $pdo->prepare("UPDATE peminjaman SET status = 'Selesai' WHERE id = ?");
        $stmt->execute([$_POST['id_peminjaman']]);
    }
    header("Location: peminjaman.php");
    exit;
}
$peminjaman = $pdo->query("
    SELECT peminjaman.*, siswa.nama AS nama_siswa, buku.judul AS judul_buku 
    FROM peminjaman 
    JOIN siswa ON peminjaman.nisn = siswa.nisn 
    JOIN buku ON peminjaman.kode_buku = buku.kode_buku 
    WHERE peminjaman.status = 'Dipinjam'
    ORDER BY peminjaman.id DESC
")->fetchAll();

$peminjamanList = $pdo->query("SELECT peminjaman.*, siswa.nama FROM peminjaman JOIN siswa ON peminjaman.nisn = siswa.nisn ORDER BY id DESC")->fetchAll();
?>

<div class="space-y-6">
    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Form Transaksi Peminjaman Buku</h3>
        <form method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="hidden" name="pinjam" value="1">
            <input type="text" name="nisn" placeholder="NISN Siswa" required class="px-4 py-2.5 border rounded-xl">
            <input type="text" name="kode_buku" placeholder="Kode Buku" required class="px-4 py-2.5 border rounded-xl">
            <input type="date" name="tanggal_pinjam" value="<?= date('Y-m-d'); ?>" required class="px-4 py-2.5 border rounded-xl">
            <input type="date" name="batas_waktu" value="<?= date('Y-m-d', strtotime('+7 days')); ?>" required class="px-4 py-2.5 border rounded-xl">
            <button type="submit" class="md:col-span-4 bg-emerald-800 text-white font-semibold py-3 rounded-xl">Proses Peminjaman</button>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b font-bold text-gray-800">Daftar Transaktif Aktif</div>
       <table class="w-full text-left border-collapse">
    <thead>
        <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-600 uppercase">
            <th class="p-4">Peminjam</th>
            <th class="p-4">Judul Buku</th> <!-- Diubah dari Kode Buku -->
            <th class="p-4">Batas Waktu</th>
            <th class="p-4">Status</th>
            <th class="p-4">Aksi</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-100 text-sm">
        <?php foreach ($peminjaman as $row): ?>
        <tr class="hover:bg-gray-50 transition">
            <td class="p-4">
                <div class="font-bold text-gray-800"><?= $row['nama_siswa']; ?></div>
                <div class="text-xs text-gray-500">NISN: <?= $row['nisn']; ?></div>
            </td>
            <td class="p-4 font-semibold text-gray-800"><?= $row['judul_buku']; ?></td> <!-- Menampilkan Judul Buku -->
            <td class="p-4 text-red-600 font-medium"><?= $row['batas_waktu']; ?></td>
            <td class="p-4">
                <span class="bg-amber-50 text-amber-700 px-2.5 py-1 rounded-lg text-xs font-semibold"><?= $row['status']; ?></span>
            </td>
            <td class="p-4">
                <a href="peminjaman.php?selesai=<?= $row['id']; ?>" class="bg-emerald-800 hover:bg-emerald-900 text-white text-xs font-semibold px-4 py-2 rounded-xl transition shadow">Selesaikan</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>