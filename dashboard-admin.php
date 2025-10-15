<?php
require_once __DIR__ . '/config.php';
require_auth();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Batatua 1928 - Kelola Profil</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-amber-50">
    <div class="flex min-h-screen">
        <div class="w-80  p-8 flex flex-col"style="background-color: #A18686;">
            <div class="text-center mb-16">
                <div class="text-4xl font-bold tracking-widest">BATA</div>
                <div class="text-lg">- 1928 -</div>
                <div class="text-4xl font-bold tracking-widest mt-1">TUA</div>
            </div>
            <nav class="flex-1 space-y-6">
                <a href="dashboard-utama.php" class="flex items-center space-x-4 text-black hover:text-white transition-colors"><span class="text-2xl font-bold">Dashboard</span></a>
                <a href="dashboard-menu.php" class="flex items-center space-x-4 text-black hover:text-white transition-colors"><span class="text-2xl font-bold">Kelola Menu</span></a>
                <a href="dashboard-galeri.php" class="flex items-center space-x-4 text-black hover:text-white transition-colors"><span class="text-2xl font-bold">Kelola Galeri</span></a>
                <a href="dashboard-admin.php" class="flex items-center space-x-4 text-black hover:text-white transition-colors"><span class="text-2xl font-bold">Kelola Profil</span></a>
            </nav>
            <div class="mt-auto flex items-center justify-between gap-4">
                <a href="Homepage.php" class="flex items-center space-x-2 text-black hover:text-white transition-colors"><span class="text-2xl font-bold">Kembali</span></a>
                <a href="logout.php" class="flex items-center space-x-2 text-black hover:text-white transition-colors"><span class="text-2xl font-bold">Logout</span></a>
            </div>
        </div>

        <div class="flex-1 p-8">
            <div class="bg-amber-100 p-6 rounded-lg mb-8 flex items-center justify-between">
                <h1 class="text-4xl font-bold">BATATUA 1928 - KELOLA PROFIL</h1>
                <div class="flex items-center space-x-4">
                    <h1 class="text-1xl">Edit</h1>
                    <h1>Hapus</h1>
                </div>
            </div>

            <div class="bg-amber-200 rounded-3xl p-12 mb-8 relative">
                <div class="absolute top-8 right-12">
                    <div class="bg-blue-100 border-4 border-blue-800 rounded-2xl px-6 py-3">
                        <div class="text-center">
                            <div class="text-blue-800 font-bold text-sm">KEDAI</div>
                            <div class="text-red-600 font-bold text-3xl">BATATUA</div>
                            <div class="text-blue-800 font-bold text-xl">1928</div>
                        </div>
                    </div>
                </div>
                <div class="space-y-4 text-xl">
                    <div class="flex"><span class="font-bold w-64">Nama Cafe :</span><span>Batatua 1928</span></div>
                    <div class="flex"><span class="font-bold w-64">Alamat :</span><span>Jl. Ketintang Madya No. 82, Surabaya</span></div>
                    <div class="flex"><span class="font-bold w-64">Email :</span><span>batatua1928@gmail.com</span></div>
                    <div class="flex"><span class="font-bold w-64">Jam Operasional :</span><span>08.00 - 00.00 WIB</span></div>
                    <div class="flex">
                        <span class="font-bold w-64">Tentang Kami :</span>
                        <div class="flex-1">
                            <p>Kedai Batatua 1928, tempat yang nyaman dengan</p>
                            <p>menu lengkap dan harga terjangkau.</p>
                            <p>Nikmati suasana hangat untuk makan, santai,</p>
                            <p>dan berkumpul bersama teman atau keluarga.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <button class="w-full bg-amber-200 hover:bg-amber-300 text-black font-bold text-2xl py-6 rounded-2xl transition-colors">Simpan Perubahan</button>
                <button class="w-full bg-amber-200 hover:bg-amber-300 text-black font-bold text-2xl py-6 rounded-2xl transition-colors">Batal</button>
            </div>
        </div>
    </div>
</body>
</html>


