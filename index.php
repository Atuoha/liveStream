
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Live</title>   
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css">
  <link rel="stylesheet" href="bootstrap.min.css">
  <link rel="stylesheet" href="mdb.min.css">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="custom.css"> 
</head> 


<!-- AUTHOR: ATUOHA ANTHONY -->
<!-- GITHUB: GITHUB.COM/ATUOHA -->
<!-- FACEBOOK: FACEBOOK.COM/ATUTECHSCORP -->

 <body>
<div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
        <div class="card">
        <div class="card-header card-header-primary">
            <h4 class="card-title red-text"><i class="fa fa-video-camera fa-2x red-text"></i> Recording Live</h4>
            <p class="card-category black-text"><a href="https://facebook.com/atutechscorp" class="black-text">|<i class="fa fa-home"></i> Go back Home |</a> We are sharing the beautiful captures that exhort our thinking </p>
        </div>


 <div class="card-body">
    <div class="text-center" id="cam_tv"> 

    <!-- ADMIN VIEW  --->
    <video controls></video>
    <p><button id="btnStart" class="btn btn-primary"> <i class="fa fa-play"></i></button>
    <button id="btnStop" class="btn btn-danger"> <a class="text-white" href="#vid2"><i class="fa fa-pause"></i></a></button></p> 
    <!-- ADMIN RECORDING -->

    <!--  VIEWING  -->
    <video controls id="vid2"  autoplay="true"></video>


    <!-- Form for submitting  to database-->
    <div class="container text-center">
        <form>
            <div class="row">
                <div class="col-md-6 label">
                    <div class="form-group">
                    <input type="text" class="form-control" placeholder="Video Name">
                    </div>
                </div>

                <div class="col-md-6">
                <button id="send" class="btn btn-primary"> <i class="fa fa-send"></i> RECORDING...</button>
                </div>
                
                </div>  
        </form> 

    </div>  
    <!-- End of form  -->

    </div>

           </div>
        </div>
      </div>
    </div>
</div>
</div>
   
</body>
</html> 



<script>
let constraintObj = { 
    audio: true, 
    video: { 
        facingMode: "environment", 
        width: { min: 640, ideal: 1280, max: 1920 },
        height: { min: 480, ideal: 720, max: 1080 } 
    } 
}; 
        // width: 1280, height: 720  -- preference only
        // facingMode: {exact: "user"}
        // facingMode: "environment"
        
        //handle older browsers that might implement getUserMedia in some way
        if (navigator.mediaDevices === undefined) {
            navigator.mediaDevices = {};
            navigator.mediaDevices.getUserMedia = function(constraintObj) {
                let getUserMedia = navigator.webkitGetUserMedia || navigator.mozGetUserMedia;
                if (!getUserMedia) {
                    return Promise.reject(new Error('getUserMedia is not implemented in this browser'));
                }
                return new Promise(function(resolve, reject) {
                    getUserMedia.call(navigator, constraintObj, resolve, reject);
                });
            }
        }else{
            navigator.mediaDevices.enumerateDevices()
            .then(devices => {
                devices.forEach(device=>{
                    console.log(device.kind.toUpperCase(), device.label);
                    //, device.deviceId
                })
            })
            .catch(err=>{
                console.log(err.name, err.message);
            })
        }

        navigator.mediaDevices.getUserMedia(constraintObj)
        .then(function(mediaStreamObj) {
            //connect the media stream to the first video element
            let video = document.querySelector('video');
            if ("srcObject" in video) {
                video.srcObject = mediaStreamObj;
            } else {
                //old version
                video.src = window.URL.createObjectURL(mediaStreamObj);
            }
            
            video.onloadedmetadata = function(ev) {
                //show in the video element what is being captured by the webcam
                video.play();
            };
            
            //add listeners for saving video/audio
            let start = document.getElementById('btnStart');
            let stop = document.getElementById('btnStop');
            let vidSave = document.getElementById('vid2');
            let mediaRecorder = new MediaRecorder(mediaStreamObj);
            let chunks = [];
            
            start.addEventListener('click', (ev)=>{
                mediaRecorder.start();

            document.getElementById('send').innerHTML = `<i class="fa fa-video-camera"></i> RECORDING A NEW ONE`;
            document.getElementById('send').style.display = 'block';
            document.querySelector('.label').style.display = 'none';

                
            console.log(mediaRecorder.state);
            })
            stop.addEventListener('click', (ev)=>{
                mediaRecorder.stop();
            document.getElementById('send').innerHTML = `<i class="fa fa-send"></i> SHARE`;
            document.getElementById('send').style.display = 'block';
            document.querySelector('.label').style.display = 'block';
            

                console.log(mediaRecorder.state);
            });
            mediaRecorder.ondataavailable = function(ev) {
                chunks.push(ev.data);
            }
            mediaRecorder.onstop = (ev)=>{
                let blob = new Blob(chunks, { 'type' : 'video/mp4;' });
                chunks = [];
                let videoURL = window.URL.createObjectURL(blob);
                vidSave.src = videoURL;
            }

        })
        .catch(function(err) { 
            console.log(err.name, err.message); 
        });
</script>