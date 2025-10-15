<?php
require_once __DIR__ . '/config.php';
start_session_if_needed();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username !== '' && $password !== '') {
        $conn = get_db_connection();
        $stmt = $conn->prepare('SELECT id, password_hash FROM users WHERE username = ?');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->bind_result($userId, $passwordHash);
        if ($stmt->fetch() && password_verify($password, $passwordHash)) {
            $_SESSION['user_id'] = $userId;
            $_SESSION['username'] = $username;
            header('Location: dashboard-admin.php');
            exit;
        } else {
            $error = 'Username atau password salah';
        }
        $stmt->close();
        $conn->close();
    } else {
        $error = 'Harap isi semua field';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kedai Batatua - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="min-h-screen relative overflow-hidden">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('Assets/Hero Image (1).png');">
            <div class="absolute inset-0 bg-white/40 backdrop-blur-sm"></div>
        </div>

        <div class="relative z-10 flex items-center justify-center min-h-screen px-4">
            <div class="w-full max-w-md">
                <div class="text-center mb-8">
                    <h1 class="text-5xl font-bold text-gray-900 mb-2">LOGIN</h1>
                </div>

                <?php if ($error): ?>
                <div class="mb-4 p-3 bg-red-100 text-red-700 rounded"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>

                <form method="post" class="space-y-6">
                    <div>
                        <label for="username" class="block text-lg font-semibold text-gray-900 mb-2">Username</label>
                        <input type="text" id="username" name="username" class="w-full px-4 py-3 rounded-lg border-2 border-gray-300 bg-white focus:outline-none focus:border-gray-400 text-gray-900" />
                    </div>
                    <div>
                        <label for="password" class="block text-lg font-semibold text-gray-900 mb-2">Password</label>
                        <input type="password" id="password" name="password" class="w-full px-4 py-3 rounded-lg border-2 border-gray-300 bg-white focus:outline-none focus:border-gray-400 text-gray-900" />
                    </div>
                    <button type="submit" class="w-full bg-gray-800 hover:bg-gray-900 text-white font-semibold py-3 rounded-lg transition-colors duration-200 text-lg">LOGIN</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>


