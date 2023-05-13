function drawClock(){
    let canvas = document.getElementById("clock");
    let context = canvas.getContext("2d");

    let now = new Date();
    let hours = now.getHours();
    let minutes = now.getMinutes();
    let seconds = now.getSeconds();

    drawBackground(context);
    for(let i=1; i<13; i++){
        drawNumber(context,i);
    }
    drawHoursHand(context, hours, minutes);
    drawMinutesHand(context, minutes, seconds);
    drawSecondsHand(context, seconds);
    pointFinal(context,15);
    return context;
}

function drawBackground(context){
    context.save();
    context.fillRect(50,50,500,500);
    context.beginPath();
    context.fillStyle = "white";
    context.arc(300,300,225,0,2*Math.PI,false);
    context.fill();
    context.restore();
}

function drawNumber(context, number){
    context.save();
    context.font = "bold 50px arial";
    context.textAlign = "center";
    context.textBaseline = "middle";
    context.translate(300,300);
    context.rotate(number*Math.PI/6);
    context.translate(0, -175);
    context.fillText(number,0,0);
    context.restore();
}

function drawHoursHand(context, hours, minutes){
    context.save();
    context.translate(300,300);
    context.rotate((hours-6)*Math.PI/6);
    context.rotate((minutes)*Math.PI/360);
    context.beginPath();
    context.fillRect(0,0,5,150);
    context.restore();
}

function drawMinutesHand(context, minutes, seconds){
    context.save();
    context.translate(300,300);
    context.rotate((minutes-30)*Math.PI/30);
    context.rotate((seconds)*Math.PI/1800);
    context.beginPath();
    context.fillRect(0,0,5,190);
    context.restore();
}

function drawSecondsHand(context, secondes){
    context.save();
    context.fillStyle = "grey";
    context.strokeStyle = "grey";
    context.translate(300,300);
    context.rotate((secondes-30)*Math.PI/30);
    context.beginPath();
    context.fillRect(0,0,5,210);
    context.restore();
}

function fillRoundedRectangle(context, x, y, width, height, radius){
    context.save();
    context.beginPath();
    context.arc(x,y,radius,-Math.PI, Math.PI/2,false);
    context.moveTo();
    context.closePath();
    context.restore();
}

function pointFinal(context, rayon){
    context.save();
    context.beginPath();
    context.arc(300,300,rayon,0,2*Math.PI, false);
    context.fill();
    context.restore();
}

setInterval(drawClock, 1000);
