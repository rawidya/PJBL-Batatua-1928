<?php
require_once __DIR__ . '/config.php';
$conn = get_db_connection();

// Fetch categories and items
$categories = [];
$res = $conn->query('SELECT id, name FROM categories ORDER BY name');
while ($row = $res->fetch_assoc()) { $categories[] = $row; }
$res->close();

$items = [];
$res = $conn->query('SELECT id, name, price, image_path, category_id FROM menu_items ORDER BY id DESC');
while ($row = $res->fetch_assoc()) { $items[] = $row; }
$res->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Batatua1928 - Menu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-amber-50 scroll-smooth">
    <header>
        <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 py-4 transition-all duration-300">
            <div class="container mx-auto px-6 flex items-center justify-between">
                <div class="flex items-center -ml-2">
                    <span class="text-2xl font-bold text-black font-['Playfair_Display']">
                        <a href="dashboard-utama.php"> 
                            BATATUA<span class="text-black-200">1928</span>
                        </a>
                    </span>
                </div>
                <ul class="hidden md:flex items-center space-x-10 text-sm font-semibold tracking-wide">
                    <li><a href="Homepage.php" class="text-black transition-colors duration-300 relative after:content-[''] after:absolute after:w-0 after:h-0.5 after:bg-yellow-300 after:left-0 after:-bottom-1 after:transition-all after:duration-300 hover:after:w-full">HOME</a></li>
                    <li><a href="Homepage.php" class="text-black transition-colors duration-300 relative after:content-[''] after:absolute after:w-0 after:h-0.5 after:bg-yellow-300 after:left-0 after:-bottom-1 after:transition-all after:duration-300 hover:after:w-full">ABOUT</a></li>
                    <li><a href="menu.php" class="text-black transition-colors duration-300 relative after:content-[''] after:absolute after:w-0 after:h-0.5 after:bg-yellow-300 after:left-0 after:-bottom-1 after:transition-all after:duration-300 hover:after:w-full">MENU</a></li>
                    <li><a href="contact.php" class="text-black transition-colors duration-300 relative after:content-[''] after:absolute after:w-0 after:h-0.5 after:bg-yellow-300 after:left-0 after:-bottom-1 after:transition-all after:duration-300 hover:after:w-full">CONTACT</a></li>
                    <li><a href="#location" class="text-black transition-colors duration-300 relative after:content-[''] after:absolute after:w-0 after:h-0.5 after:bg-yellow-300 after:left-0 after:-bottom-1 after:transition-all after:duration-300 hover:after:w-full">LOCATION</a></li>
                    <li><a href="Login-for-admin.php" class="text-black transition-colors duration-300">LOGIN</a></li>
                </ul>
                <button id="mobile-menu-btn" class="md:hidden focus:outline-none">
                    <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
            <div id="mobile-menu" class="hidden md:hidden bg-white/95 bakackdrop-blur-sm border-t border-gray-100">
                <ul class="flex flex-col px-6 py-4 text-sm font-semibold tracking-wide">
                    <li><a href="Homepage.php" class="block py-3 text-gray-700 hover:text-red-700 transition-colors duration-300">HOME</a></li>
                    <li><a href="Homepage.php" class="block py-3 text-gray-700 hover:text-red-700 transition-colors duration-300">ABOUT</a></li>
                    <li><a href="menu.php" class="block py-3 text-gray-700 hover:text-red-700 transition-colors duration-300">MENU</a></li>
                    <li><a href="contact.php" class="block py-3 text-gray-700 hover:text-red-700 transition-colors duration-300">CONTACT</a></li>
                    <li><a href="#location" class="block py-3 text-gray-700 hover:text-red-700 transition-colors duration-300">LOCATION</a></li>
                    <li><a href="Login-for-admin.php" class="block py-3 text-gray-700 hover:text-red-700 transition-colors duration-300">LOGIN</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <section id="home" class="relative h-screen flex items-center justify-center bg-cover bg-center" style="background-image: url('Assets/Hero Image (2).png');">
        <div class="absolute inset-0 bg-gradient-to-b from-black/40 to-black/40"></div>
        <div class="relative z-10 text-center text-white px-4">
            <h1 class="text-4xl text-black md:text-6xl font-bold mb-4 text-center">Batatua1928</h1>
        </div>
    </section>

    <div class="bg-[#fff8f0] min-h-screen py-10 px-5">
        <h2 class="text-3xl font-bold text-center mb-8">Product Kami</h2>
        <div class="flex justify-center gap-4 mb-8 flex-wrap">
            <button class="category-btn text-black transition-colors duration-300 relative after:content-[''] after:absolute after:w-0 after:h-0.5 after:bg-yellow-300 after:left-0 after:-bottom-1 after:transition-all after:duration-300 hover:after:w-full px-4 py-2" data-category="all">All</button>
            <?php foreach ($categories as $cat): ?>
                <button class="category-btn text-black transition-colors duration-300 relative after:content-[''] after:absolute after:w-0 after:h-0.5 after:bg-yellow-300 after:left-0 after:-bottom-1 after:transition-all after:duration-300 hover:after:w-full px-4 py-2" data-category="<?= htmlspecialchars($cat['name']) ?>"><?= htmlspecialchars($cat['name']) ?></button>
            <?php endforeach; ?>
        </div>

        <div id="productGrid" class="grid grid-cols-1 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-4 gap-8">
            <?php foreach ($items as $it): ?>
                <div class="product-card bg-[#E2AE54] rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition" data-category="<?= htmlspecialchars($it['category_id'] ? $categories[array_search($it['category_id'], array_column($categories, 'id'))]['name'] ?? '' : '') ?>">
                    <?php if (!empty($it['image_path'])): ?>
                        <img src="<?= htmlspecialchars($it['image_path']) ?>" alt="<?= htmlspecialchars($it['name']) ?>" class="w-full h-48 object-cover">
                    <?php endif; ?>
                    <div class="p-4">
                        <h3 class="text-xl font-semibold text-gray-800"><?= htmlspecialchars($it['name']) ?></h3>
                        <p class="text-black font-bold mt-2">Rp <?= number_format((int)$it['price'], 0, ',', '.') ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="flex justify-center mt-12 mb-8">
            <button onclick="history.back()" class="bg-gray-800 text-white px-10 py-3 rounded-lg text-lg font-semibold hover:bg-gray-700 transition duration-300 shadow-md">BACK</button>
        </div>
    </div>

    <footer id="location" class="border-t-2 border-black text-black py-12">
        <div class="container mx-auto px-4">
            <div class="flex justify-center grid-cols-1 md:grid-cols-3 gap-8 text-center md:text-left place-items-center md:place-items-start">
                <div>
                    <h3 class="text-2xl font-bold mb-4">Lokasi</h3>
                    <p class="mb-2"><i class="fas fa-map-marker-alt mr-2"></i>Jl. Ketintang Madya</p>
                    <p class="mb-2">No. 82, Surabaya.</p>
                </div>
                <div>
                    <h3 class="text-2xl font-bold mb-4">Jam Operasional</h3>
                    <p class="mb-2"><i class="fas fa-clock mr-2"></i>Setiap Hari</p>
                    <p class="mb-2">09.00 - 00.00 WIB</p>
                    <p class="mt-4 italic">"Semangat Muda Menolak Tua"</p>
                </div>
                <div>
                    <h3 class="text-2xl font-bold mb-4">Contact us</h3>
                    <div class="flex justify-center md:justify-start space-x-4">
                        <a href="https://www.instagram.com/kedaibatatua.id/" class="w-12 h-12 bg-amber-700 rounded-full flex items-center justify-center hover:bg-amber-600 transition duration-300"><i class="fab fa-instagram text-white text-lg"></i></a>
                        <a href="#" class="w-12 h-12 bg-amber-700 rounded-full flex items-center justify-center hover:bg-amber-600 transition duration-300"><i class="fas fa-envelope text-white text-lg"></i></a>
                        <a href="#" class="w-12 h-12 bg-amber-700 rounded-full flex items-center justify-center hover:bg-amber-600 transition duration-300"><i class="fab fa-tiktok text-white text-lg"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script>
    window.addEventListener('scroll', function() {
        const navbar = document.getElementById('navbar');
        if (window.scrollY > 50) {
            navbar.classList.add('bg-white/95', 'backdrop-blur-sm', 'shadow-sm');
            navbar.classList.remove('bg-transparent');
        } else {
            navbar.classList.remove('bg-white/95', 'backdrop-blur-sm', 'shadow-sm');
            navbar.classList.add('bg-transparent');
        }
    });
    document.getElementById('mobile-menu-btn').addEventListener('click', function() {
        const mobileMenu = document.getElementById('mobile-menu');
        mobileMenu.classList.toggle('hidden');
    });
    document.querySelectorAll('#mobile-menu a').forEach(link => {
        link.addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.add('hidden');
        });
    });
    const categoryBtns = document.querySelectorAll('.category-btn');
    const productCards = document.querySelectorAll('.product-card');
    categoryBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const category = this.getAttribute('data-category');
            productCards.forEach(card => {
                if (category === 'all' || card.getAttribute('data-category') === category) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
    </script>
</body>
</html>


