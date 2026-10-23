import $ from "jquery";
import { load } from "mime";

class SaferRedirect{
    constructor() {
        this.overlay = $('#dvSaferEscape_safety-overlay');
        this.overlayClose = $('#dvSaferEscape_safety-overlay-close');
        this.redirectSettingsIcon = $('#dvSaferEscape_settings-icon');
        this.escapeHatch = $('#dvSaferEscape_safety-exit');
        this.searchEngineOptions = $('.dvSaferEscape_search-options');
        this.saveSettingsButton = $('#dvSaferEscape_save-settings');
        this.events();
        let idleTimer;
        document.addEventListener("visibilitychange", ()=>{
            if (document.visibilityState !== "visible") {
                clearTimeout(idleTimer);
                idleTimer = setTimeout(()=>this.gtfo(), 20000);
            }
        });
        window.onload = ()=>{
            clearTimeout(idleTimer);
            let searchTerm = localStorage.getItem('searchTerm');
            if (searchTerm == "" || searchTerm == null){
                this.openSaferEscapeOverlay();
            }
        }
        window.onmousemove = ()=>clearTimeout(idleTimer);
        window.onmousedown = ()=>clearTimeout(idleTimer);
        window.ontouchstart = ()=>clearTimeout(idleTimer);
        window.onkeydown = ()=>clearTimeout(idleTimer);
        window.onclick = ()=>clearTimeout(idleTimer);
        window.onscroll = ()=>clearTimeout(idleTimer);
    }

    events(){
        this.overlayClose.on('click', this.closeSaferEscapeOverlay.bind(this));
        this.redirectSettingsIcon.on('click', (e)=>{
            e.stopPropagation();
            this.openSaferEscapeOverlay();
        });
        this.escapeHatch.on('click', this.gtfo.bind(this));
        this.searchEngineOptions.on('click', this.selectOption.bind(this));
        this.saveSettingsButton.on('click', this.saveRedirectSettings.bind(this));
    }

    gtfo(){
        let searchDestination = localStorage.getItem('searchEngine') ? "https://" + localStorage.getItem('searchEngine') + ".com" : "https://google.com";
        searchDestination += '/search?q=';
        searchDestination += localStorage.getItem('searchTerm') ? localStorage.getItem('searchTerm').replace(/ /g, '+') : "baby+sloths";
        localStorage.clear();
        console.log('gtfo and going to ' + searchDestination);
    }

    closeSaferEscapeOverlay(){
        this.overlay.addClass('dvSaferEscape_hidden');
        this.redirectSettingsIcon.removeClass('dvSaferEscape_hidden');
    }

    openSaferEscapeOverlay(){
        let searchEngine = localStorage.getItem('searchEngine');
        let searchTerm = localStorage.getItem('searchTerm');
        this.overlay.removeClass('dvSaferEscape_hidden');
        this.redirectSettingsIcon.addClass('dvSaferEscape_hidden');
        if (searchEngine !== '' && searchEngine !== null){
            $('.dvSaferEscape_search-options').removeClass('dvSaferEscape_search-option-selected');
            $(`button[data-name="${searchEngine}"]`).addClass('dvSaferEscape_search-option-selected');
        }
        if (searchTerm !== '' && searchTerm !== null){
            $('#dvSaferSearch_search-input').val(searchTerm);
        }
    }

    selectOption(e){
        this.searchEngineOptions.removeClass('dvSaferEscape_search-option-selected');
        $(e.target).addClass('dvSaferEscape_search-option-selected');
    }

    saveRedirectSettings(){
        let searchEngine = $('.dvSaferEscape_search-option-selected').data('name');
        let searchTerm = $('#dvSaferSearch_search-input').val();
        localStorage.setItem('searchEngine', searchEngine);
        localStorage.setItem('searchTerm', searchTerm);
        this.closeSaferEscapeOverlay();
    }
}

export default SaferRedirect;