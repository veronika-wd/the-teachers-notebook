document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            let isValid = true;

            // Валидация email
            const email = document.getElementById('email').value;
            const emailError = document.getElementById('emailError');
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                emailError.style.display = 'block';
                isValid = false;
            } else {
                emailError.style.display = 'none';
            }

            // Валидация пароля
            const password = document.getElementById('password').value;
            const passwordError = document.getElementById('passwordError');
            if (!/^[a-z0-9]+$/.test(password)) {
                passwordError.style.display = 'block';
                isValid = false;
            } else {
                passwordError.style.display = 'none';
            }

            if (isValid) {
                // Сохраняем статус авторизации и перенаправляем
                localStorage.setItem('isAuthenticated', 'true');
                window.location.href = 'loading.html';
            }
        });