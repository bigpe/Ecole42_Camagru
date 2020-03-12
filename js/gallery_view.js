let Scroll = true;
let current_page = 1;
let image_count;
let page_count;
let gallery;

onload = function () {
    gallery = document.getElementById("gallery");
    check_feed((current_page - 1) * 5);
};
onscroll = function end_scroll() {
    if (window.scrollY + 1 >= document.documentElement.scrollHeight - document.documentElement.clientHeight && Scroll) {
        if (current_page != page_count) {
            current_page += 1;
            Scroll = false;
            check_feed((current_page - 1) * 5);
        }
    }
};

setInterval(function () {
    Scroll = true;
    }, 500);

function check_feed(offset) {
    $.ajax({
        url: '../check_feed.php',
        method: "POST",
        data: {"offset": offset},
        async: true,
        success: function (data) {
            fill_feed(data);
        }
    });

}
function fill_feed(r) {
    image_count= r['image_count'];
    page_count = Math.ceil(image_count / 5);
    image_count = image_count - ((current_page - 1) * 5);
    for(let i=0; i < image_count; i++) {
        if (i == 5)
            break;
        let feed_block = document.createElement("div");
        feed_block.setAttribute("class", "feed_block");
        let feed_image = document.createElement("img");
        feed_image.src = r[i]['photo_base64'];
        feed_image.setAttribute("class", "feed_image");
        feed_image.setAttribute("id", r[i]['image_id']);
        let feed_date_block = document.createElement("div");
        feed_date_block.setAttribute("class", "feed_date_block");
        feed_date_block.innerHTML = "<sup>" + r[i]['time'] + "</sup>";
        feed_block.append(feed_date_block);
        let feed_button_block = document.createElement("div");
        feed_button_block.setAttribute("class", "feed_button_block");
        if (navigator.userAgent.match("Chrome") || navigator.userAgent.match("Safari"))
            feed_button_block.style.marginLeft = "95px";
        let like_count = "0";
        if (r[i]['like_count'] > 0)
            like_count = r[i]['like_count'];
        let comment_count = "0";
        if (r[i]['comment_count'] > 0)
            comment_count = r[i]['comment_count'];
        let like_count_mini = document.createElement("p");
        like_count_mini.setAttribute("class", "like_count_mini");
        if(like_count <= 0)
            like_count_mini.style.color = "white";
        like_count_mini.innerHTML = like_count;
        feed_button_block.append(like_count_mini);
        let like_button_mini = document.createElement("p");
        like_button_mini.innerHTML = "<i class=\"far fa-heart\"></i>";
        if (r[i]['like_self'] == 1) {
            like_button_mini.innerHTML = "<i class=\"fas fa-heart\"></i>";
            like_button_mini.setAttribute("class", "like_button_mini_liked");
            like_button_mini.style.color = "crimson";
        }
        else
            like_button_mini.setAttribute("class", "like_button_mini");
        feed_button_block.append(like_button_mini);
        like_button_mini.onclick = function () {
            if(like_button_mini.className == "like_button_mini") {
                like_button_mini.setAttribute("class", "like_button_mini_liked");
                like_count_mini.innerText = (parseInt(like_count_mini.innerText) + 1).toString();
                like_count_mini.style.color = "black";
                like_button_mini.innerHTML = "<i class=\"fas fa-heart\"></i>";
                like_button_mini.style.color = "crimson";
                set_like(0, feed_image.id);
            }
            else{
                like_button_mini.setAttribute("class", "like_button_mini");
                like_count_mini.innerText = (parseInt(like_count_mini.innerText) - 1).toString();
                if (parseInt(like_count_mini.innerHTML) <= 0)
                    like_count_mini.style.color = "white";
                like_button_mini.innerHTML = "<i class=\"far fa-heart\"></i>";
                like_button_mini.style.color = "gray";
                // like_button.firstElementChild.setAttribute("class", "fas fa-heart-broken");
                set_like(1, feed_image.id);
            }
        };
        let comment_count_mini = document.createElement("p");
        comment_count_mini.setAttribute("class", "comment_count_mini");
        comment_count_mini.innerHTML = comment_count;
        feed_button_block.append(comment_count_mini);
        let comment_button_mini = document.createElement("p");
        comment_button_mini.setAttribute("class", "comment_button_mini");
        comment_button_mini.innerHTML = "<i class=\"far fa-comment\"></i>";
        feed_button_block.append(comment_button_mini);
        let comment_frame = document.createElement("iframe");
        if(comment_count <= 0)
            comment_count_mini.style.color = "white";
        comment_button_mini.onclick = function (){
            if(!(feed_block.lastChild == comment_frame)) {
                if(document.getElementById("comment_frame"))
                    document.getElementById("comment_frame").remove();
                comment_frame.setAttribute("id", "comment_frame");
                comment_frame.setAttribute("src", "comment_page_feed.html");
                feed_block.append(comment_frame);
            }
        };
        let share_count_mini = document.createElement("p");
        share_count_mini.setAttribute("class", "share_count_mini");
        // share_count_mini.innerHTML = share_count;
        feed_button_block.append(share_count_mini);
        let share_button_mini = document.createElement("p");
        share_button_mini.setAttribute("class", "share_button_mini");
        share_button_mini.innerHTML = "<i class=\"far fa-share-square\"></i>";
        share_button_mini.onclick = function(){
            window.open("https://vk.com/share.php?url=" + window.location.href.split('/').slice(0, 3).join('/')
                + "?action=image_view&image_id=" + feed_image.id, "_blank");
        };
        feed_button_block.append(share_button_mini);
        feed_block.append(feed_image);
        feed_block.append(feed_button_block);
        gallery.append(feed_block);
    }
}

function set_like(type, image_id){
    let r = new XMLHttpRequest();
    r.open("POST", "../set_like.php", true);
    r.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    if(!type)
        r.send("image_id=" + image_id + "&action=like");
    else
        r.send("image_id=" + image_id + "&action=dislike");
}