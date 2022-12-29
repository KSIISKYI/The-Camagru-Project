document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("burger").addEventListener("click", function() {
        document.querySelector("header").classList.toggle("open")
    })
})

const comment_buttons = document.body.querySelectorAll(".comment");

console.log(comment_buttons)

for (let i = 0; i < comment_buttons.length; i++) {
    comment_buttons[i].addEventListener("click", function() {
        document.querySelector(`#comment${i}`).classList.toggle("open_comment_form");

        if (comment_buttons[i].innerText == "Reply") {
            comment_buttons[i].innerText = "Close";
        } else {
            comment_buttons[i].innerText = "Reply";
        }

        if (comment_buttons[i].style.backgroundColor == "rgb(251, 60, 60)") {
            comment_buttons[i].style.backgroundColor = '#3485ae';
        } else {
            comment_buttons[i].style.backgroundColor = "rgb(251, 60, 60)";
        }
    })
}

const delete_edited_image_btn = document.body.querySelector("#delete_edited_image_btn");

if (delete_edited_image_btn) {
    delete_edited_image_btn.addEventListener('click', function () {
        let response = fetch(`http://localhost/The-Camagru-Project/edited_images/${delete_edited_image_btn.value}`, {
            method: 'DELETE',
        });
        window.location.replace(`http://localhost/The-Camagru-Project/edited_images`);
    })
}

const delete_edited_image_btns = document.body.querySelectorAll('.delete_edited_image_btn_demo');

for (let i = 0; i < delete_edited_image_btns.length; i++) {
    delete_edited_image_btns[i].addEventListener('click', function () {
        let response = fetch(`http://localhost/The-Camagru-Project/edited_images/${delete_edited_image_btns[i].value}`, {
		    method: 'DELETE',
        });
        window.location.replace(`http://localhost/The-Camagru-Project/edited_images`);
    })
}


const liked = document.body.querySelectorAll('.liked');

for (let i = 0; i < liked.length; i++) {
    liked[i].addEventListener('click', function () {
        let response = fetch(`http://localhost/The-Camagru-Project/likes/${liked[i].value}`, {
		    method: 'DELETE',
        });
        window.location.replace(`http://localhost/The-Camagru-Project/edited_images`);
    })
}

const comments = document.body.querySelectorAll('.delete_comment_btn');

for (let i = 0; i < comments.length; i++) {
    comments[i].addEventListener('click', function () {
        fetch(`http://localhost/The-Camagru-Project/comments/${comments[i].value}`, {
		    method: 'DELETE',
        }).then(res => res.text()).then(res => window.location.replace(res));
    })
}
