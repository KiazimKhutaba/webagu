// const url = (lon, lat) => `https://maps.yandex.ru?ll=${lon},${lat}`;

class Geolocation
{
    constructor(onSuccess, onError) {
        navigator.geolocation.getCurrentPosition(onSuccess, onError, {
            enableHighAccuracy: true
        })
    }
}

function onSuccess({ coords }) {
    const { latitude, longitude } = coords
    const position = [latitude, longitude]
    console.log(url(longitude, latitude)) // [широта, долгота]
}

function success({ coords }) {

}

function error({ message }) {
    // alert('Без доступа к данному разрешению тесты работать не будут');
    console.log(message) // при отказе в доступе получаем PositionError: User denied Geolocation
}
