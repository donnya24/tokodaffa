<?php
?>

<!-- Script untuk modal logout -->
<script>
// Logout Modal Functions
function openLogoutModal() {
    const modal = document.getElementById('logoutModal');
    const modalContent = document.getElementById('logoutModalContent');
    
    if (!modal || !modalContent) return;
    
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    
    // Animasi fade in
    setTimeout(() => {
        modal.classList.add('bg-opacity-50');
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeLogoutModal() {
    const modal = document.getElementById('logoutModal');
    const modalContent = document.getElementById('logoutModalContent');
    
    if (!modal || !modalContent) return;
    
    // Animasi fade out
    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }, 300);
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('logoutModal');
    if (event.target === modal) {
        closeLogoutModal();
    }
}

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeLogoutModal();
    }
});
</script>

</div> <!-- Penutup main content (flex-1) -->
</div> <!-- Penutup flex h-screen -->

<!-- Include modal logout -->
<?php include 'partials/logout_modal.php'; ?>

</body>
</html>