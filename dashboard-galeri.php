<?php
require_once __DIR__ . '/config.php';
require_auth();

$conn = get_db_connection();

// Ensure gallery table exists (id, image_path, caption, created_at)
$conn->query("CREATE TABLE IF NOT EXISTS gallery (
  id INT AUTO_INCREMENT PRIMARY KEY,
  image_path VARCHAR(255) NOT NULL,
  caption VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB");

// Handle create/delete actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'create') {
        $caption = trim($_POST['caption'] ?? '');
        $imagePath = null;
        if (!empty($_FILES['image']['name'])) {
            $uploadDir = __DIR__ . '/uploads/gallery';
            if (!is_dir($uploadDir)) { mkdir($uploadDir, 0777, true); }
            $filename = time() . '-' . preg_replace('/[^a-zA-Z0-9_.-]/', '_', $_FILES['image']['name']);
            $dest = $uploadDir . '/' . $filename;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                $imagePath = 'uploads/gallery/' . $filename;
            }
        }
        if ($imagePath) {
            $stmt = $conn->prepare('INSERT INTO gallery (image_path, caption) VALUES (?, ?)');
            $stmt->bind_param('ss', $imagePath, $caption);
            $stmt->execute();
            $stmt->close();
        }
        header('Location: dashboard-galeri.php');
        exit;
    } elseif ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            // fetch file to optionally unlink
            $res = $conn->prepare('SELECT image_path FROM gallery WHERE id=?');
            $res->bind_param('i', $id);
            $res->execute();
            $res->bind_result($path);
            $filePath = null;
            if ($res->fetch()) { $filePath = $path; }
            $res->close();

            $stmt = $conn->prepare('DELETE FROM gallery WHERE id=?');
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->close();

            if ($filePath) {
                $abs = __DIR__ . '/' . $filePath;
                if (is_file($abs)) { @unlink($abs); }
            }
        }
        header('Location: dashboard-galeri.php');
        exit;
    }
}

// Fetch images
$images = [];
$res = $conn->query('SELECT id, image_path, caption, created_at FROM gallery ORDER BY id DESC');
while ($row = $res->fetch_assoc()) { $images[] = $row; }
$res->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Batatua 1928 - Kelola Galeri</title>
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
                <h1 class="text-4xl font-bold">KELOLA GALERI</h1>
                <button onclick="document.getElementById('createModal').classList.remove('hidden')" class="bg-gray-800 text-white px-4 py-2 rounded">Tambah Foto</button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php if (empty($images)): ?>
                    <div class="bg-white rounded-xl p-4 shadow">Belum ada foto.</div>
                <?php else: ?>
                    <?php foreach ($images as $img): ?>
                    <div class="bg-white rounded-xl p-4 shadow flex flex-col">
                        <img src="<?= htmlspecialchars($img['image_path']) ?>" alt="<?= htmlspecialchars($img['caption'] ?? '') ?>" class="w-full h-48 object-cover rounded" />
                        <?php if (!empty($img['caption'])): ?>
                        <div class="mt-2 text-gray-800 font-medium"><?= htmlspecialchars($img['caption']) ?></div>
                        <?php endif; ?>
                        <form method="post" class="mt-3" onsubmit="return confirm('Hapus foto ini?')">
                            <input type="hidden" name="action" value="delete" />
                            <input type="hidden" name="id" value="<?= (int)$img['id'] ?>" />
                            <button type="submit" class="px-3 py-2 bg-red-200 rounded">Hapus</button>
                        </form>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- Create Modal -->
    <div id="createModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg p-6 w-full max-w-lg">
            <div class="text-xl font-semibold mb-4">Tambah Foto Galeri</div>
            <form method="post" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="action" value="create" />
                <div>
                    <label class="block mb-1">Gambar</label>
                    <input type="file" name="image" accept="image/*" required />
                </div>
                <div>
                    <label class="block mb-1">Caption (opsional)</label>
                    <input type="text" name="caption" class="w-full border rounded px-3 py-2" />
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('createModal').classList.add('hidden')" class="px-4 py-2">Batal</button>
                    <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

