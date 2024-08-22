export default function () {
    /*
    *   First check if the device viewport is less than 768 pixels.
    *   If it is, then check if the device matches the regex test to prevent the developer tools from working.
    *   TODO: Remove the check for the developer tools on production!
    */
    if (window.matchMedia("(max-width: 767px)").matches) {
        return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    }
    else return false;
}
