<?php
require_once __DIR__ . '/config.php';
require_auth();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Batatua 1928</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-amber-50">
    <div class="flex min-h-screen">
        <div class="w-80  p-8 flex flex-col" style="background-color: #A18686;">
            <div class="text-center mb-16">
                <div class="text-4xl font-bold tracking-widest">BATA</div>
                <div class="text-lg">- 1928 -</div>
                <div class="text-4xl font-bold tracking-widest mt-1">TUA</div>
            </div>
            <nav class="flex-1 space-y-6">
                <a href="dashboard-utama.php" class="flex items-center space-x-4 text-black hover:text-white transition-colors">
                    <span class="text-2xl font-bold">Dashboard</span>
                </a>
                <a href="dashboard-menu.php" class="flex items-center space-x-4 text-black hover:text-white transition-colors">
                    <span class="text-2xl font-bold">Kelola Menu</span>
                </a>
                <a href="dashboard-galeri.php" class="flex items-center space-x-4 text-black hover:text-white transition-colors">
                    <span class="text-2xl font-bold">Kelola Galeri</span>
                </a>
                <a href="dashboard-admin.php" class="flex items-center space-x-4 text-black hover:text-white transition-colors">
                    <span class="text-2xl font-bold">Kelola Profil</span>
                </a>
            </nav>
            <div class="mt-auto flex items-center justify-between gap-4">
                <a href="Homepage.php" class="flex items-center space-x-2 text-black hover:text-white transition-colors">
                    <span class="text-2xl font-bold">Kembali</span>
                </a>
                <a href="logout.php" class="flex items-center space-x-2 text-black hover:text-white transition-colors">
                    <span class="text-2xl font-bold">Logout</span>
                </a>
            </div>
        </div>
        <div class="flex-1 p-8">
            <div class="bg-amber-100 p-6 rounded-lg mb-8 flex items-center justify-between">
                <h1 class="text-4xl font-bold">BATATUA 1928 - DASHBOARD</h1>
                <div>Selamat datang, <?php echo htmlspecialchars($_SESSION['username'] ?? '', ENT_QUOTES, 'UTF-8'); ?></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="dashboard-menu.php" class="bg-white rounded-xl p-6 shadow">
                    <div class="text-xl font-semibold">Kelola Menu</div>
                    <div class="text-gray-600">Tambah, ubah, hapus item menu</div>
                </a>
                <a href="dashboard-admin.php" class="bg-white rounded-xl p-6 shadow">
                    <div class="text-xl font-semibold">Profil</div>
                    <div class="text-gray-600">Informasi kedai</div>
                </a>
                <a href="dashboard-galeri.php" class="bg-white rounded-xl p-6 shadow">
                    <div class="text-xl font-semibold">Galeri</div>
                    <div class="text-gray-600">Kelola foto</div>
                </a>
            </div>
        </div>
    </div>
</body>
</html>


