<?php
include 'includes/header.php';

// Mengambil data statistik dari database
$totalSiswa = $pdo->query("SELECT COUNT(*) FROM siswa")->fetchColumn();
$totalBuku = $pdo->query("SELECT COUNT(*) FROM buku")->fetchColumn();
$sedangDipinjam = $pdo->query("SELECT COUNT(*) FROM peminjaman WHERE status = 'Dipinjam'")->fetchColumn();
$absensiHariIni = $pdo->query("SELECT COUNT(*) FROM absensi WHERE DATE(waktu) = CURDATE()")->fetchColumn();

// Data untuk rekap harian, mingguan, dan bulanan
$peminjamanBaruHariIni = $pdo->query("SELECT COUNT(*) FROM peminjaman WHERE DATE(tanggal_pinjam) = CURDATE()")->fetchColumn();
$kunjunganMingguIni = $pdo->query("SELECT COUNT(*) FROM absensi WHERE WEEK(waktu) = WEEK(CURDATE())")->fetchColumn();
$peminjamanMingguIni = $pdo->query("SELECT COUNT(*) FROM peminjaman WHERE WEEK(tanggal_pinjam) = WEEK(CURDATE())")->fetchColumn();
$kunjunganBulanIni = $pdo->query("SELECT COUNT(*) FROM absensi WHERE MONTH(waktu) = MONTH(CURDATE())")->fetchColumn();
?>

<div class="space-y-6">
    <!-- Top Bar Title & Admin Info -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center bg-white p-6 rounded-2xl border border-gray-200 shadow-sm gap-4">
        <div>
            <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">Dashboard</span>
            <h1 class="text-xl font-bold text-gray-800 mt-1">Dashboard Pemantauan Perpustakaan</h1>
        </div>
        <div class="flex items-center gap-3">
            <div class="text-right">
                <div class="text-sm font-bold text-gray-800">Admin Perpustakaan</div>
                <div class="text-xs text-gray-500"><?= date('l, d F Y'); ?></div>
            </div>
            <div class="w-10 h-10 rounded-full bg-emerald-800 text-white flex items-center justify-center font-bold shadow">
                <i class="fa-solid fa-user"></i>
            </div>
        </div>
    </div>

    <!-- 4 Kotak Statistik Utama -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Card 1: Total Murid -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Murid</p>
                <h3 class="text-3xl font-extrabold text-gray-800 mt-1"><?= $totalSiswa; ?></h3>
                <span class="text-xs font-semibold text-emerald-600 mt-2 inline-block">↑ Terdaftar di Sistem</span>
            </div>
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl shadow-inner">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
        </div>

        <!-- Card 2: Total Koleksi Buku -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Koleksi Buku</p>
                <h3 class="text-3xl font-extrabold text-gray-800 mt-1"><?= $totalBuku; ?></h3>
                <span class="text-xs font-semibold text-emerald-600 mt-2 inline-block">Tersedia di Rak</span>
            </div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-xl shadow-inner">
                <i class="fa-solid fa-book"></i>
            </div>
        </div>

        <!-- Card 3: Sedang Dipinjam -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Sedang Dipinjam</p>
                <h3 class="text-3xl font-extrabold text-gray-800 mt-1"><?= $sedangDipinjam; ?></h3>
                <span class="text-xs font-semibold text-amber-600 mt-2 inline-block">Buku Aktif</span>
            </div>
            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center text-xl shadow-inner">
                <i class="fa-solid fa-hand-holding"></i>
            </div>
        </div>

        <!-- Card 4: Absensi Hari Ini -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Absensi Hari Ini</p>
                <h3 class="text-3xl font-extrabold text-gray-800 mt-1"><?= $absensiHariIni; ?></h3>
                <span class="text-xs font-semibold text-purple-600 mt-2 inline-block">Murid Berkunjung</span>
            </div>
            <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center text-xl shadow-inner">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
        </div>
    </div>

    <!-- 3 Kolom Rekap (Harian, Mingguan, Bulanan) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Pemantauan Harian -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4">
            <div class="flex items-center gap-2 border-b border-gray-100 pb-3">
                <i class="fa-solid fa-chart-line text-emerald-600"></i>
                <h3 class="font-bold text-gray-800">Pemantauan Harian</h3>
            </div>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between items-center py-2 border-b border-gray-50">
                    <span class="text-gray-500">Kunjungan Perpustakaan</span>
                    <span class="font-bold text-gray-800"><?= $absensiHariIni; ?> Murid</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-50">
                    <span class="text-gray-500">Peminjaman Baru</span>
                    <span class="font-bold text-gray-800"><?= $peminjamanBaruHariIni; ?> Buku</span>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span class="text-gray-500">Buku Masuk Hari Ini</span>
                    <span class="font-bold text-gray-800">0 Eksemplar</span>
                </div>
            </div>
        </div>

        <!-- Rekap Mingguan -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4">
            <div class="flex items-center gap-2 border-b border-gray-100 pb-3">
                <i class="fa-solid fa-calendar-week text-blue-600"></i>
                <h3 class="font-bold text-gray-800">Rekap Mingguan</h3>
            </div>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between items-center py-2 border-b border-gray-50">
                    <span class="text-gray-500">Total Kunjungan Minggu Ini</span>
                    <span class="font-bold text-gray-800"><?= $kunjunganMingguIni; ?> Kunjungan</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-50">
                    <span class="text-gray-500">Buku Masuk Minggu Ini</span>
                    <span class="font-bold text-gray-800">0 Buku</span>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span class="text-gray-500">Peminjaman Minggu Ini</span>
                    <span class="font-bold text-gray-800"><?= $peminjamanMingguIni; ?> Transaksi</span>
                </div>
            </div>
        </div>

        <!-- Rekap Bulanan -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4">
            <div class="flex items-center gap-2 border-b border-gray-100 pb-3">
                <i class="fa-solid fa-calendar-days text-purple-600"></i>
                <h3 class="font-bold text-gray-800">Rekap Bulanan</h3>
            </div>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between items-center py-2 border-b border-gray-50">
                    <span class="text-gray-500">Total Kunjungan Bulan Ini</span>
                    <span class="font-bold text-gray-800"><?= $kunjunganBulanIni; ?> Murid</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-50">
                    <span class="text-gray-500">Sirkulasi Buku</span>
                    <span class="font-bold text-emerald-600 bg-emerald-50 px-2.5 py-0.5 rounded-lg text-xs">Aktif & Terkendali</span>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span class="text-gray-500">Status Sistem</span>
                    <span class="font-bold text-emerald-600 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Normal
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>