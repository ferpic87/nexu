
//**<script type="text/javascript">**//
var base_title;
$(document).ready(function () {
    if($('#live_notifications_link').length) {
    	var pos = $('#live_notifications_link').offset();
    	//var pos = $('#live_notifications_link').offset();
	//    alert(pos.left);
    	base_title = document.title;

    	//show the menu directly over the placeholder
    	$("#live_notifications").css({
    	    top: (24 + pos.top) + "px",
    	    left: (pos.left) + "px"
    	}).show();
    }

    $("#live_notifications").hide();                           
    
    $('#live_notifications_loader').show();	
    $("#live_notifications_result").load("<?php echo $vars['url']; ?>live_notifications/ajax",function(){
        $('#live_notifications_loader').hide(); // remove the loading gif
    }); 

    $("#live_notifications_link").click(function(){ 
        $("#live_notifications").toggle($('#live_notifications').css('display') == 'none');
        var num = parseInt($("#count_unread_notifications").html());
        if(num>0){
            elgg.action('live_notifications/read_all', function(response) {

            });
        }
        $("#count_unread_notifications").html(0);
        $("#count_unread_notifications").hide(); 
        $('.elgg-icon-live_notifications').addClass("elgg-icon-live_notifications-selected");
        return false;   
    });

    //Interval update counter: 10 second(10000)
   setInterval(function() {
        //$.ajax('/mod/live_notifications/count', function(response) {
        elgg.action('live_notifications/refresh_count', function(response) {

            var num = parseInt($("#count_unread_notifications").html());
            var new_count = parseInt(response.output);
	    if(new_count > 0)
		document.title = base_title +" ("+new_count+")";
            if(new_count>num){
                $("#count_unread_notifications").html(new_count);
                $("#count_unread_notifications").show();
                $('#live_notifications_loader').show(); 
                $("#live_notifications_result").load("<?php echo $vars['url']; ?>live_notifications/ajax",function(){
                    $('#live_notifications_loader').hide(); // remove the loading gif
                    elgg.system_message('<?php echo elgg_echo('live_notifications:new_notification'); ?>');
                });  
            }
            else if(new_count==0){
                $("#count_unread_notifications").hide();                
            }
        });
    }, 30000);
    
    $(document).click(function(event) { 
        if($(event.target).parents().index($('#live_notifications')) == -1) {
            if($('#live_notifications').is(":visible")) {
                $('#live_notifications').hide();
                $('.elgg-icon-live_notifications').removeClass("elgg-icon-live_notifications-selected");
                $('.notifications_content_item').removeClass("new_notification");                
            }
        }
	document.title = base_title;        
    });
	

});

