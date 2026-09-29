document.getElementById('excelTable').addEventListener('click', async (e) => {
    e.preventDefault();

    const workbook = new ExcelJS.Workbook();
    const worksheet = workbook.addWorksheet('Список');

    worksheet.columns = [
        { header: 'Фамилия', key: 'surname', width: 30 },
        { header: 'Имя', key: 'name', width: 20 },
        { header: 'Отчество', key: 'patronymic', width: 30 },
        { header: 'Класс', key: 'class', width: 10 },
        { header: 'Адрес проживания', key: 'address', width: 40 },
        { header: 'Серия и номер паспорта', key: 'passport', width: 15 },
        { header: 'Дата рождения', key: 'birthDate', width: 20 },
        { header: 'Статус', key: 'status', width: 30 }
    ];

    const rows = document.querySelectorAll('.tabulator-row');

    const data = [];

    rows.forEach(row => {
        const student = {};

         const fields = row.querySelectorAll('.tabulator-cell');

         fields.forEach(field => {
             student[field.getAttribute('tabulator-field')] = field.textContent;
         });

        data.push(student);
    })

    worksheet.addRows(data);

    // Жирный шрифт для заголовка
    worksheet.getRow(1).font = { bold: true };
    worksheet.getRow(1).fill = {
        type: 'pattern',
        pattern: 'solid',
        fgColor: { argb: 'cdbbf0' } // Серый фон
    };

    worksheet.eachRow((row) => {
        row.eachCell((cell) => {
            cell.border = {
                top: { style: 'thin', color: { argb: 'FFD3D3D3' } },
                left: { style: 'thin', color: { argb: 'FFD3D3D3' } },
                bottom: { style: 'thin', color: { argb: 'FFD3D3D3' } },
                right: { style: 'thin', color: { argb: 'FFD3D3D3' } }
            };
        });
    });

    try {
        const buffer = await workbook.xlsx.writeBuffer();
        const blob = new Blob([buffer], {
            type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        });

        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'Список учеников.xlsx';
        document.body.appendChild(a);
        a.click();

        setTimeout(() => {
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
        }, 100);

        window.URL.revokeObjectURL(url);
        document.body.removeChild(a);
    } catch (error) {
        console.error('Ошибка при создании файла:', error);
    }

});



