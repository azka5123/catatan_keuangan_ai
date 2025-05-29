// Import CSS
import '../css/app.css';

// Global JavaScript functions for the finance app
window.FinanceApp = {
    // Currency formatter
    formatCurrency: function(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(amount);
    },

    // Number formatter without currency symbol
    formatNumber: function(amount) {
        return new Intl.NumberFormat('id-ID').format(amount);
    },

    // Date formatter
    formatDate: function(date, options = {}) {
        const defaultOptions = {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        return new Date(date).toLocaleDateString('id-ID', { ...defaultOptions, ...options });
    },

    // Confirm dialog
    confirm: function(message, callback) {
        if (confirm(message)) {
            callback();
        }
    },

    // Loading state management
    setLoading: function(element, isLoading, originalText = '') {
        if (isLoading) {
            element.dataset.originalText = element.innerHTML;
            element.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Loading...';
            element.disabled = true;
        } else {
            element.innerHTML = originalText || element.dataset.originalText;
            element.disabled = false;
        }
    },

    // Number input formatter
    formatNumberInput: function(input) {
        let value = input.value.replace(/[^0-9]/g, '');
        if (value) {
            input.value = parseInt(value).toLocaleString('id-ID');
        }
    },

    // Get clean number from formatted input
    getCleanNumber: function(value) {
        return parseInt(value.replace(/[^0-9]/g, '')) || 0;
    }
};

// Global event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alerts
    const alerts = document.querySelectorAll('.alert-auto-hide');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 300);
        }, 5000);
    });

    // Format number inputs automatically
    const numberInputs = document.querySelectorAll('input[data-format="number"]');
    numberInputs.forEach(input => {
        input.addEventListener('input', function() {
            FinanceApp.formatNumberInput(this);
        });
    });

    // Sidebar toggle for mobile
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebar = document.getElementById('sidebar');

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('-translate-x-full');
        });
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        const dropdowns = document.querySelectorAll('.dropdown-menu');
        dropdowns.forEach(dropdown => {
            if (!dropdown.contains(e.target) && !dropdown.previousElementSibling.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    });
});

// Export for use in other files
export default window.FinanceApp;
