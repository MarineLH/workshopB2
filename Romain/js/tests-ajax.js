$(document).ready(function() {
    
    $('#test').click(function() {
        request = $.ajax({
            url: "assets/ajax-mng.php",
            type:"post",
            data: {requete : 'get_users', user_mail: 'romain.valeye@gmail.com', user_pwd: 'truc'}
            // la requete get_users ne requiert pas de paramètres donc ceci s'effectuera sans faire attention aux autres params
        });

        request.done(function (response, textStatus, jqXHR){
            alert("Response : " & response & "textStatus : " & textStatus);
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
            console.log('Coucou');

        });
    });
});