<?php
include 'includes/header.php';

$pesan = '';
$tipe_form = $_POST['tipe_form'] ?? 'terdaftar';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['aksi_absen'])) {
        $tipe_form = 'terdaftar';
        $nisn = trim($_POST['nisn']);
        
        // Cek apakah siswa terdaftar di database
        $stmt = $pdo->prepare("SELECT * FROM siswa WHERE nisn = ?");
        $stmt->execute([$nisn]);
        $siswa = $stmt->fetch();

        if ($siswa) {
            $insert = $pdo->prepare("INSERT INTO absensi (nisn) VALUES (?)");
            $insert->execute([$nisn]);
            $pesan = "<div class='p-4 mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium'>Absensi berhasil dicatat untuk <b>{$siswa['nama']}</b>!</div>";
        } else {
            $pesan = "<div class='p-4 mb-4 bg-red-50 border border-red-200 text-red-800 rounded-xl text-sm font-medium'>NISN tidak ditemukan di database tabel siswa! Silakan daftarkan melalui tab 'Tambah Siswa Baru'.</div>";
        }
    } elseif (isset($_POST['tambah_siswa'])) {
        $tipe_form = 'baru';
        $nisn = trim($_POST['nisn']);
        $nama = trim($_POST['nama']);
        $jk = $_POST['jk'];
        $kelas = $_POST['kelas'];

        try {
            // Masukkan ke tabel siswa
            $stmt = $pdo->prepare("INSERT INTO siswa (nisn, nama, jk, kelas) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nisn, $nama, $jk, $kelas]);

            // Otomatis catatkan absensinya juga
            $insert = $pdo->prepare("INSERT INTO absensi (nisn) VALUES (?)");
            $insert->execute([$nisn]);

            $pesan = "<div class='p-4 mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium'>Siswa baru <b>{$nama}</b> berhasil disimpan ke database dan langsung diabsen!</div>";
        } catch (PDOException $e) {
            $pesan = "<div class='p-4 mb-4 bg-red-50 border border-red-200 text-red-800 rounded-xl text-sm font-medium'>Gagal menyimpan: NISN tersebut kemungkinan sudah terdaftar di database.</div>";
        }
    }
}

// Ambil data log absensi bergabung dengan tabel siswa
$logAbsen = $pdo->query("SELECT absensi.*, siswa.nama, siswa.kelas, siswa.jk FROM absensi JOIN siswa ON absensi.nisn = siswa.nisn ORDER BY absensi.waktu DESC")->fetchAll();
?>

<div class="space-y-6">
    <?= $pesan; ?>

    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
        <!-- Tab Navigasi Mode Absen / Tambah Siswa -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center pb-4 mb-5 border-b border-gray-100 gap-4">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Modul Absensi Kunjungan & Database Siswa</h3>
                <p class="text-sm text-gray-500">Scan NISN siswa terdaftar atau daftarkan siswa baru ke sistem.</p>
            </div>
            <div class="flex bg-gray-100 p-1 rounded-xl">
                <button type="button" onclick="switchMode('terdaftar')" id="btn-terdaftar" class="px-4 py-2 rounded-lg text-sm font-semibold transition <?= $tipe_form == 'terdaftar' ? 'bg-white text-emerald-800 shadow-sm' : 'text-gray-600'; ?>">Siswa Terdaftar</button>
                <button type="button" onclick="switchMode('baru')" id="btn-baru" class="px-4 py-2 rounded-lg text-sm font-semibold transition <?= $tipe_form == 'baru' ? 'bg-white text-emerald-800 shadow-sm' : 'text-gray-600'; ?>">Tambah Siswa Baru</button>
            </div>
        </div>

        <!-- Form 1: Siswa Terdaftar -->
        <form method="POST" id="form-terdaftar" class="<?= $tipe_form == 'terdaftar' ? '' : 'hidden'; ?> space-y-4">
            <input type="hidden" name="aksi_absen" value="1">
            <input type="hidden" name="tipe_form" value="terdaftar">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">MASUKKAN / SCAN NISN</label>
                <div class="flex gap-2">
                    <input type="text" name="nisn" placeholder="Contoh: 0012345678" required class="flex-1 px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white text-gray-800">
                    <button type="submit" class="bg-emerald-800 hover:bg-emerald-900 text-white font-semibold px-6 py-3 rounded-xl transition shadow">
                        <i class="fa-solid fa-check mr-2"></i> Kirim Absen
                    </button>
                </div>
            </div>
        </form>

        <!-- Form 2: Siswa Baru -->
        <form method="POST" id="form-baru" class="<?= $tipe_form == 'baru' ? '' : 'hidden'; ?> grid grid-cols-1 md:grid-cols-2 gap-4">
            <input type="hidden" name="tambah_siswa" value="1">
            <input type="hidden" name="tipe_form" value="baru">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">NISN (Primary Key)</label>
                <input type="text" name="nisn" placeholder="Nomor Induk Siswa Nasional" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white text-gray-800">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" name="nama" placeholder="Nama siswa" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white text-gray-800">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Jenis Kelamin</label>
                <select name="jk" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white text-gray-800">
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Kelas</label>
                <select name="kelas" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white text-gray-800">
                    <option value="">Pilih Kelas</option>
                    <option value="1">Kelas 1</option>
                    <option value="2">Kelas 2</option>
                    <option value="3">Kelas 3</option>
                    <option value="4">Kelas 4</option>
                    <option value="5">Kelas 5</option>
                    <option value="6">Kelas 6</option>
                </select>
            </div>
            <div class="md:col-span-2 flex justify-end pt-2">
                <button type="submit" class="bg-emerald-800 hover:bg-emerald-900 text-white font-semibold px-6 py-2.5 rounded-xl transition shadow">
                    <i class="fa-solid fa-user-plus mr-2"></i> Simpan ke Database Siswa & Absen
                </button>
            </div>
        </form>
    </div>

    <!-- Tabel Log Kehadiran -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <div>
                <h3 class="text-base font-bold text-gray-800">Log Kehadiran Perpustakaan</h3>
                <p class="text-xs text-gray-500">Data terhubung langsung dengan relasi tabel siswa di MySQL.</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-600 uppercase">
                        <th class="p-4">Waktu</th>
                        <th class="p-4">NISN</th>
                        <th class="p-4">Nama Siswa</th>
                        <th class="p-4">Kelas</th>
                        <th class="p-4">Jenis Kelamin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    <?php if (count($logAbsen) > 0): ?>
                        <?php foreach ($logAbsen as $log): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-4 font-mono text-xs text-gray-500"><?= $log['waktu']; ?></td>
                            <td class="p-4 font-semibold text-emerald-800"><?= $log['nisn']; ?></td>
                            <td class="p-4 font-medium text-gray-800"><?= $log['nama']; ?></td>
                            <td class="p-4"><span class="bg-blue-50 text-blue-700 px-2.5 py-1 rounded-lg text-xs font-semibold">Kelas <?= $log['kelas']; ?></span></td>
                            <td class="p-4 text-gray-600"><?= $log['jk'] == 'L' ? 'Laki-laki' : 'Perempuan'; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="p-8 text-center text-gray-400">Belum ada data kehadiran tercatat di database.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function switchMode(mode) {
    const formTerdaftar = document.getElementById('form-terdaftar');
    const formBaru = document.getElementById('form-baru');
    const btnTerdaftar = document.getElementById('btn-terdaftar');
    const btnBaru = document.getElementById('btn-baru');

    if (mode === 'terdaftar') {
        formTerdaftar.classList.remove('hidden');
        formBaru.classList.add('hidden');
        btnTerdaftar.className = 'px-4 py-2 rounded-lg text-sm font-semibold transition bg-white text-emerald-800 shadow-sm';
        btnBaru.className = 'px-4 py-2 rounded-lg text-sm font-semibold transition text-gray-600';
    } else {
        formTerdaftar.classList.add('hidden');
        formBaru.classList.remove('hidden');
        btnBaru.className = 'px-4 py-2 rounded-lg text-sm font-semibold transition bg-white text-emerald-800 shadow-sm';
        btnTerdaftar.className = 'px-4 py-2 rounded-lg text-sm font-semibold transition text-gray-600';
    }
}
</script>

<?php include 'includes/footer.php'; ?>