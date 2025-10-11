<?php
/**
 * Contacts Management - List
 * PT Komodo Industrial Indonesia
 */

define('APP_ACCESS', true);

require_once __DIR__ . '/../../../config/constants.php';
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../includes/functions.php';
require_once __DIR__ . '/../../../includes/auth.php';

requireLogin();

// Handle mark as read
if (isPost() && post('action') === 'mark_read') {
    $contactId = (int)post('contact_id');
    $db->query("UPDATE contacts SET is_read = 1 WHERE id = ?", [$contactId]);
    setFlash('success', 'Message marked as read');
    redirect(ADMIN_URL . '/modules/contacts/index.php');
}

// Get filters
$search = get('search', '');
$status = get('status', '');
$page = (int)get('page', 1);

// Build query
$where = [];
$params = [];

if (!empty($search)) {
    $where[] = "(full_name LIKE ? OR email LIKE ? OR message LIKE ?)";
    $searchTerm = "%{$search}%";
    $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm]);
}

if ($status !== '') {
    $where[] = "is_read = ?";
    $params[] = $status;
}

$whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

// Get total count
$countSql = "SELECT COUNT(*) as count FROM contacts {$whereClause}";
$totalItems = $db->fetchOne($countSql, $params)['count'];

// Get pagination
$pagination = getPaginationData($totalItems, $page, ITEMS_PER_PAGE);

// Get contacts
$sql = "SELECT * FROM contacts {$whereClause} ORDER BY created_at DESC LIMIT ? OFFSET ?";
$params[] = $pagination['items_per_page'];
$params[] = $pagination['offset'];
$contacts = $db->fetchAll($sql, $params);

$pageTitle = 'Messages';
include __DIR__ . '/../../includes/header.php';
?>

<div class="p-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Contact Messages</h1>
        <p class="text-gray-600">Manage customer inquiries and messages</p>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <form method="GET" action="" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>"
                       placeholder="Search messages..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">All Messages</option>
                    <option value="0" <?php echo $status === '0' ? 'selected' : ''; ?>>Unread</option>
                    <option value="1" <?php echo $status === '1' ? 'selected' : ''; ?>>Read</option>
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    <i class="fas fa-search mr-2"></i> Filter
                </button>
                <a href="index.php" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Messages List -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contact Info</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subject</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Message</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if (!empty($contacts)): ?>
                        <?php foreach ($contacts as $contact): ?>
                            <tr class="hover:bg-gray-50 <?php echo !$contact['is_read'] ? 'bg-blue-50' : ''; ?>">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?php echo htmlspecialchars($contact['full_name']); ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        <i class="fas fa-envelope mr-1 text-gray-400"></i>
                                        <?php echo htmlspecialchars($contact['email']); ?>
                                    </div>
                                    <?php if (!empty($contact['phone'])): ?>
                                        <div class="text-sm text-gray-600 mt-1">
                                            <i class="fas fa-phone mr-1 text-gray-400"></i>
                                            <?php echo htmlspecialchars($contact['phone']); ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-600">
                                        <?php echo ucfirst($contact['subject']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        <?php echo truncate($contact['message'], 80); ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <?php echo timeAgo($contact['created_at']); ?>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <?php if ($contact['is_read']): ?>
                                        <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-600">Read</span>
                                    <?php else: ?>
                                        <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-600">Unread</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <button onclick="viewMessage(<?php echo $contact['id']; ?>)" 
                                                class="text-blue-600 hover:text-blue-900" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <?php if (!$contact['is_read']): ?>
                                            <form method="POST" class="inline">
                                                <input type="hidden" name="action" value="mark_read">
                                                <input type="hidden" name="contact_id" value="<?php echo $contact['id']; ?>">
                                                <button type="submit" class="text-green-600 hover:text-green-900" title="Mark as Read">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                        <a href="delete.php?id=<?php echo $contact['id']; ?>" 
                                           class="text-red-600 hover:text-red-900"
                                           onclick="return confirmDelete('Are you sure you want to delete this message?')"
                                           title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-4 text-gray-300"></i>
                                <p class="text-lg font-medium">No messages found</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if ($pagination['total_pages'] > 1): ?>
            <div class="px-6 py-4 border-t border-gray-200">
                <?php echo renderPagination($pagination, 'index.php'); ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="mt-4 text-sm text-gray-600">
        Showing <?php echo $pagination['offset'] + 1; ?> to <?php echo min($pagination['offset'] + $pagination['items_per_page'], $totalItems); ?> of <?php echo $totalItems; ?> messages
    </div>
</div>

<!-- View Message Modal -->
<div id="messageModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-xl font-bold text-gray-900">Message Details</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div id="messageContent" class="p-6">
            <!-- Content loaded via JS -->
        </div>
    </div>
</div>

<script>
function viewMessage(id) {
    // Show modal
    document.getElementById('messageModal').classList.remove('hidden');
    
    // Fetch message details (simplified - in real app use AJAX)
    const contacts = <?php echo json_encode($contacts); ?>;
    const contact = contacts.find(c => c.id == id);
    
    if (contact) {
        document.getElementById('messageContent').innerHTML = `
            <div class="space-y-4">
                <div>
                    <label class="text-sm font-medium text-gray-500">From</label>
                    <p class="text-lg font-semibold text-gray-900">${contact.full_name}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Email</label>
                    <p class="text-gray-900">${contact.email}</p>
                </div>
                ${contact.phone ? `
                <div>
                    <label class="text-sm font-medium text-gray-500">Phone</label>
                    <p class="text-gray-900">${contact.phone}</p>
                </div>
                ` : ''}
                <div>
                    <label class="text-sm font-medium text-gray-500">Subject</label>
                    <p class="text-gray-900">${contact.subject.charAt(0).toUpperCase() + contact.subject.slice(1)}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Date</label>
                    <p class="text-gray-900">${new Date(contact.created_at).toLocaleString()}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Message</label>
                    <div class="mt-2 p-4 bg-gray-50 rounded-lg">
                        <p class="text-gray-900 whitespace-pre-wrap">${contact.message}</p>
                    </div>
                </div>
            </div>
        `;
    }
}

function closeModal() {
    document.getElementById('messageModal').classList.add('hidden');
}

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});

// Close modal on backdrop click
document.getElementById('messageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>