<?php require_once __DIR__ . '/../config/database.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Perpustakaan SD Tambaksawa</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased">
    <div class="min-h-screen flex flex-col md:flex-row">
        <!-- Sidebar -->
        <aside class="w-full md:w-64 bg-emerald-900 text-white flex flex-col shadow-xl z-20">
            <div class="p-5 border-b border-emerald-800 flex items-center space-x-3">
                <div class="bg-white p-2.5 rounded-xl text-emerald-900 font-bold text-xl">
                    <i class="fa-solid fa-book-bookmark"></i>
                </div>
                <div>
                    <h1 class="font-bold text-lg leading-tight">SD TAMBAKSAWA</h1>
                    <p class="text-xs text-emerald-300">Perpustakaan Digital</p>
                </div>
            </div>
            <nav class="flex-1 p-4 space-y-1.5">
                <a href="index.php" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition hover:bg-emerald-800"><i class="fa-solid fa-chart-pie w-5"></i><span>Dashboard</span></a>
                <a href="absensi.php" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition hover:bg-emerald-800"><i class="fa-solid fa-user-check w-5"></i><span>Absensi Murid</span></a>
                <a href="buku.php" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition hover:bg-emerald-800"><i class="fa-solid fa-book w-5"></i><span>Pendataan Buku</span></a>
                <a href="peminjaman.php" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition hover:bg-emerald-800"><i class="fa-solid fa-hand-holding-hand w-5"></i><span>Peminjaman Buku</span></a>
                <a href="laporan_cetak.php" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition hover:bg-emerald-800"><i class="fa-solid fa-file-lines w-5"></i><span>Laporan Bulanan</span></a>
            </nav>
        </aside>
        <main class="flex-1 flex flex-col min-w-0 overflow-y-auto">
            <header class="bg-white border-b border-gray-200 py-4 px-6 flex justify-between items-center shadow-sm">
                <h2 class="text-xl font-bold text-gray-800">Panel Administrasi Perpustakaan</h2>
                <span class="text-xs bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full font-semibold">MySQL Connected</span>
            </header>
            <div class="p-6 flex-1">