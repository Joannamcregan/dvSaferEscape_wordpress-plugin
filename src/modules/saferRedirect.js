import $ from "jquery";
import { load } from "mime";

class SaferRedirect{
    constructor() {
        this.overlay = $('#dvSaferEscape_safety-overlay');
        this.overlayClose = $('#dvSaferEscape_safety-overlay-close');
        this.events();
        document.addEventListener("visibilitychange", ()=>{
            if (document.visibilityState !== "visible") {
                this.gtfo();
            }
        });
        window.onload = ()=>{
            console.log('load on');
            let searchTerm = localStorage.getItem('searchTerm');
            console.log('search term is ' + searchTerm);
            if (searchTerm == "" || searchTerm == null){
                this.openSaferEscapeOverlay();
            }
        }
    }

    events(){
        this.overlayClose.on('click', this.closeSaferEscapeOverlay.bind(this));
    }

    gtfo(){
        let searchDestination = localStorage.getItem('searchEngine') ? "https://" + localStorage.getItem('searchEngine') + ".com" : "https://google.com";
        searchDestination += '/search?q=';
        searchDestination += localStorage.getItem('searchString') ? localStorage.getItem('searchString').replace(/ /g, '+') : "baby+sloths";
        console.log('gtfo and going to ' + searchDestination);
    }

    closeSaferEscapeOverlay(){
        this.overlay.addClass('dvSaferEscape_hidden');
    }

    openSaferEscapeOverlay(){
        this.overlay.removeClass('dvSaferEscape_hidden');
    }
}

export default SaferRedirect;