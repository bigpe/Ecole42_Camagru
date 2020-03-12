let shot_button = document.createElement("button");
let upload_button = document.createElement("button");
let video = document.getElementById("video");
let black_screen = document.getElementById("black_screen");
let shot_block = document.getElementById("shot_block");
let photo_block;
let cam_block = document.getElementById("cam_block");
let img_count = 0;
let page_block;
let filter;
let filter_block;
let filter_delete_block;
let filter_button_delete = document.createElement("p");
let filter_button_backward = document.createElement("p");
let filter_button_forward = document.createElement("p");
let page_button_backward = document.createElement("p");
let page_button_forward = document.createElement("p");
let filter_name = document.createElement("p");
let side_block = document.getElementById("side");
let page_count = 1;
let page_current = 1;
let img_current = 0;
let filters_name = ["Shreks", "Philosophise", "Bets", "Bionic", "Thief", "Assassin"];
let filter_current_name = null;
let filter_count = filters_name.length;
let filter_current = 0;
let save_button = document.createElement("button");
let del_button = document.createElement("button");
let image_base;

function create_filter_block() {
    if (filter_current == 0)
        filter = document.getElementById("filter");
    else
        filter = document.getElementById("filter_" + filter_current);
    filter_block = document.createElement("div");
    filter_block.setAttribute("id", "filter_block");
    filter_button_backward.setAttribute("id", "filter_button_backward");
    filter_button_backward.onclick = function (){
        filter_current -= 1;
        if (filter_current <= 0)
            filter_current = filter_count;
        filter.setAttribute("id", "filter_" + filter_current);
        filter_current_name = filters_name[filter_current - 1];
        create_filter_delete_block(filter_current_name);
    };
    filter_button_forward.setAttribute("id", "filter_button_forward");
    filter_button_forward.onclick = function() {
        filter_current += 1;
        if (filter_current >= filter_count)
            filter_current = 1;
        filter.setAttribute("id", "filter_" + filter_current);
        filter_current_name = filters_name[filter_current - 1];
        create_filter_delete_block(filter_current_name);
    };
    filter_button_backward.innerHTML = "<i class=\"fas fa-arrow-circle-left\"></i>";
    filter_button_forward.innerHTML = "<i class=\"fas fa-arrow-circle-right\"></i>";
    filter_block.append(filter_button_backward);
    filter_block.append(filter_button_forward);
    cam_block.append(filter_block);
}
function create_filter_delete_block(filter_current_name) {
    filter_name.innerText = filter_current_name;
    filter_delete_block = document.createElement("div");
    filter_delete_block.setAttribute("id", "filter_delete_block");
    if (navigator.userAgent.match("Chrome") || navigator.userAgent.match("Safari"))
        filter_delete_block.setAttribute("style", "margin-top: 70px");
    filter_button_delete.setAttribute("id", "filter_button_delete");
    filter_button_delete.onclick = function() {
        filter_current = 0;
        filter_current_name = null;
        filter_button_delete.remove();
        filter.setAttribute("id", "filter_" + filter_current);
        filter_name.innerText = null;
    };
    filter_button_delete.innerHTML = "<i class=\"fas fa-times-circle\"></i>";
    filter_delete_block.append(filter_button_delete);
    filter_delete_block.append(filter_name);
    cam_block.append(filter_delete_block);
}
function create_photo_block() {
    photo_block = document.createElement("div");
    photo_block.setAttribute("id", "photo_block");
    page_block = document.createElement("div");
    page_block.setAttribute("id", "page_block");
    side_block.append(photo_block);
    side_block.append(page_block);
}
function fill_page_block(page_count) {
    if (page_count > 1 && page_current == 1)
        create_page_button_forward();
    if (page_current != page_count)
        create_page_button_forward();
    if (page_current != 1)
        create_page_button_backward();
}
function create_page_button_forward(){
    page_button_forward.setAttribute("id", "page_button_forward");
    page_button_forward.innerHTML = "<i class=\"fas fa-chevron-right\"></i>";
    page_block.append(page_button_forward);
    page_button_forward.onclick = function () {
        if (page_current != page_count){
            page_current += 1;
            photo_block.remove();
            check_gallery(page_current * 5 - 5);
            if (page_current == page_count)
                page_button_forward.remove();
            create_page_button_backward();
        }
    }
}
function create_page_button_backward(){
    page_button_backward.setAttribute("id", "page_button_backward");
    page_button_backward.innerHTML = "<i class=\"fas fa-chevron-left\"></i>";
    page_block.prepend(page_button_backward);
    page_button_backward.onclick = function () {
        if (page_current != 1) {
            page_current -= 1;
            photo_block.remove();
            check_gallery(page_current * 5 - 5);
            if (page_current == 1)
                page_button_backward.remove();
            create_page_button_forward();
        }
    }
}

onload=function() {
    if (document.documentURI.match("action=image_view&image_id=")){
        document.getElementById("wrap").style.filter = "blur(1px)";
        document.getElementById("footer").style.filter = "blur(1px)";
        black_screen.setAttribute("style", "display: inherit");
        black_screen.style.height = (document.getElementById("wrap").clientHeight +
            document.getElementById("footer").clientHeight).toString() + "px";
        let photo_page = document.createElement("iframe");
        photo_page.setAttribute("src", "html/photo_page.html");
        photo_page.setAttribute("class", "photo_frame");
        document.cookie = "image_id=" + document.documentURI.split("image_id=")[1];
        document.body.append(photo_page);
    }
    else {
        upload_button.setAttribute("id", "upload_button");
        save_button.setAttribute("id", "save_button");
        save_button.innerHTML = "<i class=\"fas fa-check\"></i>";
        del_button.setAttribute("id", "del_button");
        del_button.innerHTML = "<i class=\"fas fa-times\"></i>";
        upload_button.innerHTML = "<i class=\"fas fa-file-upload\"></i>";
        shot_block.append(upload_button);
        check_gallery(page_current * 5 - 5);
        shot_button.setAttribute("id", "shot_button");
        shot_button.innerHTML = "<i class=\"fas fa-camera\"></i>";
        shot_block.prepend(shot_button);
        if (navigator.userAgent.match("Chrome") || navigator.userAgent.match("Safari"))
            shot_block.setAttribute("style", "margin-top: 140px;");
        start_video();
    }
};

function start_video() {
    if(video.srcObject)
        return (1);
    navigator.mediaDevices.getUserMedia({video: true })
        .then(function(stream) {
            if ("srcObject" in video) {
                video.srcObject = stream;
            } else {
                video.src = window.URL.createObjectURL(stream);
            }
            video.onloadedmetadata = function() {
                video.play();
                create_filter_block();
                if (navigator.userAgent.match("Chrome") || navigator.userAgent.match("Safari")){
                    shot_block.setAttribute("style", "margin-top: 140px;");
                    filter.setAttribute("style", "margin-top: -27px;");
                }
                return (1);
            };
        })
        .catch(function(err) {
            console.log(err.name + ": " + err.message);
            return (0);
        });
}

function check_gallery(offset)
{
    create_photo_block();
    let xhr = new XMLHttpRequest();
    xhr.open('POST', "../check_gallery.php", true);
    xhr.responseType = 'json';
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send("offset=" + offset);
    xhr.onload = function () {
      if(xhr.response.length != 0)
      {
          let gallery_images = xhr.response['data'];
          fill_gallery(gallery_images);
          img_current = gallery_images.length;
          img_count = parseInt(xhr.response['image_count']);
          page_count = Math.ceil(img_count / 5);
          fill_page_block(page_count);
      }
    };
}
function fill_gallery(gallery_images) {
    for (let i = 0; i < gallery_images.length && i < 5; i++) {
        let image = new Image();
        let photo_block_image = document.createElement("div");
        image.src = gallery_images[i]['photo_byte'];
        image.setAttribute("id", gallery_images[i]['photo_token']);
        photo_block_image.setAttribute("class", "photo_block_image");
        photo_block_image.innerHTML = "<i class=\"fas fa-trash\" id='photo_block_trash" + (i + 1) + "'></i>";
        photo_block_image.append(image);
        photo_block.append(photo_block_image);
        let photo_block_trash = document.getElementById("photo_block_trash" + (i + 1));
        image.onclick = function () {
            document.cookie = "image_id=" + image.id;
            window.history.replaceState("", "Camagru", "?action=image_view&image_id=" + image.id);
            if(video.srcObject)
                video.pause();
            document.getElementById("wrap").style.filter = "blur(1px)";
            document.getElementById("footer").style.filter = "blur(1px)";
            black_screen.setAttribute("style", "display: inherit");
            black_screen.style.height = (document.getElementById("wrap").clientHeight +
                document.getElementById("footer").clientHeight).toString() + "px";
            let photo_page = document.createElement("iframe");
            photo_page.setAttribute("src", "html/photo_page.html");
            photo_page.setAttribute("class", "photo_frame");
            photo_page.setAttribute("id", image.id);
            document.body.append(photo_page);
        };
        photo_block_trash.onclick = function () {
            if (confirm("Delete this image?")) {
                img_current -= 1;
                img_count -= 1;
                let photo_token = image.getAttribute("id");
                let xhr = new XMLHttpRequest();
                xhr.open('POST', "../delete_photo.php", true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.send('photo_token=' + photo_token);
                photo_block_image.remove();
                if(img_current == 0) {
                    page_current -= 1;
                    check_gallery(0);
                }
            };
        };
        photo_block_trash.onmouseover = function () {
            photo_block_trash.setAttribute("class", "fas fa-trash-restore");
        }
        photo_block_trash.onmouseout = function () {
            photo_block_trash.setAttribute("class", "fas fa-trash");
        }
    };
};
shot_button.onclick=function () {
    if(start_video()){
        let filter_current_to_append = filter_current;
        if(filter_current != 0){
            filter_delete_block.remove();
            filter_current_name = null;
            filter_current = 0;
        }
        filter_block.remove();
        shot_button.remove();
        upload_button.remove();
        if(document.getElementById("photo"))
            document.getElementById("photo").remove();
        let canvas = document.createElement("canvas");
        canvas.setAttribute("id", "photo");
        cam_block.append(canvas);
        let context = canvas.getContext('2d');
        canvas.setAttribute("height", video.scrollHeight);
        canvas.setAttribute("width", video.scrollWidth);
        if (!document.getElementById("save_button") || !document.getElementById("del_button")) {
            shot_block.append(save_button);
            shot_block.append(del_button);
            save_button.onclick = function () {
                filter.setAttribute("id", "filter");
                create_filter_block();
                shot_button.setAttribute("id", "shot_button");
                shot_button.innerHTML = "<i class=\"fas fa-camera\"></i>";
                shot_block.append(shot_button);
                upload_button.setAttribute("id", "upload_button");
                upload_button.innerHTML = "<i class=\"fas fa-file-upload\"></i>";
                shot_block.append(upload_button);
                image_append(filter_current_to_append);
                photo_block.remove();
                page_block.remove();
                check_gallery(0);
                img_count += 1;
                clear_canvas();
                save_button.remove();
                del_button.remove();
                canvas.remove();
            };
            del_button.onclick = function () {
                filter.setAttribute("id", "filter");
                clear_canvas();
                del_button.remove();
                save_button.remove();
                canvas.remove();
                shot_button.setAttribute("id", "shot_button");
                shot_button.innerHTML = "<i class=\"fas fa-camera\"></i>";
                shot_block.append(shot_button);
                upload_button.setAttribute("id", "upload_button");
                upload_button.innerHTML = "<i class=\"fas fa-file-upload\"></i>";
                shot_block.append(upload_button);
                create_filter_block();
            };
        };
        context.translate(canvas.width, 0);
        context.scale(-1, 1);
        context.drawImage(video, 0, 0, video.scrollWidth, video.scrollHeight);
        let dataURI = canvas.toDataURL('image/jpeg');
        let image = new Image();
        let photo_token = Date.now().toString();
        image.src = dataURI;
        image.setAttribute("id", photo_token);
        image.onclick = function(){
            document.cookie = "image_id=" + image.id;
            window.history.replaceState("", "Camagru", "?action=image_view&image_id=" + image.id);
            if(video.srcObject)
                video.pause();
            document.getElementById("wrap").style.filter = "blur(1px)";
            document.getElementById("footer").style.filter = "blur(1px)";
            black_screen.setAttribute("style", "display: inherit");
            black_screen.style.height = (document.getElementById("wrap").clientHeight +
                document.getElementById("footer").clientHeight).toString() + "px";
            let photo_page = document.createElement("iframe");
            photo_page.setAttribute("src", "html/photo_page.html");
            photo_page.setAttribute("class", "photo_frame");
            photo_page.setAttribute("id", image.id);
            document.body.append(photo_page);
        };
        function image_append(filter_current_to_append) {
            let xhr = new XMLHttpRequest();
            let photo_block_image = document.createElement("div");
            xhr.open('POST', "../save_photo.php", true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send('image=' + btoa(image.src) +
                '&photo_token=' + photo_token +
                '&filter=' + filter_current_to_append);
            photo_block_image.setAttribute("class", "photo_block_image");
            photo_block_image.innerHTML = "<i class=\"fas fa-trash\" id='photo_block_trash" + (img_current + 1) + "'></i>";
            photo_block_image.append(image);
            photo_block.append(photo_block_image);
            let photo_block_trash = document.getElementById("photo_block_trash" + (img_current + 1));
            photo_block_trash.onclick = function () {
                if(confirm("Delete this image?")) {
                    img_current -= 1;
                    img_count -= 1;
                    let xhr = new XMLHttpRequest();
                    xhr.open('POST', "../delete_photo.php", true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.send('photo_token=' + photo_token);
                    image.remove();
                    photo_block_image.remove();
                }
            };
            photo_block_trash.onmouseover = function () {
                photo_block_trash.setAttribute("class", "fas fa-trash-restore");
            };
            photo_block_trash.onmouseout = function () {
                photo_block_trash.setAttribute("class", "fas fa-trash");
            }
        }
        function clear_canvas() {
            context.clearRect(0, 0, canvas.width, canvas.height);
        }
    }
};
onresize = function () {
    black_screen.style.height = (document.getElementById("wrap").clientHeight +
        document.getElementById("footer").clientHeight).toString() + "px";
};
upload_button.onclick = function () {
    let image_upload = new Image();
    let canvas_upload = document.createElement("canvas");
    canvas_upload.setAttribute("id", "canvas_upload");
    let file_input = document.createElement("input");
    file_input.type = "file";
    file_input.setAttribute("accept", "image/jpeg");
    file_input.click();
    file_input.onchange = function () {
        let file_upload = file_input.files[0];
        let reader = new FileReader();
        reader.readAsDataURL(file_upload);
        reader.onload = function () {
            let context_upload = canvas_upload.getContext('2d');
            image_upload.src = reader.result;
            canvas_upload.setAttribute("id", "photo");
            canvas_upload.setAttribute("height", "262px");
            canvas_upload.setAttribute("width", "350px");
            context_upload.drawImage(image_upload, 0, 0, video.scrollWidth, video.scrollHeight);
            image_base = canvas_upload.toDataURL('image/jpeg');
        };
        upload_button.remove();
        shot_button.remove();
        create_filter_block();
        shot_block.append(save_button);
        save_button.onclick = function (){
            if(video.srcObject)
                video.play();
            save_image(image_base);
            clear_filters();
            photo_block.remove();
            check_gallery(0);
        };
        shot_block.append(del_button);
        del_button.onclick = function(){
            if(video.srcObject)
                video.play();
            clear_filters();
        };
        cam_block.append(canvas_upload);
    };
};


function clear_filters() {
    document.getElementById("photo").remove();
    if(document.getElementById("filter_" + filter_current))
        document.getElementById("filter_" + filter_current).setAttribute("id", "filter");
    if(document.getElementById("filter_delete_block"))
        filter_delete_block.remove();
    save_button.remove();
    del_button.remove();
    shot_block.append(shot_button);
    shot_block.append(upload_button);
    if(document.getElementById("filter_block"))
        filter_block.remove();
    filter_current = 0;
}
function save_image(image_src) {
    let photo_token = Date.now().toString();
    let r = new XMLHttpRequest();
    r.open('POST', "../save_photo.php", true);
    r.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    r.send('image=' + btoa(image_src) +
        '&photo_token=' + photo_token +
        '&filter=' + filter_current.toString());
}