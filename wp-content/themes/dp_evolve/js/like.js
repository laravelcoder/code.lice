jQuery(document).ready(function() {
	jQuery('body').on('click','.jm-post-like',function(event){
		event.preventDefault();
		heart = jQuery(this);
		post_id = heart.data("post_id");
		heart.html("<i class='icon-heart-2'></i>&nbsp;<i class='icon-spin2 icon-spin'></i>");
		jQuery.ajax({
			type: "post",
			url: ajax_var.url,
			data: "action=jm-post-like&nonce="+ajax_var.nonce+"&jm_post_like=&post_id="+post_id,
			success: function(count){
				if( count.indexOf( "already" ) !== -1 )
				{
					var lecount = count.replace("already","");
					if (lecount == 0)
					{
						var lecount = "0";
					}
					heart.prop('title', '0');
					heart.removeClass("liked");
					heart.html("<i class='icon-heart-2'></i>&nbsp;"+lecount);
				}
				else
				{
					heart.prop('title', 'Unlike');
					heart.addClass("liked");
					heart.html("<i class='icon-heart-filled'></i>&nbsp;"+count);
				}
			}
		});
	});
});
