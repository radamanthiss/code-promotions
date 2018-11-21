/**
 * 
 */
function initMap()
{
    map = new google.maps.Map(document.getElementById('map'), {
        center: {
            lat: 41.390205,
            lng: 2.154007,
        },
        zoom: 8
    })
}