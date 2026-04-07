// ============================================
// ГЛОБАЛЬНЫЕ ПЕРЕМЕННЫЕ
// ============================================
let currentTable = null;          // Хранит текущий экземпляр Tabulator
let currentData = [];             // Хранит текущие данные для поиска
let searchHandler = null;         // Хранит ссылку на текущую функцию поиска

// ============================================
// ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ
// ============================================

/**
 * Безопасная проверка на включение подстроки
 * Работает со строками, числами, объектами, массивами, null/undefined
 */
function safeIncludes(value, searchTerm) {
    if (value === null || value === undefined) return false;

    let strValue;
    if (typeof value === 'object') {
        // Если объект — извлекаем все строковые значения и объединяем
        strValue = Object.values(value)
            .filter(v => typeof v === 'string' || typeof v === 'number')
            .join(' ')
            .toLowerCase();
    } else {
        strValue = String(value).toLowerCase();
    }
    return strValue.includes(searchTerm);
}

/**
 * Очищает предыдущие обработчики и таблицу перед загрузкой новых данных
 */
function cleanupPrevious() {
    const searchInput = document.getElementById('searchInput');

    // Удаляем старый обработчик поиска
    if (searchHandler) {
        searchInput.removeEventListener('input', searchHandler);
        searchHandler = null;
    }

    // Уничтожаем предыдущую таблицу Tabulator
    if (currentTable && typeof currentTable.destroy === 'function') {
        currentTable.destroy();
        currentTable = null;
    }

    // Сбрасываем данные
    currentData = [];
}

// ============================================
// ОСНОВНАЯ ФУНКЦИЯ ЗАГРУЗКИ ДАННЫХ
// ============================================
async function loadData(url = '/api/students') {
    const searchInput = document.getElementById('searchInput');

    // Очищаем предыдущее состояние
    cleanupPrevious();

    // Показываем/скрываем кнопку сортировки по классу
    const orderByClassBtn = document.getElementById('orderByClass');
    if (orderByClassBtn) {
        orderByClassBtn.style.display = (url === '/api/students') ? 'inline-block' : 'none';
    }

    try {
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        currentData = data; // Сохраняем данные для поиска

        // ============================================
        // СТУДЕНТЫ / УЧЕНИКИ
        // ============================================
        if (url === '/api/students') {
            currentTable = new Tabulator("#database-table", {
                data: data,
                layout: "fitColumns",
                columns: [
                    {title: "Фамилия", field: "surname"},
                    {title: "Имя", field: "name"},
                    {title: "Отчество", field: "patronymic"},
                    {title: "Дата рождения", field: "birthDate"},
                    {title: "Класс", field: "class"},
                    {title: "Серия и номер паспорта", field: "passportData"},
                    {title: "Родитель", field: "parent"},
                    {title: "Номер телефона родителя", field: "parentPhone"},
                    {title: "Адрес проживания", field: "address"},
                    {title: "Статус", field: "status"}
                ],
            });

            // Сортировка по классу
            const sortByClassBtn = document.getElementById('orderByClass');
            if (sortByClassBtn) {
                sortByClassBtn.onclick = () => {
                    currentTable.setSort([{column: "class", dir: "asc"}]);
                };
            }

            // Сортировка по фамилии
            const sortBySurnameBtn = document.getElementById('orderBySurname');
            if (sortBySurnameBtn) {
                sortBySurnameBtn.onclick = () => {
                    currentTable.setSort([{column: "surname", dir: "asc"}]);
                };
            }

            // Функция поиска для студентов
            searchHandler = function performSearchStudents() {
                const searchTerm = searchInput.value.trim().toLowerCase();

                if (searchTerm === '') {
                    currentTable.setData(currentData);
                    return;
                }

                const results = currentData.filter(item => {
                    return (
                        safeIncludes(item.name, searchTerm) ||
                        safeIncludes(item.surname, searchTerm) ||
                        safeIncludes(item.patronymic, searchTerm) ||
                        safeIncludes(item.birthDate, searchTerm) ||
                        safeIncludes(item.class, searchTerm) ||
                        safeIncludes(item.passportData, searchTerm) ||
                        safeIncludes(item.parent, searchTerm) ||
                        safeIncludes(item.parentPhone, searchTerm) ||
                        safeIncludes(item.address, searchTerm) ||
                        safeIncludes(item.status, searchTerm)
                    );
                });

                currentTable.setData(results);
            };

        }
            // ============================================
            // УЧИТЕЛЯ
        // ============================================
        else if (url === '/api/teachers') {
            currentTable = new Tabulator("#database-table", {
                data: data,
                layout: "fitColumns",
                columns: [
                    {title: "ФИО", field: "fullname"},
                    {title: "Телефон", field: "phone"},
                    {title: "Дата рождения", field: "birthDate"},
                    {title: "Серия и номер паспорта", field: "pasportData"},
                    {title: "СНИЛС", field: "snils"},
                    {title: "ИНН", field: "inn"},
                    {title: "Адрес проживания", field: "address"},
                    {title: "Должность", field: "post"},
                    {title: "Образование", field: "education"},
                    {title: "Опыт работы", field: "experience"},
                    {title: "Аттестация", field: "qualification"},
                ],
            });

            // Сортировка по фамилии (для учителей поле surname может отсутствовать, используем fullname)
            const sortBySurnameBtn = document.getElementById('orderBySurname');
            if (sortBySurnameBtn) {
                sortBySurnameBtn.onclick = () => {
                    currentTable.setSort([{column: "fullname", dir: "asc"}]);
                };
            }

            // Функция поиска для учителей
            searchHandler = function performSearchTeachers() {
                const searchTerm = searchInput.value.trim().toLowerCase();

                if (searchTerm === '') {
                    currentTable.setData(currentData);
                    return;
                }

                const results = currentData.filter(item => {
                    return (
                        safeIncludes(item.fullname, searchTerm) ||
                        safeIncludes(item.phone, searchTerm) ||
                        safeIncludes(item.birthDate, searchTerm) ||
                        safeIncludes(item.pasportData, searchTerm) ||
                        safeIncludes(item.snils, searchTerm) ||
                        safeIncludes(item.inn, searchTerm) ||
                        safeIncludes(item.address, searchTerm) ||
                        safeIncludes(item.post, searchTerm) ||
                        safeIncludes(item.education, searchTerm) ||
                        safeIncludes(item.experience, searchTerm) ||
                        safeIncludes(item.qualification, searchTerm)
                    );
                });

                currentTable.setData(results);
            };

        }
            // ============================================
            // РОДИТЕЛИ / ОПЕКУНЫ
        // ============================================
        else {
            currentTable = new Tabulator("#database-table", {
                data: data,
                layout: "fitColumns",
                columns: [
                    {title: "ФИО", field: "fullName"},
                    {title: "Телефон", field: "phone"},
                    {title: "Место работы", field: "job"},
                    {title: "Адрес проживания", field: "address"},
                    {title: "Статус", field: "status"},
                ],
            });

            // Сортировка по ФИО
            const sortBySurnameBtn = document.getElementById('orderBySurname');
            if (sortBySurnameBtn) {
                sortBySurnameBtn.onclick = () => {
                    currentTable.setSort([{column: "fullName", dir: "asc"}]);
                };
            }

            // Функция поиска для родителей
            searchHandler = function performSearchGuardians() {
                const searchTerm = searchInput.value.trim().toLowerCase();

                if (searchTerm === '') {
                    currentTable.setData(currentData);
                    return;
                }

                const results = currentData.filter(item => {
                    return (
                        safeIncludes(item.fullName, searchTerm) ||
                        safeIncludes(item.job, searchTerm) ||
                        safeIncludes(item.status, searchTerm) ||
                        safeIncludes(item.phone, searchTerm) ||
                        safeIncludes(item.address, searchTerm)
                    );
                });

                currentTable.setData(results);
            };
        }

        // Подключаем обработчик поиска к полю ввода
        searchInput.addEventListener('input', searchHandler);

        console.log('✅ Таблица загружена:', url);

    } catch (error) {
        console.error('❌ Ошибка загрузки данных:', error);
        alert('Не удалось загрузить данные. Проверьте консоль для деталей.');
    }
}

// ============================================
// ОБРАБОТЧИКИ КНОПОК НАВИГАЦИИ
// ============================================
document.addEventListener('DOMContentLoaded', () => {
    const pupilsBtn = document.getElementById('pupils');
    const teachersBtn = document.getElementById('teachers');
    const guardiansBtn = document.getElementById('guardians');

    if (pupilsBtn) {
        pupilsBtn.addEventListener('click', () => loadData('/api/students'));
    }
    if (teachersBtn) {
        teachersBtn.addEventListener('click', () => loadData('/api/teachers'));
    }
    if (guardiansBtn) {
        guardiansBtn.addEventListener('click', () => loadData('/api/guardians'));
    }

    // Загружаем студентов по умолчанию при первом запуске
    loadData('/api/students');
});
