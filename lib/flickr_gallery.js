$(document).ready(function () {
    /**
     * jquery for the image theme(how images will be displayed on front end)
     */                        
    $('.fancybox').fancybox();
    $('.fancybox-effects-b').fancybox({
        openEffect  : 'none',
        closeEffect	: 'none',

        helpers : {
            title : {
                type : 'over'
            }
        }
    });
    $('.fancybox-buttons').fancybox({
        openEffect  : 'none',
        closeEffect : 'none',

        prevEffect : 'none',
        nextEffect : 'none',
        arrows    : false,
        closeBtn  : false,

        helpers : {
            title : {
                type : 'inside'
            },
            buttons	: {}
        },

        afterLoad : function() {
            this.title = 'Image ' + (this.index + 1) + ' of ' + this.group.length + (this.title ? ' - ' + this.title : '');
        }
    });

    $('.fancybox-thumbs').fancybox({
        prevEffect : 'none',
        nextEffect : 'none',

        closeBtn  : false,
        arrows    : false,
        nextClick : true,

        helpers : {
            thumbs : {
                width  : 50,
                height : 50
            }
        }
    });
    /**
     * jqury script to show selected group/gallery/photoset on page load(on admin panel).
     */
    var answer2 = document.admin_styles.source;
    for (i = 0 ; 1 < answer2.length ; i++)
        if (answer2[i].selected == true)
        {
            var q2 = answer2[i].value
            if(q2=='photoset') {
                document.getElementById('options_left_1').style.display='inline';
                document.getElementById('options_left_2').style.display='none';
                document.getElementById('options_left_3').style.display='none';
                       
            }


            if(q2=='gallery') {
                document.getElementById('options_left_2').style.display='inline';
                document.getElementById('options_left_1').style.display='none';
                document.getElementById('options_left_3').style.display='none';
                       
            }


            if(q2=='group') {
                document.getElementById('options_left_3').style.display='inline';
                document.getElementById('options_left_1').style.display='none';
                document.getElementById('options_left_2').style.display='none';
                        
            }
            if(q2=='photostream') {
                document.getElementById('options_left_3').style.display='none';
                document.getElementById('options_left_1').style.display='none';
                document.getElementById('options_left_2').style.display='none';
                        

            }

                           
        } 
        
        
});
/**
 * source on change function so that respective groups,galleries or photosets can b displayed w.r.t source value(on admin panel)
 */        
function onof(num) {
    if(num=='photoset') {
        document.getElementById('options_left_1').style.display='inline';
        document.getElementById('options_left_2').style.display='none';
        document.getElementById('options_left_3').style.display='none';
                       
    }


    if(num=='gallery') {
        document.getElementById('options_left_2').style.display='inline';
        document.getElementById('options_left_1').style.display='none';
        document.getElementById('options_left_3').style.display='none';
                       
    }


    if(num=='group') {
        document.getElementById('options_left_3').style.display='inline';
        document.getElementById('options_left_1').style.display='none';
        document.getElementById('options_left_2').style.display='none';
                        
    }
    if(num=='photostream') {
        document.getElementById('options_left_3').style.display='none';
        document.getElementById('options_left_1').style.display='none';
        document.getElementById('options_left_2').style.display='none';
                        

    }

}

       

                       


