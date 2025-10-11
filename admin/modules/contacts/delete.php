<?php
/**
 * Contacts Management - Delete
 * PT Komodo Industrial Indonesia
 */

define('APP_ACCESS', true);

require_once __DIR__ . '/../../../config/constants.php';
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../includes/functions.php';
require_once __DIR__ . '/../../../includes/auth.php';

requireLogin();

$contactId = (int)get('id', 0);

// Get contact
$contact = $db->fetchOne("SELECT * FROM contacts WHERE id = ?", [$contactId]);

if (!$contact) {
    setFlash('error', 'Message not found');
    redirect(ADMIN_URL . '/modules/contacts/index.php');
}

// Delete contact
$sql = "DELETE FROM contacts WHERE id = ?";
if ($db->query($sql, [$contactId])) {
    setFlash('success', 'Message deleted successfully!');
} else {
    setFlash('error', 'Failed to delete message');
}

redirect(ADMIN_URL . '/modules/contacts/index.php');