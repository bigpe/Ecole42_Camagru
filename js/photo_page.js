let image_id;
let photo_page = document.getElementById("photo_page");
let like_button = document.getElementById("like_button");
let like_count = document.getElementById("like_count");
let comment_button = document.getElementById("comment_button");
let share_button = document.getElementById("share_button");
let comment_count = document.getElementById("comment_count");
let close_button = document.getElementById("close_button");
let delete_photo = document.getElementById("delete_photo");
let black_screen = parent.document.getElementById("black_screen");
let photo_frame = parent.document.getElementsByClassName("photo_frame")[0];
let comment_frame = document.createElement("iframe");
let dislike = true;
let like = true;

function getCookie(name) {
    let nameEQ = name + "=";
    let ca = parent.document.cookie.split(';');
    for(let i=0;i < ca.length;i++) {
        let c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

onload = function () {
    window.focus();
    if (navigator.userAgent.match("Chrome") || navigator.userAgent.match("Safari"))
        document.getElementById("photo_page_close_block")
            .setAttribute("style", "margin-left: 240px");
    image_id = getCookie("image_id");
    check_likes();
    check_comments();
    image_load();
};

like_button.onclick = function () {
    if(like) {
        like = false;
        dislike = true;
        like_count.innerText = (parseInt(like_count.innerText) + 1).toString();
        like_button.firstElementChild.setAttribute("class", "fas fa-heart");
    }
    else{
        dislike = false;
        like = true;
        like_count.innerText = (parseInt(like_count.innerText) - 1).toString();
        like_button.firstElementChild.setAttribute("class", "fas fa-heart-broken");
    }
    set_like(like);
};

function set_like(type){
    let r = new XMLHttpRequest();
    r.open("POST", "../set_like.php", true);
    r.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    if(!type)
        r.send("image_id=" + image_id + "&action=like");
    else
        r.send("image_id=" + image_id + "&action=dislike");
}

comment_button.onclick = function () {
    if (!document.getElementById("comment_frame")) {
        comment_frame.setAttribute("id", "comment_frame");
        comment_frame.setAttribute("src", "comment_page.html");
        photo_page.append(comment_frame);
    }
};


delete_photo.onclick = function () {
    if(confirm("Delete this image?")) {
        parent.document.getElementById("video").play();
        parent.document.getElementById("wrap").style.filter = "blur(0)";
        parent.document.getElementById("footer").style.filter = "blur(0)";
        if(parent.document.getElementById(image_id))
            parent.document.getElementById(image_id).parentElement.remove();
        black_screen.setAttribute("style", "display: none");
        let xhr = new XMLHttpRequest();
        xhr.open('POST', "../delete_photo.php", true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send('photo_token=' + image_id);
        xhr.onload = function () {
            if(parent.document.documentURI.match("action=image_view&image_id=")) {
                if (!parent.document.getElementById("shot_button"))
                    parent.window.location = "/";
                else
                    parent.window.history.replaceState("", "Camagru", "/");
            }
            photo_frame.remove();
        }
    }
};
close_button.onclick = function () {
    if(parent.document.documentURI.match("action=image_view&image_id="))
        if(!parent.document.getElementById("shot_button"))
            parent.window.location = "/";
    else {
        parent.window.history.replaceState("", "Camagru", "/");
        parent.document.getElementById("video").play();
        parent.document.getElementById("wrap").style.filter = "blur(0)";
        parent.document.getElementById("footer").style.filter = "blur(0)";
        photo_frame.remove();
        black_screen.setAttribute("style", "display: none");
    }
};

share_button.onclick = function () {
     window.open("https://vk.com/share.php?url=" + window.location.href.split('/').slice(0, 3).join('/')
         + "?action=image_view&image_id=" + image_id, "_blank");
};

function check_likes() {
    let r = new XMLHttpRequest();
    r.open("POST", "../check_likes.php", true);
    r.responseType = "json";
    r.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    r.send("image_id=" + image_id);
    r.onload = function () {
        if(r.response['LikeSelf']) {
            like = false;
            like_button.firstElementChild.setAttribute("class", "fas fa-heart");
        }
        like_count.innerText = r.response['LikeCount'];
        if (r.response['LikeCount'] > 9)
            like_count.style.marginLeft = "-35px";
    }
}

function check_comments() {
    let r = new XMLHttpRequest();
    r.open("POST", "../check_comments.php", true);
    r.responseType = "json";
    r.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    r.send("image_id=" + image_id);
    r.onload = function () {
        comment_count.innerText = r.response['CommentCount'];
        if (r.response['CommentCount'] > 9)
            comment_count.style.marginLeft = "-35px";
    }
}

function image_load() {
    let r = new XMLHttpRequest();
    r.open("POST", "../get_image.php", true);
    r.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    r.responseType = "json";
    r.send("image_id=" + image_id);
    r.onload = function () {
        if (!(r.response))
            parent.window.location = "/";
        let image_src = r.response['image_src'];
        let image = new Image();
        image.src = image_src;
        photo_page.setAttribute("style", "width: " + (image.width + 40).toString() + "px");
        photo_page.prepend(image);
    }
}

document.addEventListener("keydown", function (e) {
    if(e.keyCode == 27) {
        close_button.click();
    }
});