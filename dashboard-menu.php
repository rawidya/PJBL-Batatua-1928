<?php
require_once __DIR__ . '/config.php';
require_auth();

$conn = get_db_connection();

// Handle create/update/delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'create' || $action === 'update') {
        $name = trim($_POST['name'] ?? '');
        $price = (int)($_POST['price'] ?? 0);
        $categoryId = $_POST['category_id'] !== '' ? (int)$_POST['category_id'] : null;

        $imagePath = null;
        if (!empty($_FILES['image']['name'])) {
            $uploadDir = __DIR__ . '/uploads';
            if (!is_dir($uploadDir)) { mkdir($uploadDir, 0777, true); }
            $filename = time() . '-' . preg_replace('/[^a-zA-Z0-9_.-]/', '_', $_FILES['image']['name']);
            $dest = $uploadDir . '/' . $filename;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                $imagePath = 'uploads/' . $filename;
            }
        }

        if ($action === 'create') {
            $stmt = $conn->prepare('INSERT INTO menu_items (name, price, category_id, image_path) VALUES (?,?,?,?)');
            $stmt->bind_param('siis', $name, $price, $categoryId, $imagePath);
            $stmt->execute();
            $stmt->close();
        } else {
            $id = (int)$_POST['id'];
            if ($imagePath) {
                $stmt = $conn->prepare('UPDATE menu_items SET name=?, price=?, category_id=?, image_path=? WHERE id=?');
                $stmt->bind_param('siisi', $name, $price, $categoryId, $imagePath, $id);
            } else {
                $stmt = $conn->prepare('UPDATE menu_items SET name=?, price=?, category_id=? WHERE id=?');
                $stmt->bind_param('siii', $name, $price, $categoryId, $id);
            }
            $stmt->execute();
            $stmt->close();
        }
    } elseif ($action === 'delete') {
        $id = (int)$_POST['id'];
        $stmt = $conn->prepare('DELETE FROM menu_items WHERE id=?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();
    }
    header('Location: dashboard-menu.php');
    exit;
}

// Fetch data
$categories = [];
$res = $conn->query('SELECT id, name FROM categories ORDER BY name');
while ($row = $res->fetch_assoc()) { $categories[] = $row; }
$res->close();

$items = [];
$res = $conn->query('SELECT m.id, m.name, m.price, m.image_path, c.name AS category_name, m.category_id FROM menu_items m LEFT JOIN categories c ON m.category_id = c.id ORDER BY m.id DESC');
while ($row = $res->fetch_assoc()) { $items[] = $row; }
$res->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Menu - Batatua 1928</title>
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
                <h1 class="text-4xl font-bold">KELOLA MENU</h1>
                <button onclick="document.getElementById('createModal').classList.remove('hidden')" class="bg-gray-800 text-white px-4 py-2 rounded">Tambah Item</button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($items as $it): ?>
                <div class="bg-white rounded-xl p-4 shadow flex flex-col">
                    <?php if (!empty($it['image_path'])): ?>
                        <img src="<?= htmlspecialchars($it['image_path']) ?>" alt="<?= htmlspecialchars($it['name']) ?>" class="w-full h-40 object-cover rounded" />
                    <?php endif; ?>
                    <div class="mt-3 text-xl font-semibold"><?= htmlspecialchars($it['name']) ?></div>
                    <div class="text-gray-600">Kategori: <?= htmlspecialchars($it['category_name'] ?? '-') ?></div>
                    <div class="font-bold">Rp <?= number_format((int)$it['price'], 0, ',', '.') ?></div>
                    <div class="mt-4 flex gap-2">
                        <button onclick='openEdit(<?= json_encode($it, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP) ?>)' class="px-3 py-2 bg-amber-200 rounded">Edit</button>
                        <form method="post" onsubmit="return confirm('Hapus item ini?')">
                            <input type="hidden" name="action" value="delete" />
                            <input type="hidden" name="id" value="<?= (int)$it['id'] ?>" />
                            <button type="submit" class="px-3 py-2 bg-red-200 rounded">Hapus</button>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div id="createModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg p-6 w-full max-w-lg">
            <div class="text-xl font-semibold mb-4">Tambah Menu</div>
            <form method="post" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="action" value="create" />
                <div>
                    <label class="block mb-1">Nama</label>
                    <input name="name" class="w-full border rounded px-3 py-2" required />
                </div>
                <div>
                    <label class="block mb-1">Harga (Rp)</label>
                    <input type="number" name="price" class="w-full border rounded px-3 py-2" min="0" required />
                </div>
                <div>
                    <label class="block mb-1">Kategori</label>
                    <select name="category_id" class="w-full border rounded px-3 py-2">
                        <option value="">-</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= (int)$cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block mb-1">Gambar</label>
                    <input type="file" name="image" accept="image/*" />
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('createModal').classList.add('hidden')" class="px-4 py-2">Batal</button>
                    <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg p-6 w-full max-w-lg">
            <div class="text-xl font-semibold mb-4">Ubah Menu</div>
            <form method="post" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="action" value="update" />
                <input type="hidden" name="id" id="edit_id" />
                <div>
                    <label class="block mb-1">Nama</label>
                    <input name="name" id="edit_name" class="w-full border rounded px-3 py-2" required />
                </div>
                <div>
                    <label class="block mb-1">Harga (Rp)</label>
                    <input type="number" name="price" id="edit_price" class="w-full border rounded px-3 py-2" min="0" required />
                </div>
                <div>
                    <label class="block mb-1">Kategori</label>
                    <select name="category_id" id="edit_category_id" class="w-full border rounded px-3 py-2">
                        <option value="">-</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= (int)$cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block mb-1">Gambar (opsional)</label>
                    <input type="file" name="image" accept="image/*" />
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="px-4 py-2">Batal</button>
                    <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function openEdit(item) {
        document.getElementById('edit_id').value = item.id;
        document.getElementById('edit_name').value = item.name;
        document.getElementById('edit_price').value = item.price;
        document.getElementById('edit_category_id').value = item.category_id ?? '';
        document.getElementById('editModal').classList.remove('hidden');
    }
    </script>
</body>
</html>


