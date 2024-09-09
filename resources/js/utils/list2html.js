export function list2Html(list, columns)
{
    if(0 === list.length)
        throw new Error("Exported list is empty");

    const html = (headers, rows) => {

        const th = headers.map(el => `<th>${el}</th> `).join('');
        const trows = rows.map(row => row.map(col => `<td>${col}</td>`).join(''));

        return `
            <table border="1" style="text-align: center">
                <thead>
                    <tr>${th}</tr>
                </thead>
                <tbody>
                    ${trows.map(trow => `<tr>${trow}</tr>`).join('')}
                </tbody>
            </table>
        `;
    }

    const rows = []

    for (const item of list)
    {
        const obj = columns.reduce((result, item) => {
            result[item] = "";
            return result;
        }, {});

        for (const column in item)
        {
            if(columns.includes(column))
            {
                obj[column] = item[column];
            }
        }

        rows.push(Object.values(obj));
    }

    return html(columns, rows);
}
