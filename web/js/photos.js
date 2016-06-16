/**
 * Created by Aaron Allen on 6/15/2016.
 */

window.addEventListener('load',function () {
    var images = document.getElementsByClassName('photo-thumbnail');
    var slides = [];
    for (var i=0; i<images.length; i++) {
        slides.push(
            {
                src: images[i].dataset.url,
                w: images[i].offsetWidth*8.333,
                h: images[i].offsetHeight*8.333,
                msrc: images[i].src,
                title: images[i].title
            }
        );
        images[i].onclick = openGallery.bind(null, i);
    }
    var pswpElement = document.getElementsByClassName('pswp')[0];

    //var gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, slides, []);
    //gallery.init();

    function openGallery(index) {
        var gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, slides, {'index':index});
        gallery.init();
    }
}, false);
