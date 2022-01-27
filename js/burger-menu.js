const button = document.getElementsByClassName('mobile-menu-btn')[0];
const nav = document.getElementsByTagName('nav')[0];
console.log(button, nav)
let isNavShown = false;
button.addEventListener('mouseup', function()
{
    if(!isNavShown)
        nav.setAttribute('class', 'active');
    else
        nav.removeAttribute('class');

    isNavShown = !isNavShown;
});