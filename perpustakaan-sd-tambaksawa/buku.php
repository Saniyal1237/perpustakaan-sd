<?php
include 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah_buku'])) {
    $stmt = $pdo->prepare("INSERT INTO buku (kode_buku, judul, pengarang, penerbit, stok, kategori) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$_POST['kode_buku'], $_POST['judul'], $_POST['pengarang'], $_POST['penerbit'], $_POST['stok'], $_POST['kategori']]);
    header("Location: buku.php");
    exit;
}

$bukuList = $pdo->query("SELECT * FROM buku")->fetchAll();
?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
        <h3 class="text-base font-bold text-gray-800 mb-4">Tambah Buku Baru</h3>
        <form method="POST" class="space-y-4">
            <input type="hidden" name="tambah_buku" value="1">
            <input type="text" name="kode_buku" placeholder="Kode Buku (Contoh: BKU-001)" required class="w-full px-4 py-2 border rounded-xl">
            <input type="text" name="judul" placeholder="Judul Buku" required class="w-full px-4 py-2 border rounded-xl">
            <input type="text" name="pengarang" placeholder="Pengarang" required class="w-full px-4 py-2 border rounded-xl">
            <input type="text" name="penerbit" placeholder="Penerbit" required class="w-full px-4 py-2 border rounded-xl">
            <input type="number" name="stok" placeholder="Stok" required class="w-full px-4 py-2 border rounded-xl">
            <select name="kategori" class="w-full px-4 py-2 border rounded-xl">
                <option value="Pelajaran">Pelajaran</option>
                <option value="Cerita">Cerita</option>
                <option value="Referensi">Referensi</option>
            </select>
            <button type="submit" class="w-full bg-emerald-800 text-white font-semibold py-2.5 rounded-xl">Simpan Buku</button>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm lg:col-span-2 overflow-hidden">
        <div class="p-6 border-b font-bold text-gray-800">Katalog Buku Perpustakaan</div>
        <table class="w-full text-left border-collapse text-sm">
            <tr class="bg-gray-50 border-b text-xs font-semibold text-gray-600 uppercase">
                <th class="p-4">Kode</th><th class="p-4">Judul & Pengarang</th><th class="p-4">Kategori</th><th class="p-4">Stok</th>
            </tr>
            <?php foreach ($bukuList as $b): ?>
            <tr class="border-b hover:bg-gray-50">
                <td class="p-4 font-mono font-semibold text-emerald-800"><?= $b['kode_buku']; ?></td>
                <td class="p-4"><b><?= $b['judul']; ?></b><br><span class="text-xs text-gray-500"><?= $b['pengarang']; ?></span></td>
                <td class="p-4"><?= $b['kategori']; ?></td>
                <td class="p-4 font-bold"><?= $b['stok']; ?> Eks</td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>