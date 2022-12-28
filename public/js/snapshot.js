let width = 800;
let height = 600;

let canvas = document.querySelector('#canvas');
let context;
let video = document.querySelector('#webCam');
let div_selection = document.querySelector('#div_selection');

let effect_src = ''; // link of selected effect image
let selected_effect; // <img> HTMLElement of selected effect image
let contextImg; // base64 data of the edited image

let startX; // X coordinates of the selected effect image
let startY; // Y coordinates of the selected effect image
let mouseX; // X coordinates on the effect image when 'mousedown'
let mouseY; // Y coordinates on the effect image when 'mousedown'

let is_dragging = false;
let current_img_index; // index of dragged effect image
let effect_images = []; // array of added effect images to the canvas

let x = 0; // X position mouse on the canvas
let y = 0; // Y position mouse on the canvas

let effect_img = new Image();
let img = new Image();

let webcamStream;
let radioButtons = document.getElementsByName('rb');

canvas.width = width;
canvas.height = height;
canvas.onmousedown = mouse_down;
canvas.onmouseup = mouse_up;

// start video stream
function streamCam() {
    effect_images = [];
    div_selection.classList = 'close';
    video.classList = 'op';

    navigator.getMedia = (navigator.getUserMedia ||
    navigator.webkitGetUserMedia ||
    navigator.mozGetUserMedia ||
    navigator.msGetUserMedia);
    navigator.mediaDevices.getUserMedia({
        video: true
    }).then((stream) => {
        video.srcObject = stream;
        video.play();
 
        webcamStream = stream;
    }).catch((error) => {
        console.log('navigator.getUserMedia error: ', error);
    });
}

// make snapshot and draw photo on canvas
function takeAPicture() {
	let cxt = canvas.getContext('2d');
    cxt.drawImage(video, 0, 0, canvas.width, canvas.height);

	stopWebcam();
    video.classList = 'close';
    canvas.classList = 'op';
	
    context = cxt;
    img.src = canvas.toDataURL('image/jpeg', 1.0)
    contextImg = canvas.toDataURL('image/jpeg', 1.0);
}

// stop video stream
function stopWebcam() {
    webcamStream.getTracks()[0].stop(); 
}

// open video stream
function startWebcam() {
    canvas.classList = 'close';
    video.classList = 'op';

    streamCam();
}

// add new effect image on canvas
canvas.addEventListener('click', (event) => {
    if (is_dragging) {
        return;
    } else if (effect_src) {
        let image = new Image();
        image.src = effect_src;

        effect_images.push({'img_src': effect_src, 'x': x, 'y': y, 'angleToRad': 0, 'width': image.width / 10, 'height': image.height / 10});
        current_img_index = effect_images.length - 1;
        startX = x;
        startY = y;
        __drawEffectImages();
    }
})

canvas.addEventListener('mousemove', (event) => {
    coords = canvas.getBoundingClientRect();
  
    x = (event.clientX - coords.left) * (canvas.width / coords.width);
    y = (event.clientY - coords.top) * (canvas.height / coords.height);


    if (is_dragging) {
    	event.preventDefault();
    	let current_effect_img = effect_images[current_img_index];

    	let dx = x - mouseX;
    	let dy = y - mouseY;

    	current_effect_img.x = startX + dx;
    	current_effect_img.y = startY + dy;

    	__drawEffectImages();
        contextImg = canvas.toDataURL('image/jpeg', 1.0);
    }
})

// handler function for radio
radioButtons.forEach(radioButton => {
    radioButton.addEventListener('click', function() {
    	let effect = radioButton.nextSibling.nextSibling;

    	if (!selected_effect) {
    		selected_effect = effect;
    		selected_effect.classList.add('selected_effect');
        	effect_src = radioButton.value;
    	} else if (effect === selected_effect) {
    		selected_effect.classList.remove('selected_effect');
    		selected_effect = null;
    		effect_src = null;
    	} else {
			selected_effect.classList.remove('selected_effect');
			selected_effect = effect;
			effect_src = radioButton.value;
    	}
    })
})

// decrease effect image on canvas
function decrease() {
    if (current_img_index === NaN) return;

    effect_images[current_img_index].width *= 0.9;
    effect_images[current_img_index].height *= 0.9;

    __drawEffectImages();
    contextImg = canvas.toDataURL('image/jpeg', 1.0);
}
  
// increase effect image on canvas
function increase() {
    if (current_img_index === NaN) return;

    effect_images[current_img_index].width *= 1.1;
    effect_images[current_img_index].height *= 1.1;

    __drawEffectImages();
    contextImg = canvas.toDataURL('image/jpeg', 1.0);
}

// left rotate of image effect on canvas
function rotateLeft() {
    if (current_img_index === NaN) return;

    effect_images[current_img_index].angleToRad -= 5 * (Math.PI/180);

    __drawEffectImages();
    contextImg = canvas.toDataURL('image/jpeg', 1.0);
}

// right rotate of image effect on canvas
function rotateRight() {
    if (current_img_index === NaN) return;

    effect_images[current_img_index].angleToRad += 5 * (Math.PI/180);

    __drawEffectImages();
    contextImg = canvas.toDataURL('image/jpeg', 1.0);
}

// canсel last effect
function cancelEffect() {
    effect_images.splice(-1);
    __drawEffectImages();
}

// send base64 data from canvas to server
function saveImg() {
    let form = new FormData();
    form.append('img_base64', contextImg.replace(/^data:image\/jpeg;base64,/, ""));
    
    fetch(`http://localhost/The-Camagru-Project/edited_images`, {
		    method: 'POST',
            body: form,
    }).then(() => window.location.replace(window.location.href));
}

let formImgForCanvas = document.querySelector('#formImgForCanvas');

// added uploaded image on canvas
document.querySelector('#formImgForCanvas').addEventListener('submit', function(e) {
    e.preventDefault();
    var input, file, reader;
  
    input = document.querySelector('input');
  
    file = input.files[0];
    reader = new FileReader();
    reader.onload = drawImage;
    reader.readAsDataURL(file);
    canvas.classList = 'op';
    div_selection.classList = 'close';
  
  
    function drawImage() {
        img = new Image();
        img.onload = function() {
            canvas.width = img.width;
            canvas.height = img.height;
            var ctx = canvas.getContext("2d");
            ctx.drawImage(img, 0, 0, img.width, img.height);
            context = ctx;
            contextImg = canvas.toDataURL('image/jpeg', 1.0);
        }
        img.src = reader.result;
        
    }
  });

function mouse_down(event) {
    event.preventDefault();
	
	let index = 0;

	for(let effect_image of effect_images) {
		if (is_mouse_in_effect_img(x, y, effect_image)) {
            
			current_img_index = index;
			is_dragging = true;
            mouseX = x;
            mouseY = y
            startX = effect_image.x;
            startY = effect_image.y;
			return;
		}
		index++;
	}
}

function mouse_up(event) {
    if (!is_dragging) return;

	event.preventDefault();
	is_dragging = false;
}

// check "mouse down" on effect image
function is_mouse_in_effect_img(mouseX, mouseY, effect_image) {
    let img_left = effect_image.x - effect_image.width / 2;
	let img_right = effect_image.x + effect_image.width;
	let img_top = effect_image.y - effect_image.height;
	let img_bottom = effect_image.y + effect_image.height;

	return mouseX > img_left && mouseX < img_right && mouseY > img_top && mouseY < img_bottom;
}

// draws image effects on the canvas
function __drawEffectImages() {
    context.drawImage(img, 0, 0, canvas.width, canvas.height)

    for(let effect_image of effect_images) {
        let image = new Image();
        image.src = effect_image.img_src;

        context.translate(effect_image.x - effect_image.width / 2, effect_image.y - effect_image.height / 2);
        context.rotate(effect_image.angleToRad);
        context.drawImage(image, 0, 0, effect_image.width, effect_image.height);
        context.rotate(-effect_image.angleToRad);
        context.translate(-(effect_image.x - effect_image.width / 2), -(effect_image.y - effect_image.height / 2));
    }
}
