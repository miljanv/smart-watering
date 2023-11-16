"use strict";
document.addEventListener("DOMContentLoaded", function () {

    var legendState = true;
    if (window.outerWidth < 576) {
        legendState = false;
    }

    const groupValuesByMonth = (data) => {
        const groupedData = {};
        data.forEach(entry => {
            const {value, month} = entry;

            if (!groupedData[month]) {
                groupedData[month] = [];
            }

            groupedData[month].push(parseFloat(value));
        });

        return groupedData;
    }


    const exportChartData = (labels, sensorData, filename, header) => {

        const groupedValues = groupValuesByMonth(sensorData);
        const csvContent = generateCSVContent(labels, groupedValues, header);
        const blob = new Blob([csvContent], {type: 'text/csv;charset=utf-8;'});
        const link = document.createElement('a');

        link.href = URL.createObjectURL(blob);
        link.download = filename;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    const generateCSVContent = (labels, groupedValues, csvHeader) => {
        const header = csvHeader;
        const csvRows = [];
        labels.forEach(label => {
            csvRows.push(`${label}: ${groupedValues[label]}`);
        });

        return header + csvRows.join('\n');
    }


    const filterDataByDateRange = (data, startDate, endDate) => {
        return data.filter(item => {
            const timestamp = moment(item.timestamp, 'YYYY-MM-DD HH:mm:ss');
            return (
                timestamp.isBetween(startDate, endDate, null, '[]')
            );
        });
    }

    const initLineChart = (chartId, labels, sensorData, filteredData, chartLabel, color, start, end) => {
        var LINECHART = document.getElementById(chartId);
        var myLineChart = new Chart(LINECHART, {
            type: 'line',
            options: {
                scales: {
                    xAxes: [{
                        display: true,
                        gridLines: {
                            display: true
                        }
                    }],
                    yAxes: [{
                        display: true,
                        gridLines: {
                            display: true
                        }
                    }]
                },
                legend: {
                    display: legendState
                }
            },
            data: {
                labels: labels,
                datasets: [{
                    label: start && end ? `${start.format('DD/MM/YYYY')} - ${end.format('DD/MM/YYYY')}` : `${chartLabel} Data`,
                    fill: true,
                    lineTension: 0,
                    backgroundColor: "transparent",
                    borderColor: color,
                    pointBorderColor: color,
                    pointHoverBackgroundColor: color,
                    borderCapStyle: 'butt',
                    borderDash: [],
                    borderDashOffset: 0.0,
                    borderJoinStyle: 'miter',
                    borderWidth: 1,
                    pointBackgroundColor: "#fff",
                    pointBorderWidth: 1,
                    pointHoverRadius: 5,
                    pointHoverBorderColor: "#fff",
                    pointHoverBorderWidth: 2,
                    pointRadius: 1,
                    pointHitRadius: 0,
                    data: start && end ? filteredData.map(item => parseFloat(item.value)) : sensorData,
                    spanGaps: false
                }]
            }
        });
    }

    const generateLabels = (labelsSet) =>
        Array.from(labelsSet).sort((a, b) => {
            return a - b;
        });

    const handleLabelsSet = (data, set) =>
        data.forEach(entry => {
            const timestamp = new Date(entry.timestamp);
            const month = new Intl.DateTimeFormat('en-US').format(timestamp);
            set.add(month);
        });


    async function fetchDataAndCreateChart(fetchFile, csvFileName, csvHeader, chartId, chartLabel, exportBtnId, color, typeToFilter, inputRange, inputClear) {
        try {
            const response = await fetch(fetchFile);
            const urlParams = new URLSearchParams(window.location.search);
            const deviceId = urlParams.get('device');
            const data = await response.json();

            const sensorData = data.map(item => parseFloat(item.value));

            let labelsSet = new Set();
            data.forEach(entry => {
                const timestamp = new Date(entry.timestamp);
                const month = new Intl.DateTimeFormat('en-US').format(timestamp);
                labelsSet.add(month);
            });

            let labels = generateLabels(labelsSet);

            console.log('labels', labels);
            console.log('sensortData', sensorData)

            let filteredData = [];
            let start;
            let end;

            $(function () {
                $(`input[name="${inputRange}"]`).daterangepicker({
                    opens: 'left',
                    locale: {
                        format: 'DD/MM/YYYY'
                    }
                }, function (startDate, endDate, label) {
                    start = startDate;
                    end = endDate;
                    filteredData = filterDataByDateRange(data, startDate, endDate);
                    labelsSet.clear();

                    handleLabelsSet(filteredData, labelsSet);
                    labels = generateLabels(labelsSet);
                    initLineChart(chartId, labels, sensorData, filteredData, chartLabel, color, start, end);
                });
            });

            document.getElementById(inputClear).addEventListener('click', () => {
                    labelsSet.clear();
                    handleLabelsSet(data, labelsSet);
                    labels = generateLabels(labelsSet);
                    initLineChart(chartId, labels, sensorData, [], chartLabel, color)
                }
            );


            document.getElementById(exportBtnId).addEventListener('click', () => {
                const allData = [...data, ...filteredData];
                const csvData = allData.map((item) => {
                    return {
                        value: item.value,
                        month: new Intl.DateTimeFormat('en-US').format(new Date(item.timestamp))
                    }
                })

                const unique = csvData.filter((obj, index) => {
                    return index === csvData.findIndex(o => obj.value === o.value);
                });

                return exportChartData(labels, unique, csvFileName, csvHeader)
            });

            initLineChart(chartId, labels, sensorData, filteredData, chartLabel, color);
        } catch (error) {
            console.error('Error fetching data from PHP:', error);
        }
    }

    fetchDataAndCreateChart('db/getHumidityChartData.php', 'humidity_chart_data.csv', 'Month\n', 'lineChartHumidity', 'Humidity', 'exportButtonHumidity', 'blue', 'humidity', 'dateRangeHumidity', 'clearRangeHumidity');
    fetchDataAndCreateChart('db/getPhChartData.php', 'ph_chart_data.csv', 'Month\n', 'lineChartPh', 'Ph', 'exportButtonPh', 'red', 'ph', 'dateRangePh', 'clearRangePh');


});
