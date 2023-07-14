const chart = Highcharts.chart('container', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Desempeño de especialistas'
    },
    xAxis: {
        categories: [],
        crosshair: true,
    },
    yAxis: {
        title: {
            useHTML: true,
            text: 'Citas atendidas'
        }
    },
    tooltip: {
        headerFormat: '<span  style="font-size":10px>{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
    series: []
});


function fetchData(){
    //Fetch API
    fetch('/reportes/especialistas/column/data')
        .then(function(response) {
        return response.json();
    })
    .then(function(myJson) {
        console.log(myJson);
    });
}