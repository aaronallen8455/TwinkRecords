/**
 * Created by Aaron Allen on 6/16/2016.
 */

var heightLimit = 200;

function checkContentHeight(content) {
    if (content.offsetHeight > heightLimit) {
        content.style.height = heightLimit + 'px';
        content.style.overflow = 'hidden';

        //make smoke screen
        var hider = document.createElement('div');
        hider.className = 'content-hider';
        content.appendChild(hider);
        var closeHiderLink = document.createElement('a');
        closeHiderLink.href = '#';
        closeHiderLink.innerHTML = 'Show';
        closeHiderLink.style.position = 'absolute';
        closeHiderLink.style.bottom = 0;
        closeHiderLink.style.right = '50%';
        closeHiderLink.title = 'Show Full Content';
        closeHiderLink.onclick = closeHider.bind(null, hider, content);
        hider.appendChild(closeHiderLink);
    }
}

function closeHider(hider, content, e) {
    //display full content
    hider.remove();
    content.style.height = 'auto';
    content.style.overflow = 'inherit';
    e.preventDefault();
    return false;
}

window.addEventListener('load', function () {
    //get content divs
    var contentDivs = document.getElementsByClassName('event-content-wrapper');
    //hide if too big
    for (var i=0; i<contentDivs.length; i++) {
        checkContentHeight(contentDivs[i]);
    }
});
