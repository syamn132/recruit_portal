<?php
// alerts.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<?php if (isset($_SESSION['error'])): ?>
<!-- Error Alert -->
<div class="alert-error fixed top-4 right-4 max-w-sm w-full bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-lg transition-opacity duration-300 ease-in-out z-50">
    <div class="flex items-center justify-between">
        <span class="block sm:inline"><?= htmlspecialchars($_SESSION['error']) ?></span>
        <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-red-700 hover:text-red-900">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </button>
    </div>
</div>
<?php unset($_SESSION['error']); endif; ?>

<?php if (isset($_SESSION['success'])): ?>
<!-- Success Alert -->
<div class="alert-success fixed top-4 right-4 max-w-sm w-full bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-lg transition-opacity duration-300 ease-in-out z-50">
    <div class="flex items-center justify-between">
        <span class="block sm:inline"><?= htmlspecialchars($_SESSION['success']) ?></span>
        <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-green-700 hover:text-green-900">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </button>
    </div>
</div>
<?php unset($_SESSION['success']); endif; ?>

<!-- Inline CSS for the alerts -->
<style>
    /* Custom CSS for Alerts */
    .alert-error, .alert-success {
        /* Position alerts at the top-right */
        position: fixed;
        top: 5rem; /* Distance from the top of the page */
        right: 1rem;/* Distance from the right of the page */
        max-width: 300px; /* Maximum width for the alerts */
        width: 100%; /* Full width for small screens */
        z-index: 99999; /* Ensure it's on top of other elements */
        border-radius: 0.375rem; /* Rounded corners */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Shadow for depth */
    }

    .alert-error {
        background-color: #fee2e2; /* Light red background for errors */
        border-color: #fca5a5; /* Light red border */
        color: #dc2626; /* Red text */
    }

    .alert-success {
        background-color: #d1fae5; /* Light green background for success */
        border-color: #86efac; /* Light green border */
        color: #15803d; /* Green text */
    }

    .alert-error button, .alert-success button {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 1.25rem;
    }

    .alert-error button:hover, .alert-success button:hover {
        color: #9b1d1d; /* Darker red on hover for close button */
    }

    /* Transition for smooth disappearing */
    .transition-opacity {
        transition: opacity 0.3s ease-in-out;
    }
</style>

<script>
    // Auto-remove alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert-error, .alert-success');
            alerts.forEach(alert => {
                alert.remove();
            });
        }, 5000);
    });

    // Optionally, you can manually remove the alert when clicking on the close button:
    document.querySelectorAll('.alert-error button, .alert-success button').forEach(button => {
        button.addEventListener('click', function () {
            this.parentElement.parentElement.remove();
        });
    });
</script>
