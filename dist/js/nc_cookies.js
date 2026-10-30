function setCookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    let expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";secure;" + expires + ";path=/";
}

function getCookie(cname) {
    let name = cname + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for(let i = 0; i <ca.length; i++) {
      let c = ca[i];
      while (c.charAt(0) == ' ') {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
      }
    }
    return "";
}

function sanitizeJSON(unsanitized){	
    return unsanitized.replace(/\\/g, "\\\\").replace(/\n/g, "\\n").replace(/\r/g, "\\r").replace(/\t/g, "\\t").replace(/\f/g, "\\f").replace(/"/g,"\\\"").replace(/'/g,"\\\'").replace(/\&/g, "\\&"); 
}

function imageExists(image_url){
    var http = new XMLHttpRequest();
    http.open('HEAD', image_url, false);
    http.send();
    return http.status != 404;
}

//How to make a JSON call to an URL
const getJSON = async url => {
    const response = await fetch(url);
    if(!response.ok) // check if response worked (no 404 errors etc...)
      throw new Error(response.statusText);
  
    const data = response.json(); // get JSON from the response
    return data; // returns a promise, which resolves to this data value
}

//get-url-parameters-with-javascript
function getAllUrlParams(url) {
    // get query string from url (optional) or window
    var queryString = url ? url.split('?')[1] : window.location.search.slice(1);
  
    // we'll store the parameters here
    var obj = {};
  
    // if query string exists
    if (queryString) {
  
      // stuff after # is not part of query string, so get rid of it
      queryString = queryString.split('#')[0];
  
      // split our query string into its component parts
      var arr = queryString.split('&');
  
      for (var i = 0; i < arr.length; i++) {
        // separate the keys and the values
        var a = arr[i].split('=');
  
        // set parameter name and value (use 'true' if empty)
        var paramName = a[0];
        var paramValue = typeof (a[1]) === 'undefined' ? true : a[1];
  
        // (optional) keep case consistent
        paramName = paramName.toLowerCase();
        if (typeof paramValue === 'string') paramValue = paramValue.toLowerCase();
  
        // if the paramName ends with square brackets, e.g. colors[] or colors[2]
        if (paramName.match(/\[(\d+)?\]$/)) {
  
          // create key if it doesn't exist
          var key = paramName.replace(/\[(\d+)?\]/, '');
          if (!obj[key]) obj[key] = [];
  
          // if it's an indexed array e.g. colors[2]
          if (paramName.match(/\[\d+\]$/)) {
            // get the index value and add the entry at the appropriate position
            var index = /\[(\d+)\]/.exec(paramName)[1];
            obj[key][index] = paramValue;
          } else {
            // otherwise add the value to the end of the array
            obj[key].push(paramValue);
          }
        } else {
          // we're dealing with a string
          if (!obj[paramName]) {
            // if it doesn't exist, create property
            obj[paramName] = paramValue;
          } else if (obj[paramName] && typeof obj[paramName] === 'string'){
            // if property does exist and it's a string, convert it to an array
            obj[paramName] = [obj[paramName]];
            obj[paramName].push(paramValue);
          } else {
            // otherwise add the property
            obj[paramName].push(paramValue);
          }
        }
      }
    }
  
    return obj;
  }

  

var iframe_blocker_text = {};

var iframe_blocker_text_en = {
    youtube: '<div class="text_overlay"><strong>Embedded YouTube Video</strong>By loading the video, you agree to <a href="https://www.google.de/intl/de/policies/privacy/" target="_blank">Youtube\'s privacy policy</a> and <a href="https://nextcloud.com/privacy/" target="_blank">Nextcloud\'s privacy policy</a>. </a><a class="video-link" href="https://youtu.be/%id%" rel="noopener" target="_blank" title="Watch video on YouTube">Link to the video: https://youtu.be/%id%</a><button title="Watch video on this page" class="youtube_btn unblock_youtube"><i class="fas fa-play"></i> Play video</button><p class="unblock_all">By playing this video, all Youtube videos will be unblocked</p></div>',
    
    vimeo: '<div class="text_overlay"><strong>Embedded Vimeo Video</strong>By loading the video, you agree to <a href="https://vimeo.com/privacy" target="_blank">Vimeo\'s privacy policy</a> and <a href="https://nextcloud.com/privacy/" target="_blank">Nextcloud\'s privacy policy</a>.<a class="video-link" href="https://vimeo.com/%id%/%hash%/" rel="noopener" target="_blank" title="Watch video on Vimeo">Link to the video: https://vimeo.com/%id%/%hash%/</a><button title="Watch video on this page" class="vimeo_btn unblock_vimeo"><i class="fas fa-play"></i> Play video</button><p class="unblock_all">By playing this video, all Vimeo videos will be unblocked</p></div>',

    openstreetmap: '<div class="text_overlay"><strong>Embedded OpenStreetMap iframe</strong>By loading the iframe, you agree to <a href="https://wiki.osmfoundation.org/wiki/Privacy_Policy" target="_blank">OpenStreetMap\'s privacy policy</a> and <a href="https://nextcloud.com/privacy/" target="_blank">Nextcloud\'s privacy policy</a>.<a class="video-link" href="%src%" rel="noopener" target="_blank" title="See iframe on OpenStreetMap">Link to the iframe: %src%</a><button title="Unblock iframe on this page" class="openstreetmap_btn unblock_openstreetmap"><i class="fas fa-play"></i> Unblock iframe</button><p class="unblock_all">By unblocking this iframe, all OpenStreetMap iframes will be unblocked.</p></div>'
    
   };


var iframe_blocker_text_de = {
    youtube: '<div class="text_overlay"><strong>Eingebettetes Youtube Video</strong>Durch das Laden des Videos stimmen Sie der Datenschutzbestimmung von <a href="https://www.google.de/intl/de/policies/privacy/" target="_blank">Youtube</a> und <a href="https://nextcloud.com/privacy/" target="_blank">Nextcloud</a> zu. <a class="video-link" href="https://youtu.be/%id%" rel="noopener" target="_blank" title="Video auf YouTube anschauen">Link zum Video: https://youtu.be/%id%</a><button title="Video auf dieser Seite anschauen" class="youtube_btn unblock_youtube"><i class="fas fa-play"></i> Video abspielen</button><p class="unblock_all">Durch das Abspielen dieses Videos werden alle Youtube Videos freigeschaltet.</p></div>',
    
    vimeo: '<div class="text_overlay"><strong>Eingebettetes Vimeo Video</strong>Durch das Laden des Videos stimmen Sie der Datenschutzbestimmung von <a href="https://vimeo.com/privacy" target="_blank">Vimeo</a> und <a href="https://nextcloud.com/privacy/" target="_blank">Nextcloud</a> zu. <a class="video-link" href="https://vimeo.com/%id%/%hash%/" rel="noopener" target="_blank" title="Video auf Vimeo anschauen">Link zum Video: https://vimeo.com/%id%/%hash%/</a><button title="Video auf dieser Seite anschauen" class="vimeo_btn unblock_vimeo"><i class="fas fa-play"></i> Video abspielen</button><p class="unblock_all">Durch das Abspielen dieses Videos werden alle Vimeo Videos freigeschaltet.</p></div>',

    openstreetmap: '<div class="text_overlay"><strong>Eingebettetes OpenStreetMap-iFrame</strong>Durch das Laden des iFrames stimmst du der <a href="https://wiki.osmfoundation.org/wiki/Privacy_Policy" target="_blank">Datenschutzrichtlinie von OpenStreetMap</a> und der <a href="https://nextcloud.com/privacy/" target="_blank">Datenschutzrichtlinie von Nextcloud</a> zu.<a class="video-link" href="%src%" rel="noopener" target="_blank" title="iFrame auf OpenStreetMap anzeigen">Link zum iFrame: %src%</a><button title="iFrame auf dieser Seite entsperren" class="openstreetmap_btn unblock_openstreetmap"><i class="fas fa-play"></i> iFrame entsperren</button><p class="unblock_all">Durch das Entsperren dieses iFrames werden alle OpenStreetMap-iFrames entsperrt.</p></div>'

   };


var iframe_blocker_text_fr = {
    youtube: '<div class="text_overlay"><strong>Vidéo Youtube intégrée</strong>En chargeant la vidéo, vous acceptez la <a href="https://www.google.de/intl/de/policies/privacy/" target="_blank">politique de confidentialité de Youtube</a> et de <a href="https://nextcloud.com/privacy/" target="_blank">Nextcloud</a>. <a class="video-link" href="https://youtu.be/%id%" rel="noopener" target="_blank" title="Regarder la vidéo sur YouTube">Lien vers la vidéo: https://youtu.be/%id%</a><button title="Regarder la vidéo sur cette page" class="youtube_btn unblock_youtube"><i class="fas fa-play"></i> Lire la vidéo</button><p class="unblock_all">En regardant cette vidéo, toutes les vidéos Youtube seront débloquées.</p></div>',
    
    vimeo: '<div class="text_overlay"><strong>Vidéo Vimeo intégrée</strong>En chargeant la vidéo, vous acceptez la <a href="https://vimeo.com/privacy/" target="_blank">politique de confidentialité de Vimeo</a> et de <a href="https://nextcloud.com/privacy/" target="_blank">Nextcloud</a>. <a class="video-link" href="https://vimeo.com/%id%/%hash%/" rel="noopener" target="_blank" title="Regardez la vidéo sur Vimeo">Lien vers la vidéo: https://vimeo.com/%id%/%hash%/</a><button title="Regarder la vidéo sur cette page" class="vimeo_btn unblock_vimeo"><i class="fas fa-play"></i> Lire la vidéo</button><p class="unblock_all">En regardant cette vidéo, toutes les vidéos Vimeo seront débloquées.</p></div>',

    openstreetmap: '<div class="text_overlay"><strong>iFrame OpenStreetMap intégré</strong>En chargeant l’iFrame, vous acceptez la <a href="https://wiki.osmfoundation.org/wiki/Privacy_Policy" target="_blank">politique de confidentialité d’OpenStreetMap</a> ainsi que la <a href="https://nextcloud.com/privacy/" target="_blank">politique de confidentialité de Nextcloud</a>.<a class="video-link" href="%src%" rel="noopener" target="_blank" title="Voir l’iframe sur OpenStreetMap">Lien vers l’iFrame : %src%</a><button title="Débloquer l’iFrame sur cette page" class="openstreetmap_btn unblock_openstreetmap"><i class="fas fa-play"></i> Débloquer l’iFrame</button><p class="unblock_all">En débloquant cet iFrame, tous les iFrames OpenStreetMap seront débloquées.</p></div>'
   };


var iframe_blocker_text_es = {
    youtube: '<div class="text_overlay"><strong>Vídeo Youtube incrustado</strong>Al cargar el video, aceptas la <a href="https://www.google.de/intl/de/policies/privacy/" target="_blank">política de privacidad de Youtube</a> y de <a href="https://nextcloud.com/privacy/" target="_blank">Nextcloud</a>. <a class="video-link" href="https://youtu.be/%id%" rel="noopener" target="_blank" title="Ver video en youtube">Enlace al vídeo: https://youtu.be/%id%</a><button title="Ver video en esta página" class="youtube_btn unblock_youtube"><i class="fas fa-play"></i> Reproducir vídeo</button><p class="unblock_all">Al reproducir este video, se desbloquearán todos los videos de Youtube</p></div>',
    
    vimeo: '<div class="text_overlay"><strong>Vídeo Vimeo incrustado</strong>Al cargar el video, aceptas la <a href="https://vimeo.com/privacy" target="_blank">política de privacidad de Vimeo</a> y de <a href="https://nextcloud.com/privacy/" target="_blank">Nextcloud</a>. <a class="video-link" href="https://vimeo.com/%id%/%hash%/" rel="noopener" target="_blank" title="Ver video en Vimeo">Enlace al vídeo: https://vimeo.com/%id%/%hash%/</a><button title="Ver video en esta página" class="vimeo_btn unblock_vimeo"><i class="fas fa-play"></i> Reproducir vídeo</button><p class="unblock_all">Al reproducir este video, se desbloquearán todos los videos de Vimeo</p></div>',

    openstreetmap: '<div class="text_overlay"><strong>iFrame de OpenStreetMap incrustado</strong>Al cargar el iFrame, aceptas la <a href="https://wiki.osmfoundation.org/wiki/Privacy_Policy" target="_blank">política de privacidad de OpenStreetMap</a> y la <a href="https://nextcloud.com/privacy/" target="_blank">política de privacidad de Nextcloud</a>.<a class="video-link" href="%src%" rel="noopener" target="_blank" title="Ver iFrame en OpenStreetMap">Enlace al iFrame: %src%</a><button title="Desbloquear iFrame en esta página" class="openstreetmap_btn unblock_openstreetmap"><i class="fas fa-play"></i> Desbloquear iFrame</button><p class="unblock_all">Al desbloquear este iFrame, se desbloquearán todos los iFrames de OpenStreetMap.</p></div>'

   };


var iframe_blocker_text_it = {
    youtube: '<div class="text_overlay"><strong>Video YouTube incorporato</strong>Caricando il video, accetti <a href="https://www.google.de/intl/de/policies/privacy/" target="_blank">l\'informativa sulla privacy di Youtube</a> e di <a href="https://nextcloud.com/privacy/" target="_blank">Nextcloud</a>. <a class="video-link" href="https://youtu.be/%id%" rel="noopener" target="_blank" title="Guarda video su YouTube">Link al video: https://youtu.be/%id%</a><button title="Guarda video su questa pagina" class="youtube_btn unblock_youtube"><i class="fas fa-play"></i> Riproduci video</button><p class="unblock_all">Riproducendo questo video, tutti i video di Youtube verranno sbloccati</p></div>',
    
    vimeo: '<div class="text_overlay"><strong>Video Vimeo incorporato</strong>Caricando il video, accetti <a href="https://vimeo.com/privacy" target="_blank">l\'informativa sulla privacy di Vimeo</a> e di <a href="https://nextcloud.com/privacy/" target="_blank">Nextcloud</a>. <a class="video-link" href="https://vimeo.com/%id%/%hash%/" rel="noopener" target="_blank" title="Guarda video su Vimeo">Link al video: https://vimeo.com/%id%/%hash%/</a><button title="Guarda video su questa pagina" class="vimeo_btn unblock_vimeo"><i class="fas fa-play"></i> Riproduci video</button><p class="unblock_all">Riproducendo questo video, tutti i video di Vimeo verranno sbloccati</p></div>',

    openstreetmap: '<div class="text_overlay"><strong>iFrame di OpenStreetMap incorporato</strong>Caricando l’iFrame, accetti la <a href="https://wiki.osmfoundation.org/wiki/Privacy_Policy" target="_blank">privacy policy di OpenStreetMap</a> e la <a href="https://nextcloud.com/privacy/" target="_blank">privacy policy di Nextcloud</a>.<a class="video-link" href="%src%" rel="noopener" target="_blank" title="Vedi l’iFrame su OpenStreetMap">Link all’iFrame: %src%</a><button title="Sblocca l’iFrame su questa pagina" class="openstreetmap_btn unblock_openstreetmap"><i class="fas fa-play"></i> Sblocca iFrame</button><p class="unblock_all">Sbloccando questo iFrame, verranno sbloccati tutti gli iFrame di OpenStreetMap.</p></div>'

};


var curr_lang_cookie = getCookie('wp-wpml_current_language');
iframe_blocker_text = iframe_blocker_text_en; //default

switch(curr_lang_cookie) {
    case 'de':
        iframe_blocker_text = iframe_blocker_text_de;
        break;
    case 'fr':
        iframe_blocker_text = iframe_blocker_text_fr;
        break;
    case 'es':
        iframe_blocker_text = iframe_blocker_text_es;
        break;
    case 'it':
        iframe_blocker_text = iframe_blocker_text_it;
        break;
    default:
        iframe_blocker_text = iframe_blocker_text_en;
}



var getPageIframes = function(platform){
    var video_iframes_youtube = [];
    var video_iframes_vimeo = [];
    var iframes_openstreetmap = [];
    var frames = document.getElementsByTagName("iframe");

    for (var i = 0; i < frames.length; ++i) {
                     var video_frame, video_platform, video_src;
                     video_frame = frames[i];
                     video_src = video_frame.src;
     
                     // Only process video iframes [youtube|vimeo]
                     if (video_src.match(/youtube|vimeo|openstreetmap/) == null) {
                         continue;
                     }
                     
                     //video_platform = video_src.match(/vimeo/) == null ? 'youtube' : 'vimeo';
                     if(video_src.match(/vimeo/) != null) {
                        video_platform = "vimeo";
                     } else if (video_src.match(/youtube/) != null) {
                        video_platform = "youtube";
                     } else if(video_src.match(/openstreetmap/) != null) {
                        video_platform = "openstreetmap";
                     }

                     //console.log("video_platform: "+video_platform);
                     video_frame.parentNode.classList.add(video_platform+"_container");
     
                     //add iframes to the arrays
                     if(video_platform == 'youtube') {
                         video_iframes_youtube.push(video_frame);
                     }
                     if(video_platform == 'vimeo') {
                         video_iframes_vimeo.push(video_frame);
                     }
                     if(video_platform == 'openstreetmap') {
                        iframes_openstreetmap.push(video_frame);
                    }
     
    }

    if(platform=='youtube')
        return video_iframes_youtube;

    if(platform=='vimeo')
        return video_iframes_vimeo;

    if(platform=='openstreetmap')
        return iframes_openstreetmap;
}



var block_all_iframes = function(platform = 'all') {
            window.video_iframes = [];

             var replace_all_iframes = function(){

                var video_iframes_youtube = getPageIframes('youtube');
                var video_iframes_vimeo = getPageIframes('vimeo');
                var video_iframes_openstreetmap = getPageIframes('openstreetmap');

                var thumb_root = "https://nextcloud.com/wp-content/themes/nextcloud-theme/dist/img/thumbs/";

                
                //set youtube first
                if( platform == 'all' || platform == 'youtube'){
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
                     var thumb_url = thumb_root + video_id +'.jpg';

                     wall.setAttribute('style', 'width:'+video_w+'px;height:'+video_h+'px;');
                     if(imageExists(thumb_url)) {
                        wall.setAttribute('style', wall.getAttribute('style')+'; background: url('+thumb_url+');');
                     }

                     wall.innerHTML = iframe_blocker_text[video_platform].replace(/\%id\%/g, video_id);
     
                     // this is where the replacement happens
                    if( !getCookie('nc_cookie_banner') || ( getCookie('nc_cookie_banner') && !JSON.parse(getCookie('nc_cookie_banner')).external_media.youtube )  ) {
                            //console.log("youtube: "+JSON.parse(nc_cookie_banner_saved).external_media.youtube);
                            //all should be blocked
                            if(video_frame.parentNode.classList.contains('video-block'))
                            {
                                video_frame.parentNode.classList.add("with-iframe-blocker");
                            }
                            //console.log("youtube cookie not set, so block this iframe");
                            video_frame.parentNode.replaceChild(wall, video_frame);
                    }
     
     
     
                     //check if button exists
                     if (typeof(document.querySelectorAll('button.unblock_youtube')[iy]) != 'undefined' && document.querySelectorAll('button.unblock_youtube')[iy] != null)
                     {
                        
                        var unblockAllYoutubes = function () {
                            if( !getCookie('nc_cookie_banner') || ( getCookie('nc_cookie_banner') && !JSON.parse(getCookie('nc_cookie_banner')).external_media.youtube )  ) {
                                //console.log('setting youtube cookie...');
                                //Cookies.set('nc_youtube_unblocked', 1, { expires: 30 });
                                //console.log('youtube blocker gets replaced with iframe...');
                                //console.log("nc_vimeo_unblocked: "+getCookie('nc_vimeo_unblocked'));


                                //update cookie banner preferences
                                if(getCookie('nc_cookie_banner')) {
                                    var nc_cookies_banner_saved = JSON.parse(getCookie('nc_cookie_banner'));
                                } else {
                                    //set defaults
                                    var nc_cookies_banner_saved = {
                                        essentials: true,
                                        convenience: false,
                                        statistics: {
                                            matomo: true
                                        },
                                        external_media: {
                                            youtube: false,
                                            vimeo: false
                                        }
                                    };
                                }
                                nc_cookies_banner_saved.external_media.youtube = true;

                                //set matomo consent
                                nc_cookies_banner_saved.statistics.matomo = true;
                                //_paq.push(['setCookieConsentGiven']);

                                setCookie('nc_cookie_banner', JSON.stringify(nc_cookies_banner_saved), 30);
                                document.getElementById("accept_youtube").checked = true;

                                var all_video_blocks_youtube = document.querySelectorAll('.video-block.youtube_container');
                                all_video_blocks_youtube.forEach(function(item){
                                    item.classList.remove("with-iframe-blocker");
                                });

                                var all_video_walls = document.querySelectorAll('.video-wall.youtube');
                                all_video_walls.forEach(function(item){
                                            var video_frame = item,
                                            index = video_frame.getAttribute('data-index');
                                            video_iframes_youtube[index].src = video_iframes_youtube[index].src.replace(/www\.youtube\.com/, 'www.youtube-nocookie.com');
                                            video_frame.parentNode.replaceChild(video_iframes_youtube[index], video_frame);
                                });

                            }
                        }
                        
                        var unblockThisYoutube = function() {
                            var video_frame = this.parentNode.parentNode;
                            var index = video_frame.getAttribute('data-index');
                            
                            var video_wrapper = video_frame.closest('.wpb_video_wrapper');
                            if (typeof(video_wrapper) != 'undefined' && video_wrapper != null)
                            {
                                // Exists.
                                 video_wrapper.classList.remove("with-iframe-blocker");
                            }
        
                            //replace current iframe blocker with the relative iframe
                            video_iframes_youtube[index].src = video_iframes_youtube[index].src.replace(/www\.youtube\.com/, 'www.youtube-nocookie.com');
                            video_frame.parentNode.replaceChild(video_iframes_youtube[index], video_frame);

                            //unblock all youtubes
                            unblockAllYoutubes();
                        }         

                        document.querySelectorAll('button.unblock_youtube')[iy].addEventListener('click', unblockThisYoutube, false); //end event listener

                        //event listener cookie banner
                        document.querySelector('#accept_all_btn').addEventListener('click', unblockAllYoutubes, false); //end event listener

                        //event listener cookie banner
                        document.querySelector('#save_settings').addEventListener('click', function(){
                            if (document.getElementById('accept_youtube').checked) {
                                unblockAllYoutubes();
                            }
                        }, false); //end event listener

                     } //endif
                }
                }

     
                 //set vimeo
                 if( platform == 'all' || platform == 'vimeo'){
                 var iv;
                 for (iv = 0; iv < video_iframes_vimeo.length; ++iv) {
     
                     var video_w, video_h, wall, video_id, video_hash;
     
                     var video_frame = video_iframes_vimeo[iv];
                     var video_src = video_frame.src;
                     var video_platform = 'vimeo';
                     wall = document.createElement('article');
     
                     var elem_video_w = video_frame.parentNode.offsetWidth;
                     //console.log("elem width: "+elem_video_w);
                
                     if(video_frame.getAttribute('width')) {
                         video_w = video_frame.getAttribute('width');
                     }else {
                         video_w = "1280";
                         //video_frame.offsetWidth;
                     }
                     
                     if(video_frame.getAttribute('height')){
                         video_h = video_frame.getAttribute('height');
                     }else {
                         //video_h = "720";
                         video_h = (elem_video_w * 9) / 16;
                         //console.log("video_h: "+video_h);
                         //video_frame.offsetHeight;
                     }
                     
     
     
                     
                     video_id = video_src.match(/(embed|video)\/([^?\s]*)/)[2];
                     //get vimeo video hash from URL, useful for external link preview

                     if(getAllUrlParams(video_src).h) {
                        video_hash = getAllUrlParams(video_src).h;
                     } else {
                        video_hash = '#';
                     }
                    //console.log("video_hash for ID: "+video_id+" : "+video_hash);

                     wall.setAttribute('class', 'video-wall '+video_platform);
     
                     wall.setAttribute('data-index', iv);
                     wall.setAttribute('data-platform', video_platform);
                     wall.setAttribute('style', 'width:'+video_w+'px; /*height:'+video_h+'px;*/ ');

     
                    var thumb_url = thumb_root + video_id +'.jpg';
                    //wall.setAttribute('style', 'width:'+video_w+'px;height:'+video_h+'px; background: url('+thumb_url+'); ');

                    wall.setAttribute('style', 'width:'+video_w+'px;height:'+video_h+'px;');
                     if(imageExists(thumb_url)) {
                        wall.setAttribute('style', wall.getAttribute('style')+'; background: url('+thumb_url+');');
                     }

                    var replacements = {
                            "%id%": video_id,
                            "%hash%": video_hash
                    }; 
                     //replace id in text
                     wall.innerHTML = iframe_blocker_text[video_platform].replace(/%\w+%/g, function(all) {
                        return replacements[all] || all;
                     });


     
                     // this is where the replacement happens
                     //if(!getCookie('nc_vimeo_unblocked')) {
                    if( !getCookie('nc_cookie_banner') || ( getCookie('nc_cookie_banner') && !JSON.parse(getCookie('nc_cookie_banner')).external_media.vimeo )  ) {    
                         //console.log("vimeo cookie not set, so block this iframe");
                         if(video_frame.parentNode.classList.contains('video-block'))
                         {
                             video_frame.parentNode.classList.add("with-iframe-blocker");
                         }
                         video_frame.parentNode.replaceChild(wall, video_frame);
                    }
     
     
     
                     //check if button exists
                     if (typeof(document.querySelectorAll('button.unblock_vimeo')[iv]) != 'undefined' && document.querySelectorAll('button.unblock_vimeo')[iv] != null){
                        

                        var unblockAllVimeos = function() {

                            //if( !getCookie('nc_vimeo_unblocked') ){
                            if( !getCookie('nc_cookie_banner') || ( getCookie('nc_cookie_banner') && !JSON.parse(getCookie('nc_cookie_banner')).external_media.vimeo )  ) {  
                                //console.log('setting vimeo cookie...');
                                //Cookies.set('nc_vimeo_unblocked', 1, { expires: 30 });

                                //update cookie banner preferences
                                if(getCookie('nc_cookie_banner')) {
                                    var nc_cookies_banner_saved = JSON.parse(getCookie('nc_cookie_banner'));
                                } else {
                                    //set defaults
                                    var nc_cookies_banner_saved = {
                                        essentials: true,
                                        convenience: false,
                                        statistics: {
                                            matomo: true
                                        },
                                        external_media: {
                                            youtube: false,
                                            vimeo: false
                                        }
                                    };
                                }
                                nc_cookies_banner_saved.external_media.vimeo = true;
                                
                                //set matomo consent and load code
                                nc_cookies_banner_saved.statistics.matomo = true;
                                //_paq.push(['setCookieConsentGiven']);
                                

                                setCookie('nc_cookie_banner', JSON.stringify(nc_cookies_banner_saved), 30);
                                document.getElementById("accept_vimeo").checked = true;


                                var all_video_walls = document.querySelectorAll('.video-wall.vimeo');
                                all_video_walls.forEach(function(item){
                                    var video_frame = item,
                                    index = video_frame.getAttribute('data-index');
                                    video_frame.parentNode.replaceChild(video_iframes_vimeo[index], video_frame);
                                });


                                //var all_video_blocks_vimeo = document.querySelectorAll('.video-block.vimeo_container');
                                var all_video_blocks_vimeo = document.querySelectorAll('.vimeo_container');
                                all_video_blocks_vimeo.forEach(function(item){
                                    item.classList.remove("with-iframe-blocker");
                                });

                            }
                        };

                        var unblockThisVimeo = function() {
                            var video_frame = this.parentNode.parentNode;
                            var index = video_frame.getAttribute('data-index');
    
                            var video_wrapper = video_frame.closest('.wpb_video_wrapper');
                            if (typeof(video_wrapper) != 'undefined' && video_wrapper != null)
                                {
                                    // Exists.
                                    video_wrapper.classList.remove("with-iframe-blocker");
                                }
                                

        
                            //replace current iframe blocker with the relative iframe
                            video_frame.parentNode.replaceChild(video_iframes_vimeo[index], video_frame);
        
        
                            //unblock all vimeos
                            unblockAllVimeos();
                        }


                        document.querySelectorAll('button.unblock_vimeo')[iv].addEventListener('click', unblockThisVimeo, false); //end event listener
                        
                        //event listener cookie banner
                        document.querySelector('#accept_all_btn').addEventListener('click', unblockAllVimeos, false); //end event listener


                        //event listener cookie banner
                        document.querySelector('#save_settings').addEventListener('click', function(){
                            if (document.getElementById('accept_vimeo').checked) {
                                unblockAllVimeos();
                            }
                        }, false); //end event listener

     
                    } //endif
     
                 }
                }



                //set openstreetmap
                if( platform == 'all' || platform == 'openstreetmap'){
                    var io;
                    for (io = 0; io < video_iframes_openstreetmap.length; ++io) {

                        var video_w, video_h, wall, video_url, video_hash;

                        var video_frame = video_iframes_openstreetmap[io];
                        var video_src = video_frame.src;
                        //console.log("video_src for Openstreetmap:"+video_src);


                        var video_platform = 'openstreetmap';
                        wall = document.createElement('article');

                        var elem_video_w = video_frame.parentNode.offsetWidth;
                        //console.log("elem width: "+elem_video_w);
                
                        if(video_frame.getAttribute('width')) {
                            video_w = video_frame.getAttribute('width');
                        }else {
                            video_w = "1280";
                            //video_frame.offsetWidth;
                        }
                        
                        if(video_frame.getAttribute('height')){
                            video_h = video_frame.getAttribute('height');
                        }else {
                            //video_h = "720";
                            video_h = (elem_video_w * 9) / 16;
                            //console.log("video_h: "+video_h);
                            //video_frame.offsetHeight;
                        }

                        /*
                        if(getAllUrlParams(video_src).h) {
                            video_url = getAllUrlParams(video_src).src;
                        } else {
                            video_url = '';
                        }
                        */
                        video_url = video_src;
                        

                        wall.setAttribute('class', 'video-wall '+video_platform);

                        wall.setAttribute('data-index', io);
                        wall.setAttribute('data-platform', video_platform);
                        wall.setAttribute('style', 'width:'+video_w+'px; /*height:'+video_h+'px;*/ ');
                     
                        var thumb_url = 'https://nextcloud.com/wp-content/themes/nextcloud-theme/dist/img/thumbs/osm_default.png';

                        wall.setAttribute('style', 'width:'+video_w+'px;height:'+video_h+'px;');
                        wall.setAttribute('style', wall.getAttribute('style')+'; background: url('+thumb_url+');');
                       
                        var replacements = {
                                "%src%": video_url
                        }; 
                        //replace parameters in text
                        wall.innerHTML = iframe_blocker_text[video_platform].replace(/%\w+%/g, function(all) {
                        return replacements[all] || all;
                        });



                        // this is where the replacement happens
                    if( !getCookie('nc_cookie_banner') || ( getCookie('nc_cookie_banner') && !JSON.parse(getCookie('nc_cookie_banner')).external_media.openstreetmap )  ) {    
                            //console.log("Openstreetmap cookie not set, so block this iframe");
                            if(video_frame.parentNode.classList.contains('video-block'))
                            {
                                video_frame.parentNode.classList.add("with-iframe-blocker");
                            }
                            video_frame.parentNode.replaceChild(wall, video_frame);
                    }



                        //check if button exists
                        if (typeof(document.querySelectorAll('button.unblock_openstreetmap')[io]) != 'undefined' && document.querySelectorAll('button.unblock_openstreetmap')[io] != null){
                        

                        var unblockAllOpenstreetmaps = function() {

                            if( !getCookie('nc_cookie_banner') || ( getCookie('nc_cookie_banner') && !JSON.parse(getCookie('nc_cookie_banner')).external_media.openstreetmap )  ) {  
                                //console.log('setting Openstreetmap cookie...');

                                //update cookie banner preferences
                                if(getCookie('nc_cookie_banner')) {
                                    var nc_cookies_banner_saved = JSON.parse(getCookie('nc_cookie_banner'));
                                } else {
                                    //set defaults
                                    var nc_cookies_banner_saved = {
                                        essentials: true,
                                        convenience: false,
                                        statistics: {
                                            matomo: true
                                        },
                                        external_media: {
                                            youtube: false,
                                            vimeo: false,
                                            openstreetmap: false
                                        }
                                    };
                                }
                                nc_cookies_banner_saved.external_media.openstreetmap = true;
                                
                                //set matomo consent and load code
                                nc_cookies_banner_saved.statistics.matomo = true;
                                

                                setCookie('nc_cookie_banner', JSON.stringify(nc_cookies_banner_saved), 30);
                                document.getElementById("accept_openstreetmap").checked = true;


                                var all_video_walls = document.querySelectorAll('.video-wall.openstreetmap');
                                all_video_walls.forEach(function(item){
                                    var video_frame = item,
                                    index = video_frame.getAttribute('data-index');
                                    video_frame.parentNode.replaceChild(video_iframes_openstreetmap[index], video_frame);
                                });


                                var all_video_blocks_openstreetmap = document.querySelectorAll('.openstreetmap_container');
                                all_video_blocks_openstreetmap.forEach(function(item){
                                    item.classList.remove("with-iframe-blocker");
                                });

                            }
                        };

                        var unblockThisOpenstreetmap = function() {
                            var video_frame = this.parentNode.parentNode;
                            var index = video_frame.getAttribute('data-index');

                            var video_wrapper = video_frame.closest('.wpb_video_wrapper');
                            if (typeof(video_wrapper) != 'undefined' && video_wrapper != null)
                                {
                                    // Exists.
                                    video_wrapper.classList.remove("with-iframe-blocker");
                                }
                                

                            //replace current iframe blocker with the relative iframe
                            video_frame.parentNode.replaceChild(video_iframes_openstreetmap[index], video_frame);


                            //unblock all OpenStreetMap
                            unblockAllOpenstreetmaps();
                        }


                        document.querySelectorAll('button.unblock_openstreetmap')[io].addEventListener('click', unblockThisOpenstreetmap, false); //end event listener
                        
                        //event listener cookie banner
                        document.querySelector('#accept_all_btn').addEventListener('click', unblockAllOpenstreetmaps, false); //end event listener

                        //event listener cookie banner
                        document.querySelector('#save_settings').addEventListener('click', function(){
                            if (document.getElementById('accept_openstreetmap').checked) {
                                unblockAllOpenstreetmaps();
                            }
                        }, false); //end event listener


                    } //endif

                    }
                }


            };

     
             //iframe blocker for iframes present directly on the page
            document.addEventListener("DOMContentLoaded",replace_all_iframes);
};
block_all_iframes('all');





//used for the magnific popup iframes - it blocks the iframes from the magnific popup links
var replace_this_iframe = function(element_item, popup_element){
    //console.log("function parsing..");
    var video_frame, wall, video_platform, video_src, video_id, video_w, video_h, video_hash;

    var video_iframes_youtube = [];
    var video_iframes_vimeo = [];
    var video_iframes_openstreetmap = [];

    var frames = document.getElementsByTagName("iframe");
    //console.log("frames length inside popup: "+frames.length);

    for (i = 0; i < frames.length; ++i) {
        var video_frame, video_platform, video_src;
        video_frame = frames[i];
        video_src = video_frame.src;

        // Only process video iframes [youtube|vimeo]
        if (video_src.match(/youtube|vimeo|openstreetmap/) == null) {
            continue;
        }

        //video_platform = video_src.match(/vimeo/) == null ? 'youtube' : 'vimeo';
        if(video_src.match(/vimeo/) != null) {
            video_platform = "vimeo";
         } else if (video_src.match(/youtube/) != null) {
            video_platform = "youtube";
         } else if(video_src.match(/openstreetmap/) != null) {
            video_platform = "openstreetmap";
         }


        //console.log("video_platform: "+video_platform);
        video_frame.parentNode.classList.add(video_platform+"_container");

        if(video_platform == 'youtube') {
            video_iframes_youtube.push(video_frame);
        }
        if(video_platform == 'vimeo') {
            video_iframes_vimeo.push(video_frame);
        }
        if(video_platform == 'openstreetmap') {
            video_iframes_openstreetmap.push(video_frame);
        }


    } //end for

    video_frame = element_item;
    var this_video_iframe = element_item;
    video_src = video_frame.src;
    //console.log("this_video_iframe src: "+ element_item.src);


    // Only process video iframes [youtube|vimeo]
    if (video_src.match(/youtube|vimeo|openstreetmap/) == null) {
        //continue;
    } else {
        //video_iframes.push(video_frame);
        video_w = video_frame.getAttribute('width');
        video_h = video_frame.getAttribute('height');
        wall = document.createElement('article');

        //video_platform = video_src.match(/vimeo/) == null ? 'youtube' : 'vimeo';
        if(video_src.match(/vimeo/) != null) {
            video_platform = "vimeo";
         } else if (video_src.match(/youtube/) != null) {
            video_platform = "youtube";
         } else if(video_src.match(/openstreetmap/) != null) {
            video_platform = "openstreetmap";
         }
         //console.log("video_platform: "+video_platform);



         if( video_platform == 'youtube' || video_platform == 'vimeo' ) {
            video_id = video_src.match(/(embed|video)\/([^?\s]*)/)[2];
         }
        
        wall.setAttribute('class', 'video-wall');
        var thumb_root = "https://nextcloud.com/wp-content/themes/nextcloud-theme/dist/img/thumbs/";

        var thumb_url = thumb_root + video_id +'.jpg';
        
        //set thumbnail image as background
        if( video_platform == 'youtube' ) {
            //var thumb_url = 'https://i1.ytimg.com/vi/'+ video_id +'/maxresdefault.jpg';
            wall.innerHTML = iframe_blocker_text[video_platform].replace(/\%id\%/g, video_id);
        }

        if( video_platform == 'vimeo') {
            if(getAllUrlParams(video_src).h) {
                video_hash = getAllUrlParams(video_src).h;
             } else {
                video_hash = '#';
             }

                var replacements = {
                        "%id%": video_id,
                        "%hash%": video_hash
                }; 
                 //replace id in text
                 wall.innerHTML = iframe_blocker_text[video_platform].replace(/%\w+%/g, function(all) {
                    return replacements[all] || all;
                 });
        }


        if( video_platform == 'openstreetmap' ) {
            //replace with a default image for the blocker - aspect ratio 16/9
            var thumb_url = 'https://nextcloud.com/wp-content/themes/nextcloud-theme/dist/img/thumbs/osm_default.png';
            //replace "src" placeholder in the wall element text
            wall.innerHTML = iframe_blocker_text[video_platform].replace(/\%src\%/g, video_src);
        }


        //add BG image
        wall.setAttribute('style', 'width:'+video_w+'px;height:'+video_h+'px;');
        if(imageExists(thumb_url)) {
                //console.log('image exists!');
                wall.setAttribute('style', wall.getAttribute('style')+'; background: url('+thumb_url+');');
        } else {
                //console.log('image does not exist!');
        }


        //this is where replacement happens
        if( video_platform == 'youtube' ) {
            //if(!getCookie('nc_youtube_unblocked')){
            if( !getCookie('nc_cookie_banner') || ( getCookie('nc_cookie_banner') && !JSON.parse(getCookie('nc_cookie_banner')).external_media.youtube )  ) {  

                video_frame.parentNode.replaceChild(wall, video_frame);
                
            }
        }

        if( video_platform == 'vimeo' ) {
            if( !getCookie('nc_cookie_banner') || ( getCookie('nc_cookie_banner') && !JSON.parse(getCookie('nc_cookie_banner')).external_media.vimeo )  ) {  

                video_frame.parentNode.replaceChild(wall, video_frame);  
            }
        }

        if( video_platform == 'openstreetmap' ) {
            if( !getCookie('nc_cookie_banner') || ( getCookie('nc_cookie_banner') && !JSON.parse(getCookie('nc_cookie_banner')).external_media.openstreetmap )  ) {  

                video_frame.parentNode.replaceChild(wall, video_frame);  
            }
        }



        //unblock iframe when click on button
        jQuery(popup_element).find('.video-wall button').click(function(){
            var video_frame = this.parentNode;
            this_video_iframe.src = this_video_iframe.src.replace(/www\.youtube\.com/, 'www.youtube-nocookie.com');
            video_frame.parentNode.replaceChild(this_video_iframe, video_frame);


            if( video_platform == 'youtube' ) {
                if( !getCookie('nc_cookie_banner') || ( getCookie('nc_cookie_banner') && !JSON.parse(getCookie('nc_cookie_banner')).external_media.youtube )  ) {  
                    //Cookies.set('nc_youtube_unblocked', 1, { expires: 30 });

                    if(getCookie('nc_cookie_banner')) {
                        var nc_cookies_banner_saved = JSON.parse(getCookie('nc_cookie_banner'));
                    } else {
                        //set defaults
                        var nc_cookies_banner_saved = {
                            essentials: true,
                            convenience: false,
                            statistics: {
                                matomo: false
                            },
                            external_media: {
                                youtube: false,
                                vimeo: false,
                                openstreetmap: false
                            }
                        };
                    }
                    
                    nc_cookies_banner_saved.external_media.youtube = true;
                    setCookie('nc_cookie_banner', JSON.stringify(nc_cookies_banner_saved), 30);
                    //console.log("cookie youtube set!");
                    document.getElementById("accept_youtube").checked = true;

                }
            }

            if( video_platform == 'vimeo' ) {
                if( !getCookie('nc_cookie_banner') || ( getCookie('nc_cookie_banner') && !JSON.parse(getCookie('nc_cookie_banner')).external_media.vimeo )  ) {  

                    if(getCookie('nc_cookie_banner')) {
                        var nc_cookies_banner_saved = JSON.parse(getCookie('nc_cookie_banner'));
                    } else {
                        //set defaults
                        var nc_cookies_banner_saved = {
                            essentials: true,
                            convenience: false,
                            statistics: {
                                matomo: false
                            },
                            external_media: {
                                youtube: false,
                                vimeo: false,
                                openstreetmap: false
                            }
                        };
                    }
                    
                    nc_cookies_banner_saved.external_media.vimeo = true;
                    setCookie('nc_cookie_banner', JSON.stringify(nc_cookies_banner_saved), 30);
                    document.getElementById("accept_vimeo").checked = true;
                }
            }


            if( video_platform == 'openstreetmap' ) {
                if( !getCookie('nc_cookie_banner') || ( getCookie('nc_cookie_banner') && !JSON.parse(getCookie('nc_cookie_banner')).external_media.openstreetmap )  ) {  

                    if(getCookie('nc_cookie_banner')) {
                        var nc_cookies_banner_saved = JSON.parse(getCookie('nc_cookie_banner'));
                    } else {
                        //set defaults
                        var nc_cookies_banner_saved = {
                            essentials: true,
                            convenience: false,
                            statistics: {
                                matomo: false
                            },
                            external_media: {
                                youtube: false,
                                vimeo: false,
                                openstreetmap: false
                            }
                        };
                    }
                    
                    nc_cookies_banner_saved.external_media.openstreetmap = true;
                    setCookie('nc_cookie_banner', JSON.stringify(nc_cookies_banner_saved), 30);
                    document.getElementById("accept_openstreetmap").checked = true;
                }
            }

        });
        

    }
};