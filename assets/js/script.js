// =============================================
// YIC Library System — script.js
// Vanilla JavaScript ES6 | No Frameworks
// =============================================

document.addEventListener('DOMContentLoaded', function () {

    // 1. Auto-hide alerts after 5 seconds
    document.querySelectorAll('.alert-success, .alert-error, .alert-warning').forEach(function (el) {
        if (el.id) return; // skip placeholder divs (they have IDs)
        setTimeout(function () {
            el.style.transition = 'opacity 0.6s ease';
            el.style.opacity = '0';
            setTimeout(function () { el.style.display = 'none'; }, 650);
        }, 5000);
    });

    // 2. Login form validation
    var loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function (e) {
            var email    = document.getElementById('email').value.trim();
            var password = document.getElementById('password').value.trim();
            if (!email || !password) {
                e.preventDefault();
                showError('loginError', 'Please fill in all fields.');
            } else if (!validEmail(email)) {
                e.preventDefault();
                showError('loginError', 'Please enter a valid email address.');
            }
        });
    }

    // 3. Register form validation
    var regForm = document.getElementById('registerForm');
    if (regForm) {
        regForm.addEventListener('submit', function (e) {
            var name     = document.getElementById('username').value.trim();
            var email    = document.getElementById('email').value.trim();
            var password = document.getElementById('password').value.trim();
            if (!name || !email || !password) {
                e.preventDefault();
                showError('registerError', 'All fields are required.');
            } else if (!validEmail(email)) {
                e.preventDefault();
                showError('registerError', 'Please enter a valid email address.');
            } else if (password.length < 6) {
                e.preventDefault();
                showError('registerError', 'Password must be at least 6 characters.');
            }
        });
    }

    // 4. Highlight active nav link
    var page = window.location.pathname.split('/').pop();
    document.querySelectorAll('nav a').forEach(function (a) {
        if (a.getAttribute('href') && a.getAttribute('href').indexOf(page) !== -1 && page !== '') {
            a.style.background = 'rgba(255,255,255,0.25)';
            a.style.color = '#D2B48C';
        }
    });

});

function showError(id, msg) {
    var el = document.getElementById(id);
    if (el) {
        el.textContent = '❌ ' + msg;
        el.style.display = 'block';
        el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
}

function validEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}
