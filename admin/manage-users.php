<?php
require_once '../config/functions.php';
initSessionConfig();
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

$adminUser = $_SESSION['user'];

// Handle Delete
if (isset($_GET['delete'])) {
    $userId = $_GET['delete'];
    // Prevent admin from deleting their own account
    if ($userId != $adminUser['id']) {
        execute("DELETE FROM users WHERE id = ?", [$userId]);
    }
    header('Location: manage-users.php');
    exit;
}

// Handle Role Change
if (isset($_GET['change_role'])) {
    $userId = $_GET['change_role'];
    $newRole = $_GET['role'];
    // Prevent admin from changing their own role
    if ($userId != $adminUser['id']) {
        execute("UPDATE users SET role = ? WHERE id = ?", [$newRole, $userId]);
    }
    header('Location: manage-users.php');
    exit;
}

$users = select("SELECT * FROM users ORDER BY created_at DESC");

$pageTitle = "Manage Users";
?>

<?php include 'header.php'; ?>

            <div class="max-w-7xl mx-auto">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md">
        <div class="h-20 flex items-center justify-center">
            <h1 class="text-2xl font-bold text-blue-600">Okarahotel</h1>
        </div>
        <nav class="mt-5">
            <a href="dashboard.php" class="flex items-center mt-4 py-2 px-6 text-gray-600 hover:bg-gray-200">
                <i class="fas fa-th-large mr-3"></i> Dashboard
            </a>
            <a href="manage-bookings.php" class="flex items-center mt-4 py-2 px-6 text-gray-600 hover:bg-gray-200">
                <i class="fas fa-calendar-alt mr-3"></i> Bookings
            </a>
            <a href="manage-rooms.php" class="flex items-center mt-4 py-2 px-6 text-gray-600 hover:bg-gray-200">
                <i class="fas fa-bed mr-3"></i> Rooms
            </a>
            <a href="manage-food.php" class="flex items-center mt-4 py-2 px-6 text-gray-600 hover:bg-gray-200">
                <i class="fas fa-utensils mr-3"></i> Food Menu
            </a>
            <a href="manage-food-orders.php" class="flex items-center mt-4 py-2 px-6 text-gray-600 hover:bg-gray-200">
                <i class="fas fa-concierge-bell mr-3"></i> Food Orders
            </a>
            <a href="manage-users.php" class="flex items-center mt-4 py-2 px-6 bg-gray-200 text-gray-700">
                <i class="fas fa-users mr-3"></i> Users
            </a>
            <a href="site-setting.php" class="flex items-center mt-4 py-2 px-6 text-gray-600 hover:bg-gray-200">
                <i class="fas fa-cog mr-3"></i> Settings
            </a>
            <a href="../logout.php" class="flex items-center mt-4 py-2 px-6 text-gray-600 hover:bg-gray-200">
                <i class="fas fa-sign-out-alt mr-3"></i> Logout
            </a>
        </nav>
    </aside>

    <!-- Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="flex justify-between items-center p-6 bg-white border-b-2 border-gray-200">
            <h2 class="text-2xl text-gray-700 font-semibold">Manage Users</h2>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl text-gray-700 font-semibold mb-4">All Users</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Full Name</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Email</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Phone</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Role</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            <?php if (!empty($users)): ?>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td class="text-left py-3 px-4"><?php echo htmlspecialchars($user['full_name']); ?></td>
                                        <td class="text-left py-3 px-4"><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td class="text-left py-3 px-4"><?php echo htmlspecialchars($user['phone']); ?></td>
                                        <td class="text-left py-3 px-4">
                                            <span class="px-2 py-1 font-semibold leading-tight <?php echo $user['role'] === 'admin' ? 'text-purple-700 bg-purple-100' : 'text-gray-700 bg-gray-100'; ?> rounded-sm">
                                                <?php echo ucfirst($user['role']); ?>
                                            </span>
                                        </td>
                                        <td class="text-left py-3 px-4">
                                            <?php if ($user['id'] != $adminUser['id']): ?>
                                                <?php if ($user['role'] === 'user'): ?>
                                                    <a href="manage-users.php?change_role=<?php echo $user['id']; ?>&role=admin" class="text-green-500 hover:text-green-700 mr-2" title="Make Admin"><i class="fas fa-user-shield"></i></a>
                                                <?php else: ?>
                                                    <a href="manage-users.php?change_role=<?php echo $user['id']; ?>&role=user" class="text-yellow-500 hover:text-yellow-700 mr-2" title="Make User"><i class="fas fa-user"></i></a>
                                                <?php endif; ?>
                                                <a href="manage-users.php?delete=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure you want to delete this user?');" class="text-red-500 hover:text-red-700" title="Delete User"><i class="fas fa-trash"></i></a>
                                            <?php else: ?>
                                                <span class="text-gray-400">N/A</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4">No users found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include 'footer.php'; ?>
