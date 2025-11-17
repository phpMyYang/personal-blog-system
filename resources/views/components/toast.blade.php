@props(['type', 'message'])

<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1080;">
    <div id="liveToast-{{ $type }}" class="toast align-items-center text-white bg-{{ $type == 'error' ? 'danger' : 'success' }} border-0" 
         role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-{{ $type == 'error' ? 'x-circle' : 'check-circle' }} me-2"></i>
                {{ $message }}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>