<?php 
include 'includes/header.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['code_buku'];
    $jumlah = (int)$_POST['jumlah'];
    $tanggal = $_POST['tanggal_masuk'];
    $sumber = trim($_POST['sumber']);

    // Tambah record buku masuk
    $stmt = $pdo->prepare("INSERT INTO buku_masuk (code_buku, jumlah, tanggal_masuk, sumber) VALUES (?, ?, ?, ?)");
    $stmt->execute([$code, $jumlah, $tanggal, $sumber]);

    // Update stok buku
    $update = $pdo->prepare("UPDATE buku SET stock = stock + ? WHERE code_buku = ?");
    $update->execute([$jumlah, $code]);

    $message = "<div class='bg-emerald-100 text-emerald-800 p-3 rounded-xl mb-4'>Buku masuk berhasil dicatat dan stok diperbarui!</div>";
}

$bukuList = $pdo->query("SELECT * FROM buku")->fetchAll();
?>

<div class="space-y-6">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <h2 class="text-2xl font-bold text-gray-800">Pencatatan & Rekap Buku Masuk</h2>
        <p class="text-sm text-gray-500">Catat penambahan koleksi buku mingguan ke perpustakaan.</p>
    </div>

    <?= $message; ?>

    <!-- Form Buku Masuk -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Input Buku Masuk Baru</h3>
        <form method="POST" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Buku</label>
                <select name="code_buku" required class="w-full px-4 py-3 rounded-xl">
                    <?php foreach($bukuList as $b): ?>
                        <option value="<?= $b['code_buku']; ?>"><?= $b['code_buku']; ?> - <?= htmlspecialchars($b['judul']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Masuk</label>
                <input type="number" name="jumlah" required min="1" value="1" class="w-full px-4 py-3 rounded-xl">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Masuk</label>
                <input type="date" name="tanggal_masuk" required value="<?= date('Y-m-d'); ?>" class="w-full px-4 py-3 rounded-xl">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sumber / Donatur</label>
                <input type="text" name="sumber" placeholder="Contoh: Bantuan Pemerintah / Pembelian" class="w-full px-4 py-3 rounded-xl">
            </div>
            <div class="sm:col-span-2">
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium px-6 py-3 rounded-xl transition shadow">Simpan Buku Masuk</button>
            </div>
        </form>
    </div>

    <!-- Tabel Rekap Buku Masuk Mingguan -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Rekapitulasi Buku Masuk (Mingguan / Bulanan)</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b text-gray-500 font-semibold">
                        <th class="pb-3">Tanggal</th>
                        <th class="pb-3">Kode Buku</th>
                        <th class="pb-3">Judul Buku</th>
                        <th class="pb-3">Jumlah</th>
                        <th class="pb-3">Sumber</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php
                    $stmt = $pdo->query("SELECT bm.*, b.judul FROM buku_masuk bm JOIN buku b ON bm.code_buku = b.code_buku ORDER BY bm.tanggal_masuk DESC");
                    while($row = $stmt->fetch()):
                    ?>
                    <tr>
                        <td class="py-3 text-gray-600"><?= $row['tanggal_masuk']; ?></td>
                        <td class="py-3 font-semibold text-emerald-600"><?= $row['code_buku']; ?></td>
                        <td class="py-3 font-medium text-gray-800"><?= htmlspecialchars($row['judul']); ?></td>
                        <td class="py-3"><span class="bg-emerald-50 text-emerald-800 px-2 py-1 rounded-md text-xs font-semibold">+<?= $row['jumlah']; ?> Eks</span></td>
                        <td class="py-3 text-gray-600"><?= htmlspecialchars($row['sumber']); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>