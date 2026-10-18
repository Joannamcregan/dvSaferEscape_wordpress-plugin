import $ from "jquery";
import { load } from "mime";

class SaferRedirect{
    constructor() {
        document.addEventListener("visibilitychange", ()=>{
            if (document.visibilityState !== "visible") {
                this.gtfo();
            }
        });
    }
    gtfo(){
        let searchDestination = localStorage.getItem('searchEngine') ? "https://" + localStorage.getItem('searchEngine') + ".com" : "https://google.com";
        searchDestination += '/search?q=';
        searchDestination += localStorage.getItem('searchString') ? localStorage.getItem('searchString').replace(/ /g, '+') : "baby+sloths";
        console.log('gtfo and going to ' + searchDestination);
    }
}

export default SaferRedirect;