<!-- Toaster Component -->
<div id="toaster-container" class="position-fixed" style="top: 20px; right: 20px; z-index: 9999;">
    @if(session('success'))
        <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000">
            <div class="toast-header bg-success text-white">
                <i class="fa fa-check-circle mr-2"></i>
                <strong class="mr-auto">Success!</strong>
                <button type="button" class="ml-2 mb-1 close text-white" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="toast-body">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000">
            <div class="toast-header bg-danger text-white">
                <i class="fa fa-exclamation-circle mr-2"></i>
                <strong class="mr-auto">Error!</strong>
                <button type="button" class="ml-2 mb-1 close text-white" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="toast-body">
                {{ session('error') }}
            </div>
        </div>
    @endif

    @if(session('warning'))
        <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000">
            <div class="toast-header bg-warning text-dark">
                <i class="fa fa-exclamation-triangle mr-2"></i>
                <strong class="mr-auto">Warning!</strong>
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="toast-body">
                {{ session('warning') }}
            </div>
        </div>
    @endif

    @if(session('info'))
        <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000">
            <div class="toast-header bg-info text-white">
                <i class="fa fa-info-circle mr-2"></i>
                <strong class="mr-auto">Info!</strong>
                <button type="button" class="ml-2 mb-1 close text-white" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="toast-body">
                {{ session('info') }}
            </div>
        </div>
    @endif
</div>

<style>
.toast {
    min-width: 300px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    border: none;
    border-radius: 8px;
    margin-bottom: 10px;
}

.toast-header {
    border-bottom: none;
    border-radius: 8px 8px 0 0;
    padding: 12px 16px;
}

.toast-body {
    padding: 12px 16px;
    font-size: 14px;
}

.toast.show {
    animation: slideInRight 0.3s ease-out;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.toast.fade {
    animation: slideOutRight 0.3s ease-in;
}

@keyframes slideOutRight {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide toasts after 5 seconds
    const toasts = document.querySelectorAll('.toast');
    toasts.forEach(function(toast) {
        setTimeout(function() {
            if (toast.classList.contains('show')) {
                toast.classList.remove('show');
                toast.classList.add('fade');
                setTimeout(function() {
                    toast.remove();
                }, 300);
            }
        }, 5000);
    });

    // Manual close functionality
    const closeButtons = document.querySelectorAll('.toast .close');
    closeButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            const toast = this.closest('.toast');
            toast.classList.remove('show');
            toast.classList.add('fade');
            setTimeout(function() {
                toast.remove();
            }, 300);
        });
    });
});
</script> 