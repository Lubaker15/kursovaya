const accountLinks = document.querySelectorAll('.account-link');
const accountTexts = document.querySelectorAll('.account-text');
const accountIcons = document.querySelectorAll('.account-icon');


document.getElementById('hamburger').addEventListener('click', function() {
    const mobileMenu = document.getElementById('mobileMenu');
    mobileMenu.classList.toggle('active');
});

const mobileLinks = document.querySelectorAll('.mobile-menu a');
mobileLinks.forEach(link => {
    link.addEventListener('click', () => {
        document.getElementById('mobileMenu').classList.remove('active');
    });
});

window.addEventListener('storage', function(e) {
    if (e.key === 'currentUser') {
        location.reload();
    }
});