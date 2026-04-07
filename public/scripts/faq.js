// Проверка авторизации
        if (!localStorage.getItem('isAuthenticated')) {
            window.location.href = 'auth.html';
        }
        
        // Заполняем email пользователя (в реальном приложении брали бы из API)
        document.getElementById('userEmail').textContent = localStorage.getItem('userEmail') || 'Пользователь';
        
        // Выход из системы
        document.getElementById('logoutBtn').addEventListener('click', function() {
            localStorage.removeItem('isAuthenticated');
            localStorage.removeItem('userEmail');
            window.location.href = 'index.html';
        });
        
        // Аккордеон FAQ
        const faqItems = document.querySelectorAll('.faq-item');
        faqItems.forEach(item => {
            const question = item.querySelector('.faq-question');
            question.addEventListener('click', () => {
                // Закрываем все открытые элементы
                faqItems.forEach(otherItem => {
                    if (otherItem !== item && otherItem.classList.contains('active')) {
                        otherItem.classList.remove('active');
                        otherItem.querySelector('.accordion-icon').textContent = '+';
                    }
                });
                
                // Открываем/закрываем текущий
                item.classList.toggle('active');
                const icon = item.querySelector('.accordion-icon');
                icon.textContent = item.classList.contains('active') ? '−' : '+';
            });
        });