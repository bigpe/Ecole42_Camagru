let comment_send_button = document.getElementById("comment_send_button");
let comment_text_input = document.getElementById("comment_text_input");
let comment_send_block = document.getElementById("comment_send_block");
let current_comments = document.getElementById("current_comments");
let top_comment = document.getElementById("top_comment");
let comment_count = parseInt(parent.document.getElementById("comment_frame").parentElement.children[2].getElementsByClassName("comment_count_mini")[0].innerHTML);
let image_id;
let username;
let scroll;

onload = function () {
    comment_text_input.focus();
    image_id = parent.document.getElementById("comment_frame").parentElement.children[1].id;
    check_user();
    if(parseInt(parent.document.getElementById("comment_frame").parentElement.children[2].getElementsByClassName("comment_count_mini")[0].innerHTML) > 0)
        comment_fill();
};

comment_send_button.onclick = function () {
    comment_send();
};

function comment_send (){
    let message = comment_text_input.valueOf().value;
    if (message) {
        document.body.scrollTop = 0;
        comment_count += 1;
        parent.document.getElementById("comment_frame")
            .parentElement.children[2].getElementsByClassName("comment_count_mini")[0]
            .innerHTML = comment_count.toString();
        parent.document.getElementById("comment_frame")
            .parentElement.children[2].getElementsByClassName("comment_count_mini")[0]
            .style.color = "black";
        comment_input_block_reset();
        let r = new XMLHttpRequest();
        r.open("POST", "../save_comment.php", true);
        r.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        r.send("image_id=" + image_id + "&message=" + message);
        comment_append(message);
    }
}

comment_text_input.onclick = function () {
    comment_text_input.valueOf().placeholder = "";
};

comment_text_input.addEventListener("keydown", function (e) {
    if(e.keyCode == 13) {
        comment_send();
    }
});

comment_text_input.oninput = function () {
    comment_send_block.style.background = "black";
    comment_send_button.style.cursor = "pointer";
    if(!comment_text_input.valueOf().value) {
        comment_input_block_reset();
    }
};

function comment_input_block_reset() {
    comment_text_input.valueOf().value = null;
    comment_text_input.valueOf().placeholder = "Vote your message...";
    comment_send_block.style.background = "gray";
    comment_send_button.style.cursor = "default";
}

function comment_append(message) {
    const monthNames = ["January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"];
    let date = new Date();
    date = date.getDate() + " " + monthNames[date.getMonth()].slice(0, 3) + ". "
        + date.getHours() + ":" + String(date.getMinutes()).padStart(2, "0");
    let comment = document.createElement("p");
    comment.setAttribute("class", "comment");
    comment.innerHTML = "<b class='comment_username'>" + username + "</b>" + " " + message;
    current_comments.prepend(comment);
    let comment_date = document.createElement("p");
    comment_date.setAttribute("class", "comment_date");
    comment_date.innerText = date;
    current_comments.prepend(comment_date);
}

function check_user() {
    let r = new XMLHttpRequest();
    r.open("POST", "../check_user.php", true);
    r.responseType = "json";
    r.send();
    r.onload = function () {
        username = r.response['username'];
    }
}

onscroll = function () {
    scroll = document.body.scrollTop;
    if (scroll >= 40)
        top_comment.style.visibility = "inherit";
    else
        top_comment.style.visibility = "hidden";
};

function comment_fill(){
    let r = new XMLHttpRequest();
    r.open("POST", "../get_comments.php", true);
    r.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    r.responseType = 'json';
    r.send("image_id=" + image_id);
    r.onload = function () {
        let comments = r.response;
        let time = null;
        let text = null;
        let usr = null;
        for (let i = 0; i < comment_count; i++){
            time = comments[i]['time'];
            let comment_date = document.createElement("p");
            comment_date.setAttribute("class", "comment_date");
            comment_date.innerText = time;
            current_comments.append(comment_date);
            usr = comments[i]['username'];
            text = comments[i]['text'];
            let comment = document.createElement("p");
            comment.setAttribute("class", "comment");
            comment.innerHTML = "<b class='comment_username'>" + usr + "</b> " + text;
            current_comments.append(comment);
        }
    }
}