$(document).ready(function() {

	/* Twitter initiates. Enter your username here. */
	$(".tweeter_widget").tweet({
            username: "designsentry",
            join_text: "auto",
            avatar_size: 30,
            count: 4,
            auto_join_text_default: ",",
            auto_join_text_ed: ",",
            auto_join_text_ing: ",",
            auto_join_text_reply: "DS replied to",
            auto_join_text_url: "DS was checking out",
            loading_text: "loading tweets..."
    });	
	
	/* Social networking icons fade on hover */
	$('#footer .icons li img').hover(function(){
		$(this).stop().animate({"opacity": "0.45"},{queue:false,duration:300});
	}, function() {
		$(this).stop().animate({"opacity": "1"},{queue:false,duration:300});
	});
});