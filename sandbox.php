<?php
//<c> Sharkbyteprojects
$queries = array();
parse_str($_SERVER['QUERY_STRING'], $queries);
$height=$queries['height'];
$width=$queries['width'];
$ps=$queries['particlesize'];
$hc=$queries['hc'];
$bc=$queries['bc'];
$paused=$queries['paused'];
header('Content-Type: '."text/html".'; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sandbox</title>
    <!--<c> Sharkbyteprojects-->
</head>
<body>
    <div>
        <h1>Sandbox</h1>
        <div id="d" hidden>
            <h3>HOLD LMB on Canvas to add Sand!</h3>
            <canvas id="cv" style="border:1px solid #000000;background-color: #<?php echo((isset($bc)&&preg_match("/^(?:[0-9a-fA-F]{3}){1,2}$/", $bc)) == 1?$bc:"757575");?>;">CANVAS DOESN'T EXIST</canvas>
            <br>
            <button id="pp"></button>
        </div>
        <noscript>
            <p><strong>OOOOPS!</strong> You disabled JS</p>
            <p>Please Enable JS Now</p>
        </noscript>
        <p><a href="https://github.com/sharkbyteprojects" target="_blank">&copy; Sharkbyteprojects</a></p>
    </div>
    <script>
        (()=>{
            const h=<?php echo((isset($ps)&&preg_match("/^[0-9]+$/", $ps)) == 1?$ps:"3");?>;//SIZE OF SINGLE PARTICLES IN PX
            const canvassettings={
                "width":  <?php echo((isset($width)&&preg_match("/^[0-9]+$/", $width)) == 1?$width:"1000");?>, 
                "height": <?php echo((isset($height)&&preg_match("/^[0-9]+$/", $height)) == 1?$height:"500"); ?>,
                "colorx": "#<?php echo((isset($hc)&&preg_match("/^(?:[0-9a-fA-F]{3}){1,2}$/", $hc)) == 1?$hc:"ebf200"); ?>"};//

            document.getElementById("d").hidden=false
            const canvas = document.getElementById("cv");
            canvas.width=canvassettings.width;
            canvas.height=canvassettings.height;
            const ctx = canvas.getContext("2d");
            function clear(){
                ctx.clearRect(0, 0, canvas.width, canvas.height);
            }
            function realINP(xa){
                return Math.round(xa/h);
            }
            function drawPixel(x,y, f=undefined){
                ctx.beginPath();
                ctx.rect(x*h,y*h,h,h);
                ctx.fillStyle = f==undefined?canvassettings.colorx:f;
                ctx.fill();
            }
            var x=[];
            var mouseX, mouseY;
            function mouseMove(evt)
            {
                var rect = canvas.getBoundingClientRect();
                mouseX = evt.clientX - rect.left; mouseY =  evt.clientY - rect.top;
            }
            var permanentRun=false, inbounds=false, paused=<?php echo(($paused=="true"||$paused=="1")?"true":"false");?>;
            function mousedraw(){
                if(mouseX!=undefined&&mouseY!=undefined&&permanentRun&&inbounds)
                {
                    if(isNOTnull(x[realINP(mouseX)]))
                        x[realINP(mouseX)][realINP(mouseY)] = 1;
                }
            }
            function initialize(){
                for(var r=0;r<realINP(canvas.width);r++){
                    var h=[];
                    for(var f=0;f<realINP(canvas.height);f++){
                        h.push(0);
                        drawPixel(r,f);
                    }
                    x.push(h);
                }
            }
            function isNOTnull(v){
                return v!=null&&v!=undefined&&v!=0;
            }
            function process(){
                for(var r=x.length-2;r>=0;r--){
                    if(x[r]==undefined){
                        break;
                    }
                    for(var f=x[r].length-1;f>=0;f--){
                        if(x[r][f] == 1){
                            var processed = false;
                            if(x[r][f+1]==0){
                                x[r][f+1] = 1;
                                processed = true;
                            }else if(x[r-1]!=undefined&&x[r-1][f+1]==0){
                                x[r-1][f+1] = 1;
                                processed = true;
                            }else if(x[r+1]!=undefined&&x[r+1][f+1]==0){
                                x[r+1][f+1] = 1;
                                processed = true;
                            }
                            if(processed){
                                x[r][f]=0;
                            }
                        }
                    }
                }
            }
            function drawcall(){
                for(var r=0;r<x.length;r++){
                    for(var f=0;f<x[r].length;f++){
                        if(isNOTnull(x[r][f])){
                            drawPixel(r,f);
                        }
                    }
                }
            }
            function anim(){
                clear();
                mousedraw();
                if(!paused)
                    process();
                drawcall();
                window.requestAnimationFrame(anim);
            }
            const btn=document.getElementById("pp");
            function upaused(){
                btn.innerText=paused?"Play":"Pause";
            }
            initialize();
            anim();
            upaused();
            btn.style=`width: ${canvas.width}px;`;
            btn.onclick=()=>{
                paused=!paused;
                upaused();
            };
            canvas.onmousemove=mouseMove;
            canvas.onclick=(xx)=>{mouseMove(xx);if(isNOTnull(x[realINP(mouseX)]))x[realINP(mouseX)][realINP(mouseY)] = 1;};
            document.documentElement.onmousedown=()=>{
                permanentRun=true;
            };
            document.documentElement.onmouseup=()=>{
                permanentRun=false;
            };
            canvas.onmouseenter=()=>{
                inbounds=true;
            };
            canvas.onmouseleave=()=>{
                inbounds=false;
            }
            canvas.onmouseover = canvas.onmouseenter;
            canvas.onmouseout = canvas.onmouseleave;
        })();
    </script>
</body>
</html>