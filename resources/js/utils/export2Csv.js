import downloadFile from "./donwloadFile.js";

export default function export2Csv(cols, rows, filename = 'export_file.csv')
{
    const csvHeader = Object.values(cols).map(value => `"${value}"`).join(',') + '\r\n';

    const csvRows = function arrayToCsv(data){
        return data.map(row =>
            Object.values(row)
                .slice(1)
                .map(String)  // convert every value to String
                .map(v => v.replaceAll('"', '""'))  // escape double quotes
                .map(v => `"${v}"`)  // quote it
                .join(',')  // comma-separated
        ).join('\r\n');  // rows starting on new lines
    };

    const data = csvHeader + csvRows(rows);

    downloadFile(data, filename, 'text/csv;charset=UTF-8');
}

