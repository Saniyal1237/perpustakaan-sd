<?php 
include 'includes/header.php';
?>

<div class="space-y-6">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <h2 class="text-2xl font-bold text-gray-800">Rekapitulasi Peminjaman Mingguan</h2>
        <p class="text-sm text-gray-500">Laporan lengkap riwayat peminjaman dan pengembalian buku.</p>
    </div>

    <!-- Tabel Rekapitulasi -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Semua Riwayat Peminjaman</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b text-gray-500 font-semibold">
                        <th class="pb-3">Siswa</th>
                        <th class="pb-3">Buku</th>
                        <th class="pb-3">Tgl Pinjam</th>
                        <th class="pb-3">Batas Waktu</th>
                        <th class="pb-3">Tgl Kembali</th>
                        <th class="pb-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php
                    $stmt = $pdo->query("SELECT p.*, s.nama, b.judul FROM peminjaman p JOIN siswa s ON p.nisn = s.nisn JOIN buku b ON p.code_buku = b.code_buku ORDER BY p.tanggal_pinjam DESC");
                    while($row = $stmt->fetch()):
                    ?>
                    <tr>
                        <td class="py-3 font-medium text-gray-800"><?= htmlspecialchars($row['nama']); ?></td>
                        <td class="py-3 text-gray-600"><?= htmlspecialchars($row['judul']); ?></td>
                        <td class="py-3 text-gray-600"><?= $row['tanggal_pinjam']; ?></td>
                        <td class="py-3 text-gray-600"><?= $row['batas_waktu']; ?></td>
                        <td class="py-3 text-gray-600"><?= $row['tanggal_kembali'] ?: '-'; ?></td>
                        <td class="py-3">
                            <?php if($row['status'] == 'Dipinjam'): ?>
                                <span class="bg-amber-100 text-amber-800 px-2.5 py-1 rounded-md text-xs font-semibold">Dipinjam</span>
                            <?php else: ?>
                                <span class="bg-emerald-100 text-emerald-800 px-2.5 py-1 rounded-md text-xs font-semibold">Kembali</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>