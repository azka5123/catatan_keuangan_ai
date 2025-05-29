{{-- CSS Files --}}
@vite(['resources/css/app.css'])

{{-- iziToast --}}
<link rel="stylesheet" href="{{ asset('dist/css/iziToast.min.css') }}">
<script src="{{ asset('dist/js/iziToast.min.js') }}"></script>

{{-- Custom CSS --}}
<style>
    .sidebar-link:hover {
        background-color: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }

    .active-link {
        background-color: #3b82f6;
        color: white;
    }

    .balance-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .income-card {
        background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
    }

    .expense-card {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    }
</style>
