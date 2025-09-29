<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

require_once __DIR__ . '/../contactconfig/database.php';
require_once __DIR__ . '/../contactlib/easySQL.php';

$path_prefix = '../';

$users = [];
$pendingAdmins = [];
$messages = [];
$apiKeys = [];
$usersWithoutKeys = [];
$approvedUsersCount = 0;

try {
    $db = new EasySQL(DB_SERVER, DB_USER, DB_PASS, DB_NAME, DB_PORT);

    $users = $db->db_Out('users', 'id, username, role, status, created_at', null, [], 'created_at DESC');
    $pendingAdmins = $db->db_Out('users', 'id, username, created_at', "role = 'admin' AND status = 'pending'", [], 'created_at DESC');

    $messages = $db->db_Out('contacts', '*', null, [], 'submission_date DESC');

    $apiKeys = $db->db_Out(
        'api_keys k JOIN users u ON k.user_id = u.id',
        'k.id, k.api_key, u.username, k.requests_count, k.is_active, k.created_at'
    );

    $usersWithoutKeys = $db->db_Out(
        'users u LEFT JOIN api_keys k ON u.id = k.user_id',
        'u.id, u.username',
        "k.id IS NULL AND u.status = 'approved'"
    );

    $countResult = $db->db_Out('users', 'COUNT(id) as count', "status = 'approved'");
    if ($countResult) {
        $approvedUsersCount = $countResult[0]['count'];
    }

    $db->closeConnection();
} catch (Exception $e) {
    error_log($e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= $path_prefix ?>src/styles/styles.css">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <?php include __DIR__ . '/../navbar/index.php'; ?>

    <div class="admin-container">
        <h1>Admin Dashboard</h1>
        <p>Welcome, <?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?>.</p>
        <a href="<?= $path_prefix ?>auth/logout.php" class="logout-button">Logout</a>
    </div>

    <section class="admin-section">
        <h2>API Key Management</h2>

        <form action="manage_keys.php" method="post" class="api-key-form">
            <input type="hidden" name="action" value="create">
            <select name="user_id" required>
                <option value="" disabled selected>-- Assign key to a user --</option>
                <?php if (empty($usersWithoutKeys)): ?>
                    <?php if ($approvedUsersCount > 0): ?>
                        <option disabled>All approved users have keys</option>
                    <?php else: ?>
                        <option disabled>No approved users to assign keys to</option>
                    <?php endif; ?>
                <?php else: ?>
                    <?php foreach ($usersWithoutKeys as $user): ?>
                        <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['username']) ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <button type="submit" class="form-button" <?= empty($usersWithoutKeys) ? 'disabled' : '' ?>>Generate New Key</button>
        </form>

        <div class="table-container">
            <table class="user-table">
                <thead>
                    <tr>
                        <th>Assigned User</th>
                        <th>API Key</th>
                        <th>Requests</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($apiKeys)): ?>
                        <tr>
                            <td colspan="5" style="text-align: center;">No API keys have been created yet.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($apiKeys as $key): ?>
                            <tr>
                                <td><?= htmlspecialchars($key['username']) ?></td>
                                <td><code class="api-key-code"><?= htmlspecialchars($key['api_key']) ?></code></td>
                                <td><?= $key['requests_count'] ?></td>
                                <td><?= date('M j, Y', strtotime($key['created_at'])) ?></td>
                                <td>
                                    <a href="manage_keys.php?action=delete&id=<?= $key['id'] ?>" class="btn-deny" onclick="return confirm('Are you sure you want to delete this key?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <section class="admin-section">
        <h2>Pending Admin Approvals</h2>
        <?php if (empty($pendingAdmins)): ?>
            <p class="no-items">No pending admin requests.</p>
        <?php else: ?>
            <div class="table-container">
                <table class="user-table">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Registered At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pendingAdmins as $pending): ?>
                            <tr>
                                <td><?= htmlspecialchars($pending['username']) ?></td>
                                <td><?= date('F j, Y', strtotime($pending['created_at'])) ?></td>
                                <td>
                                    <a href="manage_request.php?action=approve&id=<?= $pending['id'] ?>" class="btn-approve">Approve</a>
                                    <a href="manage_request.php?action=deny&id=<?= $pending['id'] ?>" class="btn-deny" onclick="return confirm('Are you sure you want to deny and delete this user?')">Deny</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>

    <section class="admin-section">
        <h2>User Management</h2>
        <div class="table-container">
            <table class="user-table">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Member Since</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['username']) ?></td>
                            <td><?= htmlspecialchars(ucfirst($user['role'])) ?></td>
                            <td><?= htmlspecialchars(ucfirst($user['status'])) ?></td>
                            <td><?= date('F j, Y', strtotime($user['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

    <section class="admin-section">
        <h2>Inbox</h2>
        <?php if (empty($messages)): ?>
            <p class="no-items">There are no new messages.</p>
        <?php else: ?>
            <?php foreach ($messages as $message): ?>
                <details class="message-card">
                    <summary class="message-header">
                        <div>
                            <span class="sender-info"><?= htmlspecialchars($message['name']) ?></span>
                        </div>
                        <div class="message-date">
                            <?= date('F j, Y, g:i a', strtotime($message['submission_date'])) ?>
                        </div>
                    </summary>
                    <div class="message-content">
                        <p><strong>From:</strong> <a href="mailto:<?= htmlspecialchars($message['email']) ?>"><?= htmlspecialchars($message['email']) ?></a></p>
                        <hr>
                        <p><?= nl2br(htmlspecialchars($message['message'])) ?></p>
                    </div>
                </details>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>

    <script src="<?= $path_prefix ?>src/nav.js"></script>
</body>

</html>