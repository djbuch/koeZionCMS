$(document).ready(function() {

	//Effets sur le moteur de recherche
	hasFocci = false;
	$('.search input[type=text]').focus(function(){
		$(this).stop().animate({"opacity": "1"},{queue:false,duration:300});
		hasFocci = true;
	}).blur(function() {
		$(this).stop().animate({"opacity": "0.5"},{queue:false,duration:300});
		hasFocci = false;
	}).dblclick(function() {
		$(this).val('');
		hasFocci = true;
	});
	$('.search input[type=submit]').hover(function(){
		$(this).stop().animate({"opacity": "1"},{queue:false,duration:300});
	},function() {
		if(!hasFocci){$(this).stop().animate({"opacity": "0.5"},{queue:false,duration:300});}
	});
	
	
	//On va effacer le contenu du champ lorsqu'il récupère le focus
	$('input, textarea').focus(function() {
		
		if($(this).val() == $(this).attr('title')) { $(this).val(''); }
	});
	
	//Lorsqu'il perd le focus on va rétablir la valeur initiale si rien n'a été saisi
	$('input, textarea').blur(function() {
		
		if($(this).val() == '') { $(this).val($(this).attr('title')); }
	});
	
	
	

    /* Contact form validation, ajax response */
    /*var paraTag = $('input#submit').parent('p');
    $(paraTag).children('input').remove();
    $(paraTag).append('<input type="button" name="submit" id="submit" value="Send it!" class="btn btnPrimary" />');*/

	$('#websiteForm').submit(function() {
		
		$('#formulaire p input[type=submit]').remove();
		$('#formulaire p').append('<span class="loaderIcon">Patientez...</span>'); //Ajout du loader
		
		//Récupération des champs
       // var name = $('#InputName').val(); 
       // var email = $('#InputEmail').val();
       // var message = $('#InputMessage').val();

        /*$.ajax({
            type: 'post',
            url: 'envoyer-email.php',
            data: 'name=' + name + '&email=' + email + '&comments=' + comments,

            success: function(results) {
                $('#form_main img.loaderIcon').fadeOut(5000);
                $('ul#form_response').html(results);
            }
        }); // end ajax*/
        
        //return false;
    });
	
	//$("input[type=file]").uniform();
	// FILE INPUT STYLE
    $("input[type=file]").filestyle({
        imageheight: 31,
        imagewidth: 76,
        width: 150
    });
});