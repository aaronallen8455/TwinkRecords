/**
 * Created by Aaron Allen on 6/16/2016.
 */

window.addEventListener('load', function () {
    // toggle element
    var toggle = document.getElementsByClassName('nav-menu-toggle')[0];
    // menu
    var menu = document.getElementsByClassName('nav-menu')[0];
    // wrapper
    var wrapper = document.getElementsByClassName('nav-menu-wrapper')[0];
    
    toggle.onclick = function () {
        if (!menu.classList.contains('show-menu')) {
            menu.classList.add('show-menu');
        }else{
            menu.classList.remove('show-menu')
        }
    };

    document.addEventListener('click', function (e) {
        //close the menu
        if (e.target !== menu && e.target !== toggle && menu.classList.contains('show-menu')) {
            menu.classList.remove('show-menu');
        }
    }, false);


    var menuScrollHandler = (function() {
        var scroll = undefined;
        var isRunning = false;
        return function() {
            var newPos = window.scrollY;
            //initialize scroll here so that menu does not scroll up when we visit the #cal anchor
            if (scroll === undefined) scroll = newPos;
            if (newPos <= wrapper.offsetHeight*3) {
                //we need to prevent slideUp from executing when we are at the top of the page
                wrapper.style.top = '0px';
                scroll = undefined;
                isRunning = false;
                return;
            }
            if (Math.abs(scroll - newPos) >= wrapper.offsetHeight) {
                if (newPos > scroll) {
                    //scrolling down, hide menu
                    if (!isRunning && !menu.classList.contains('show-menu')) {
                        function slideUp() {
                            if (wrapper.offsetTop > -wrapper.offsetHeight && isRunning) {
                                wrapper.style.top = wrapper.offsetTop - 2 + 'px';
                                window.requestAnimationFrame(slideUp);
                            }else{
                                isRunning = false;
                            }
                        }
                        if (wrapper.offsetTop >= -wrapper.offsetHeight) {
                            isRunning = true;
                            slideUp();
                        }
                    }
                }else{
                    //scrolling up, show menu
                    //dont scroll up if menu is open
                    if (!isRunning) {
                        isRunning = true;
                        function slideDown() {
                            if (wrapper.offsetTop < 0) {
                                wrapper.style.top = wrapper.offsetTop + 2 + 'px';
                                window.requestAnimationFrame(slideDown);
                            }else{
                                isRunning = false;
                            }
                        }
                        slideDown();
                    }
                }
                scroll = newPos;
            }

        }
    })();

    if (window.getComputedStyle(toggle).display === 'block')
        document.addEventListener('scroll', menuScrollHandler, false);

    //switch between mobile and regular fix
    window.addEventListener('resize', function() {
        if (window.getComputedStyle(toggle).display === 'block') {
            document.addEventListener('scroll', menuScrollHandler, false);
        }
        else {
            wrapper.style.top = 0;
            document.removeEventListener('scroll', menuScrollHandler, false);
        }
    }, false);

}, false);