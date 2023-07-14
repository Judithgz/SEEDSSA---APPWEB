let $colaboradores, $date, $services, iRadio;
let $hoursMorning, $hoursAfternoon, $titleMorning, $titleAfternoon;

const titleMorning =
    `En la mañana`
;

const titleAfternoon = `
    En la tarde
`;

const noHours = `<h5 class="text-danger">
    No hay horas disponibles
</h5>`

$(function(){
    $services = $('#services');
    $colaboradores = $('#colaboradores');
    $date = $('#date');
    $titleMorning = $('#titleMorning');
    $hoursMorning = $('#hoursMorning');
    $titleAfternoon = $('#titleAfternoon');
    $hoursAfternoon = $('#hoursAfternoon');

    $services.change(() => {
        const servicesId = $services.val();
        const url = `/api/servicios/${servicesId}/nosotros`;
        $.getJSON(url, onEspecialistasLoaded);
    });

    $colaboradores.change(loadHours);
    $date.change(loadHours);
});

function onEspecialistasLoaded (especialistas) {
    let htmlOptions = '';
    especialistas.forEach(colaboradores => {
        htmlOptions += `<option value="${colaboradores.id}" >${colaboradores.name}</option>`;
    });
    $colaboradores.html(htmlOptions);

    loadHours();
}

function loadHours() {
    const selectedDate = $date.val();
    const colaboradoresId = $colaboradores.val();
    const url = `/api/horario/horas?date=${selectedDate}&colaboradores_id=${colaboradoresId}`;
    $.getJSON(url, displayHours);
}

function displayHours(data) {
    let htmlHoursM = '';
    let htmlHoursA = '';

    iRadio = 0;

    if(data.morning){
        const morning_intervalos = data.morning;
        morning_intervalos.forEach(intervalo => {
            htmlHoursM += getRadioIntervaloHTML(intervalo);
        });
    }
    if(!htmlHoursM != ""){
        htmlHoursM+= noHours;
    }

    if(data.afternoon){
        const afternoon_intervalos = data.afternoon;
        afternoon_intervalos.forEach(intervalo => {
            htmlHoursA += getRadioIntervaloHTML(intervalo);
        });
    }
    if(!htmlHoursA != ""){
        htmlHoursA+= noHours;
    }

    $hoursMorning.html(htmlHoursM);
    $hoursAfternoon.html(htmlHoursA);
    $titleMorning.html(titleMorning);
    $titleAfternoon.html(titleAfternoon);
}

function getRadioIntervaloHTML(intervalo){
    const text = `${intervalo.start} - ${intervalo.end}`;

    return `<div class="custom-control custom-radio mb-3">
            <input type="radio" id="interval${iRadio}" name="scheduled_time" value="${intervalo.start}" class="custom-control-input" required>
            <label class="custom-control-label" for="interval${iRadio++}">${text}</label>
            </div>`
}