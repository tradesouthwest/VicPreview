/* vicPreview jquery */
/* --------- jQuery capsule ---------- */
(function( $ ) {
    "use strict";
	$(document).ready( function(){
        $(function() {
            $('#wau_file_addon').on('change',function(event){
            var filename = $(this).val();
            var tmppath = URL.createObjectURL(event.target.files[0]);
            //$("img").fadeIn("fast").attr('src',tmppath);

            $('.vicpreview-above-addtocart').attr('alt', filename); 

            $('.vicpreview-above-addtocart').attr('src',tmppath);
            //$('.vicpreview-above-addtocart').draggable();
            //$('.vicpreview-above-addtocart').resize();
                console.log( $('input[type=file]').val());
                return false;
            }); 
        });
    });
})(jQuery);