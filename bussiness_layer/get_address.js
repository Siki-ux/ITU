/**
 * @author xsikul15@stud.fit.vutbr.cz
 * Funtion which returns adress from location
 */
function get_address(id,lat,lng){
    $.ajax({
        url:'https://maps.googleapis.com/maps/api/geocode/json?latlng='+lng+','+lat+'&location_type=ROOFTOP&result_type=street_address&key=AIzaSyCJVGL83AulBYsKWzBA0ooSruG4_CVIWqA',
        success: function(response){
            if(response.status !== "ZERO_RESULTS")
            {
                if(!isNaN(response.results[0].address_components[0].short_name[0]))
                {
                    first = response.results[0].address_components[1].short_name;
                    second = response.results[0].address_components[0].short_name;
                }
                else
                {
                    first = response.results[0].address_components[0].short_name;
                    second = response.results[0].address_components[1].short_name;
                }
    
                third = response.results[0].address_components[2].short_name;
    
                $('#address'+id).html(first + ' ' + second + ', ' + third);
                console.log(response.results[0].formatted_address);
            }
            else
                $('#address'+id).html(lat+" : "+lng);
        }
    });
}