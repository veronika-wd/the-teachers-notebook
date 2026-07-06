// Example starter JavaScript for disabling form submissions if there are invalid fields
(function () {
    'use strict'

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.querySelectorAll('.needs-validation')

    // Loop over them and prevent submission
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }

                form.classList.add('was-validated')
            }, false)
        })
})()

function phoneMask(input) {
    input.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, ''); // Удаляем все нецифровые символы

        // Ограничиваем до 11 цифр (7 + 10 цифр номера)
        if (value.length > 11) {
            value = value.slice(0, 11);
        }

        // Начинаем с 7, если пользователь ввел что-то другое
        if (value.length > 0 && value[0] !== '7') {
            value = '7' + value;
        }

        let formattedValue = '';

        if (value.length > 0) {
            formattedValue = '+' + value[0];
        }
        if (value.length > 1) {
            formattedValue += ' (' + value.substring(1, 4);
        }
        if (value.length >= 4) {
            formattedValue += ')';
        }
        if (value.length > 4) {
            formattedValue += ' ' + value.substring(4, 7);
        }
        if (value.length > 7) {
            formattedValue += '-' + value.substring(7, 9);
        }
        if (value.length > 9) {
            formattedValue += '-' + value.substring(9, 11);
        }

        e.target.value = formattedValue;
    });

    // Обработка удаления символов
    input.addEventListener('keydown', function(e) {
        if (e.key === 'Backspace' || e.key === 'Delete') {
            setTimeout(() => {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 0 && value[0] !== '7') {
                    value = '7' + value;
                }
                e.target.value = value ? '+' + value : '';
            }, 0);
        }
    });
}

const phoneInputs = document.querySelectorAll('.phone');
phoneInputs.forEach(input => phoneMask(input))

function snilsMask(input) {
    input.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, ''); // Удаляем все нецифровые символы

        // Ограничиваем до 11 цифр
        if (value.length > 11) {
            value = value.slice(0, 11);
        }

        let formattedValue = '';

        if (value.length > 0) {
            formattedValue = value.substring(0, 3);
        }
        if (value.length > 3) {
            formattedValue += '-' + value.substring(3, 6);
        }
        if (value.length > 6) {
            formattedValue += '-' + value.substring(6, 9);
        }
        if (value.length > 9) {
            formattedValue += '-' + value.substring(9, 11);
        }

        e.target.value = formattedValue;
    });
}

// Применение маски
const snilsInputs = document.querySelectorAll('.snils');
snilsInputs.forEach(input => snilsMask(input))

