<div id="cookie_banner" class="cookie_banner" style="display: none;">

    <div class="cookie_banner_inner">


        <div class="logo">
            <img src="https://nextcloud.com/wp-content/uploads/2022/10/nextcloud-logo-blue-transparent.svg" alt="Nextcloud" title="Nextcloud">
        </div>

        <div class="description">

            <div class="text">
                <?php echo __("This website is using cookies. By visiting you agree with our <a target='_blank' href='/privacy/'>Privacy Policy</a>", "nextcloud"); ?>
            </div>
            
        </div>

        <div class="buttons">
            <button class="accept_cookie_btn accept_all" id="accept_all_btn"><?php echo __('I understand', 'nextcloud'); ?></button>
        </div>

    </div>

</div>


<script>
function getCookie(name) {
    var dc = document.cookie;
    var prefix = name + "=";
    var begin = dc.indexOf("; " + prefix);
    if (begin == -1) {
        begin = dc.indexOf(prefix);
        if (begin != 0) return null;
    }
    else
    {
        begin += 2;
        var end = document.cookie.indexOf(";", begin);
        if (end == -1) {
        end = dc.length;
        }
    }
    // because unescape has been deprecated, replaced with decodeURI
    //return unescape(dc.substring(begin + prefix.length, end));
    return decodeURI(dc.substring(begin + prefix.length, end));
}


(function() {

   // Config
   var iframe_blocker_text = {};

   var iframe_blocker_text_en = {
    youtube: '<div class="text_overlay"><strong>Embedded YouTube Video</strong>By loading the video, you agree to Youtube\'s privacy policy. <a href="https://www.google.de/intl/de/policies/privacy/" target="_blank">Learn more</a><a class="video-link" href="https://youtu.be/%id%" rel="noopener" target="_blank" title="Watch video on YouTube">Link to the video: https://youtu.be/%id%</a><button title="Watch video on this page" class="youtube_btn unblock_youtube"><i class="fas fa-play"></i> Play video</button><p class="unblock_all">By playing this video, all Youtube videos will be unblocked</p></div>',
    
    vimeo: '<div class="text_overlay"><strong>Embedded Vimeo Video</strong>By loading the video, you agree to Vimeo\'s privacy policy. <a href="https://vimeo.com/privacy" target="_blank">Learn more</a><a class="video-link" href="https://vimeo.com/%id%" rel="noopener" target="_blank" title="Watch video on Vimeo">Link to the video: https://vimeo.com/%id%</a><button title="Watch video on this page" class="vimeo_btn unblock_vimeo"><i class="fas fa-play"></i> Play video</button><p class="unblock_all">By playing this video, all Vimeo videos will be unblocked</p></div>'

   };


   var iframe_blocker_text_de = {

    youtube: '<div class="text_overlay"><strong>Eingebettetes Youtube Video</strong>Durch das Laden des Videos stimmst du der Datenschutzbestimmung von Youtube zu. <a href="https://www.google.de/intl/de/policies/privacy/" target="_blank">Mehr erfahren</a><a class="video-link" href="https://youtu.be/%id%" rel="noopener" target="_blank" title="Watch video on YouTube">Link zum Video: https://youtu.be/%id%</a><button title="Video auf dieser Seite gucken" class="youtube_btn unblock_youtube"><i class="fas fa-play"></i> Video abspielen</button><p class="unblock_all">Durch das Abspielen dieses Videos werden alle Youtube Videos freigeschaltet.</p></div>',
    
    vimeo: '<div class="text_overlay"><strong>Eingebettetes Vimeo Video</strong>Durch das Laden des Videos stimmst du der Datenschutzbestimmung von Vimeo zu. <a href="https://vimeo.com/privacy" target="_blank">Mehr erfahren</a><a class="video-link" href="https://vimeo.com/%id%" rel="noopener" target="_blank" title="Watch video on Vimeo">Link zum Video: https://vimeo.com/%id%</a><button title="Video auf dieser Seite gucken" class="vimeo_btn unblock_vimeo"><i class="fas fa-play"></i> Video abspielen</button><p class="unblock_all">Durch das Abspielen dieses Videos werden alle Vimeo Videos freigeschaltet.</p></div>'

   };

   var iframe_blocker_text_fr = {
    youtube: '<div class="text_overlay"><strong>Vidéo Youtube intégrée</strong>En chargeant la vidéo, vous acceptez la politique de confidentialité de Youtube. <a href="https://www.google.de/intl/de/policies/privacy/" target="_blank">En savoir plus</a><a class="video-link" href="https://youtu.be/%id%" rel="noopener" target="_blank" title="Watch video on YouTube">Lien vers la vidéo: https://youtu.be/%id%</a><button title="Regarder la vidéo sur cette page" class="youtube_btn unblock_youtube"><i class="fas fa-play"></i> Lire la vidéo</button><p class="unblock_all">En regardant cette vidéo, toutes les vidéos Youtube seront débloquées.</p></div>',
    
    vimeo: '<div class="text_overlay"><strong>Vidéo Vimeo intégrée</strong>En chargeant la vidéo, vous acceptez la politique de confidentialité de Vimeo. <a href="https://vimeo.com/privacy" target="_blank">En savoir plus</a><a class="video-link" href="https://vimeo.com/%id%" rel="noopener" target="_blank" title="Regardez la vidéo sur Vimeo">Lien vers la vidéo: https://vimeo.com/%id%</a><button title="Regarder la vidéo sur cette page" class="vimeo_btn unblock_vimeo"><i class="fas fa-play"></i> Lire la vidéo</button><p class="unblock_all">En regardant cette vidéo, toutes les vidéos Vimeo seront débloquées.</p></div>'
   };


   var iframe_blocker_text_es = {
    youtube: '<div class="text_overlay"><strong>Vídeo Youtube incrustado</strong>Al cargar el video, aceptas la política de privacidad de Youtube. <a href="https://www.google.de/intl/de/policies/privacy/" target="_blank">Más información</a><a class="video-link" href="https://youtu.be/%id%" rel="noopener" target="_blank" title="Ver video en youtube">Enlace al vídeo: https://youtu.be/%id%</a><button title="Ver video en esta página" class="youtube_btn unblock_youtube"><i class="fas fa-play"></i> Reproducir vídeo</button><p class="unblock_all">Al reproducir este video, se desbloquearán todos los videos de Youtube</p></div>',
    
    vimeo: '<div class="text_overlay"><strong>Vídeo Vimeo incrustado</strong>By loading the video, you agree to Vimeo\'s privacy policy. <a href="https://vimeo.com/privacy" target="_blank">Más información</a><a class="video-link" href="https://vimeo.com/%id%" rel="noopener" target="_blank" title="Ver video en Vimeo">Enlace al vídeo: https://vimeo.com/%id%</a><button title="Ver video en esta página" class="vimeo_btn unblock_vimeo"><i class="fas fa-play"></i> Reproducir vídeo</button><p class="unblock_all">Al reproducir este video, se desbloquearán todos los videos de Vimeo</p></div>'
   };


   var iframe_blocker_text_it = {

    youtube: '<div class="text_overlay"><strong>Videmo YouTube incorporato</strong>Caricando il video, accetti l\'informativa sulla privacy di Youtube. <a href="https://www.google.de/intl/de/policies/privacy/" target="_blank">Per saperne di più</a><a class="video-link" href="https://youtu.be/%id%" rel="noopener" target="_blank" title="Guarda video su YouTube">Link al video: https://youtu.be/%id%</a><button title="Guarda video su questa pagina" class="youtube_btn unblock_youtube"><i class="fas fa-play"></i> Riproduci video</button><p class="unblock_all">Riproducendo questo video, tutti i video di Youtube verranno sbloccati</p></div>',
    
    vimeo: '<div class="text_overlay"><strong>Video Vimeo incorporato</strong>Caricando il video, accetti l\'informativa sulla privacy di Vimeo. <a href="https://vimeo.com/privacy" target="_blank">Per saperne di più</a><a class="video-link" href="https://vimeo.com/%id%" rel="noopener" target="_blank" title="Guarda video su Vimeo">Link al video: https://vimeo.com/%id%</a><button title="Guarda video su questa pagina" class="vimeo_btn unblock_vimeo"><i class="fas fa-play"></i> Riproduci video</button><p class="unblock_all">Riproducendo questo video, tutti i video di Vimeo verranno sbloccati</p></div>'

   };


   var curr_lang_cookie = Cookies.get('wp-wpml_current_language');
   if(curr_lang_cookie == 'en') {
    iframe_blocker_text = iframe_blocker_text_en;
   } 
   else if (curr_lang_cookie == 'de') {
    iframe_blocker_text = iframe_blocker_text_de;
   }
   else if (curr_lang_cookie == 'it') {
    iframe_blocker_text = iframe_blocker_text_it;
   }
   else if (curr_lang_cookie == 'fr') {
    iframe_blocker_text = iframe_blocker_text_fr;
   }
   else if (curr_lang_cookie == 'es') {
    iframe_blocker_text = iframe_blocker_text_es;
   }
   else {
    iframe_blocker_text = iframe_blocker_text_en;
   }
   

   //if(!Cookies.get('nc_cookie_banner')){
        // everything only when the cookie is not saved

        window.video_iframes = [];
        //window.video_iframes_youtube = [];
        // window.video_iframes_vimeo = [];
        //console.log("window.frames.length: "+window.frames.length);


        var replace_all_iframes = function(){

            var video_iframes_youtube = [];
            var video_iframes_vimeo = [];
            var frames = document.getElementsByTagName("iframe");
            //console.log("frames: "+frames.length);
            var i;

            //for (var i=0, max = window.frames.length - 1; i <= max; i++) {
            for (i = 0; i < frames.length; ++i) {

                var video_frame, video_platform, video_src;
                video_frame = frames[i];
                video_src = video_frame.src;

                // Only process video iframes [youtube|vimeo]
                if (video_src.match(/youtube|vimeo/) == null) {
                    continue;
                }
                
                // import other stuff
                video_platform = video_src.match(/vimeo/) == null ? 'youtube' : 'vimeo';
                //console.log("video_platform: "+video_platform);
                video_frame.parentNode.classList.add(video_platform+"_container");


                //add iframes to the arrays
                //video_iframes.push(video_frame);
 
                if(video_platform == 'youtube') {
                    video_iframes_youtube.push(video_frame);
                }
                if(video_platform == 'vimeo') {
                    video_iframes_vimeo.push(video_frame);
                }


            } //end for
            //console.log("youtube videos on the page: "+video_iframes_youtube.length);
            //console.log("vimeo videos on the page: "+video_iframes_vimeo.length);



            //set youtube first
            var iy;
            for (iy = 0; iy < video_iframes_youtube.length; ++iy) {

                var video_w, video_h, wall, video_id;

                var video_frame = video_iframes_youtube[iy];
                var video_src = video_frame.src;
                var video_platform = 'youtube';

                video_w = video_frame.getAttribute('width');
                video_h = video_frame.getAttribute('height');
                wall = document.createElement('article');

                video_id = video_src.match(/(embed|video)\/([^?\s]*)/)[2];
                wall.setAttribute('class', 'video-wall '+video_platform);

                wall.setAttribute('data-index', iy);
                wall.setAttribute('data-platform', video_platform);

                //set thumbnail image as background
                var thumb_url = 'https://i1.ytimg.com/vi/'+ video_id +'/maxresdefault.jpg';

                
                wall.setAttribute('style', 'width:'+video_w+'px;height:'+video_h+'px; background: url('+thumb_url+'); ');
                wall.innerHTML = iframe_blocker_text[video_platform].replace(/\%id\%/g, video_id);


                // this is where the replacement happens
                //console.log("platform: "+video_platform+" - Video src: "+video_src);

                if(!getCookie('nc_youtube_unblocked')){

                    if(video_frame.parentNode.classList.contains('video-block'))
                    {
                        video_frame.parentNode.classList.add("with-iframe-blocker");
                    }

                    //console.log("youtube cookie not set, so block this iframe");
                    video_frame.parentNode.replaceChild(wall, video_frame);
                }



                //check if button exists
                if (typeof(document.querySelectorAll('button.unblock_youtube')[iy]) != 'undefined' && document.querySelectorAll('button.unblock_youtube')[iy] != null){
                    document.querySelectorAll('button.unblock_youtube')[iy].addEventListener('click', function() {
                    var video_frame = this.parentNode.parentNode;
                    var index = video_frame.getAttribute('data-index');

                    //var new_video_platform = video_frame.classList.contains('vimeo') ? 'vimeo' : 'youtube';
                    //var new_video_platform = video_frame.getAttribute('data-platform');


                        var video_wrapper = video_frame.closest('.wpb_video_wrapper');
                        if (typeof(video_wrapper) != 'undefined' && video_wrapper != null)
                        {
                            // Exists.
                            video_wrapper.classList.remove("with-iframe-blocker");
                        }


                        var all_video_blocks_youtube = document.querySelectorAll('.video-block.youtube_container');
                        all_video_blocks_youtube.forEach(function(item){
                            item.classList.remove("with-iframe-blocker");
                        });

                        


                    //replace current iframe blocker with the relative iframe
                    video_iframes_youtube[index].src = video_iframes_youtube[index].src.replace(/www\.youtube\.com/, 'www.youtube-nocookie.com');
                    video_frame.parentNode.replaceChild(video_iframes_youtube[index], video_frame);


                    //unblock all youtubes
                    if( !getCookie('nc_youtube_unblocked') ) { // load youtube iframe blocker
                        console.log('setting youtube cookie...');
                        Cookies.set('nc_youtube_unblocked', 1, { expires: 30 });
                        
                        var all_video_walls = document.querySelectorAll('.video-wall.youtube');
                        all_video_walls.forEach(function(item){
                            var video_frame = item,
                            index = video_frame.getAttribute('data-index');
                            video_iframes_youtube[index].src = video_iframes_youtube[index].src.replace(/www\.youtube\.com/, 'www.youtube-nocookie.com');
                            video_frame.parentNode.replaceChild(video_iframes_youtube[index], video_frame);
                        });
                        
                    }

                    }, false); //end event listener


                } //endif

            }




            //set vimeo
            var iv;
            for (iv = 0; iv < video_iframes_vimeo.length; ++iv) {

                var video_w, video_h, wall, video_id;

                var video_frame = video_iframes_vimeo[iv];
                var video_src = video_frame.src;
                var video_platform = 'vimeo';


                if(video_frame.getAttribute('width')) {
                    video_w = video_frame.getAttribute('width');
                }else {
                    video_w = "640";
                    //video_frame.offsetWidth;
                }
                
                if(video_frame.getAttribute('height')){
                    video_h = video_frame.getAttribute('height');
                }else {
                    video_h = "200";
                    //video_frame.offsetHeight;
                }
                


                wall = document.createElement('article');

                video_id = video_src.match(/(embed|video)\/([^?\s]*)/)[2];
                wall.setAttribute('class', 'video-wall '+video_platform);

                wall.setAttribute('data-index', iv);
                wall.setAttribute('data-platform', video_platform);

                //set thumbnail image as background
                var thumb_url = 'https://vumbnail.com/'+video_id+'.jpg';

                
                wall.setAttribute('style', 'width:'+video_w+'px;height:'+video_h+'px; background: url('+thumb_url+'); ');
                wall.innerHTML = iframe_blocker_text[video_platform].replace(/\%id\%/g, video_id);


                // this is where the replacement happens
                //console.log("platform: "+video_platform+" - Video src: "+video_src);

                if(!getCookie('nc_vimeo_unblocked')) {
                    //console.log("vimeo cookie not set, so block this iframe");
                    if(video_frame.parentNode.classList.contains('video-block'))
                    {
                        video_frame.parentNode.classList.add("with-iframe-blocker");
                    }


                    video_frame.parentNode.replaceChild(wall, video_frame);
                }



                //check if button exists
                if (typeof(document.querySelectorAll('button.unblock_vimeo')[iv]) != 'undefined' && document.querySelectorAll('button.unblock_vimeo')[iv] != null){
                    document.querySelectorAll('button.unblock_vimeo')[iv].addEventListener('click', function() {
                    var video_frame = this.parentNode.parentNode;
                    var index = video_frame.getAttribute('data-index');

                    //var new_video_platform = video_frame.classList.contains('vimeo') ? 'vimeo' : 'youtube';
                    //var new_video_platform = video_frame.getAttribute('data-platform');

                        var video_wrapper = video_frame.closest('.wpb_video_wrapper');
                        if (typeof(video_wrapper) != 'undefined' && video_wrapper != null)
                        {
                            // Exists.
                            video_wrapper.classList.remove("with-iframe-blocker");
                        }
                    

                        var all_video_blocks_vimeo = document.querySelectorAll('.video-block.vimeo_container');
                        all_video_blocks_vimeo.forEach(function(item){
                            item.classList.remove("with-iframe-blocker");
                        });

                    //replace current iframe blocker with the relative iframe
                    video_frame.parentNode.replaceChild(video_iframes_vimeo[index], video_frame);


                    //unblock all vimeos
                    if( !getCookie('nc_vimeo_unblocked') ){ // load youtube iframe blocker
                        console.log('setting vimeo cookie...');
                        Cookies.set('nc_vimeo_unblocked', 1, { expires: 30 });  
                        var all_video_walls = document.querySelectorAll('.video-wall.vimeo');
                        all_video_walls.forEach(function(item){
                            var video_frame = item,
                            index = video_frame.getAttribute('data-index');
                            video_frame.parentNode.replaceChild(video_iframes_vimeo[index], video_frame);
                        });
                    }
                    

                    }, false); //end event listener


                } //endif



            }




        };

        //iframe blocker for iframes present directly on the page
        document.addEventListener("DOMContentLoaded",replace_all_iframes);

    //}


 })();


 jQuery(document).ready(function ($) {
        //if cookie is not set, show div cookie banner
        if(!Cookies.get('nc_cookie_banner')){
            $('#cookie_banner').show();

        }

        $('#accept_all_btn').click(function(){
            Cookies.set('nc_cookie_banner', 1, { expires: 30 });

            //finally hide the banner
            $('#cookie_banner').hide();

            //console.log("accept btn clicked");
        });
});
</script>