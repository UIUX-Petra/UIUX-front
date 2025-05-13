<style>
   .loader-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: rgba(168, 125, 223, 1);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    transition: transform 0.8s ease, opacity 0.5s ease;
}

/* Loader logo tetap berputar */
.loader-logo {
    width: 80px;
    height: 80px;
    animation: spin 2s linear infinite;
}

/* Ketika sudah dimuat, loader akan naik ke atas dan menghilang */
body.loaded .loader-overlay {
    transform: translateY(-100%);
    opacity: 0;
    pointer-events: none;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }

    to {
        transform: rotate(360deg);
    }
}

</style>
<div class="loader-overlay" id="loader">
    <img src="{{ asset('assets/p2p logo.svg') }}" alt="Loading..." class="loader-logo">
</div>
<script>
    window.addEventListener('load', function() {
        document.body.classList.add('loaded');
    });
</script>
