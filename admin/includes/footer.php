<?php
/**
 * Admin Footer
 * PT Komodo Industrial Indonesia
 */

if (!defined('APP_ACCESS')) {
    die('Direct access not permitted');
}
?>
        </main>
    </div>

    <!-- Alpine.js for dropdown functionality -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        // Sidebar Toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        document.getElementById('sidebarToggle')?.addEventListener('click', toggleSidebar);

        // Confirm Delete
        function confirmDelete(message = 'Are you sure you want to delete this item?') {
            return confirm(message);
        }

        // Image Preview
        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(function(alert) {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            });
        }, 5000);

        // Form validation helper
        function validateForm(formId) {
            const form = document.getElementById(formId);
            if (!form.checkValidity()) {
                form.reportValidity();
                return false;
            }
            return true;
        }

        // Character counter
        function updateCharCount(textareaId, counterId, maxLength) {
            const textarea = document.getElementById(textareaId);
            const counter = document.getElementById(counterId);
            const currentLength = textarea.value.length;
            counter.textContent = `${currentLength} / ${maxLength}`;
            
            if (currentLength > maxLength) {
                counter.classList.add('text-red-600');
            } else {
                counter.classList.remove('text-red-600');
            }
        }

        // DataTable search (simple client-side)
        function searchTable(inputId, tableId) {
            const input = document.getElementById(inputId);
            const filter = input.value.toLowerCase();
            const table = document.getElementById(tableId);
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) {
                const row = rows[i];
                const cells = row.getElementsByTagName('td');
                let found = false;

                for (let j = 0; j < cells.length; j++) {
                    const cell = cells[j];
                    if (cell.textContent.toLowerCase().indexOf(filter) > -1) {
                        found = true;
                        break;
                    }
                }

                row.style.display = found ? '' : 'none';
            }
        }

        // Loading state for buttons
        function setButtonLoading(buttonId, loading = true) {
            const button = document.getElementById(buttonId);
            if (loading) {
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
            } else {
                button.disabled = false;
                button.innerHTML = button.getAttribute('data-original-text');
            }
        }

        // Toast notification (simple)
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
            
            toast.className = `fixed bottom-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300`;
            toast.innerHTML = `
                <div class="flex items-center space-x-2">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
    </script>
</body>
</html>