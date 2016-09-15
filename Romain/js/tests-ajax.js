$(document).ready(function() {
    
    $('#test').click(function() {
        request = $.ajax({
            url: "assets/ajax-mng.php",
            type:"post",
            //data: {requete : 'get_user', ut_mail: 'test@test.com', ut_pwd: 'seb'}
            data: {requete : 'get_publications'}
        });

        request.done(function (response, textStatus, jqXHR){
            var jsonObj = $.parseJSON(response);
            console.log(jsonObj);
        });

        // Callback handler that will be called on failure
        request.fail(function (jqXHR, textStatus, errorThrown){
            console.log("Erreur avant l'envoi dans la DB!");
            // Log the error to the console
            console.error(
                "The following error occurred: "+
                textStatus, errorThrown
            );
        });

        // Callback handler that will be called regardless
        // if the request failed or succeeded
        request.always(function () {
            //console.log('Coucou');

        });
    });
});