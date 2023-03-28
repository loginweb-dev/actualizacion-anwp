( function( $ ) {
	
	$( document ).ready( function() {
		
		function getjugadores(param) {
		  console.log('Blah triggered: ', param);
		} 
		// Ensure the wp.media object is set, otherwise we can't do anything.
	
		$('#miclass').on('change', function() {
			var miget = ""
			console.log(this.value)
			if(this.value === "equipo"){
			   miget = "/wp-json/wp/v2/anwp_clubs"
			}else{
				miget = "/wp-json/wp/v2/anwp_player"
			}
			$.ajax({
				url: miget,
				success:function (response) {
					console.log(response)
					$("#milist tbody tr").remove()
					for(var i=0; i<response.length; i++){
						console.log(response[i].title.rendered)
						$("#milist tbody").append("<tr><td>"+response[i].title.rendered+"<td></tr>");
					}
				}
			});
		});
	});
})( jQuery );