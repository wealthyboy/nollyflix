<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8" />
        <title>NollyFlix</title>
        <meta name="author" content="AchuWorld">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="">
        <meta name="description" content="">
        <meta name="keywords" content="" />
        <link rel='dns-prefetch' href='//fonts.googleapis.com' />
        <meta name="generator" content="Social Likes: http://social-likes.js.org/">
        <link href="https://fonts.googleapis.com/css2?family=Lato:wght@700;900&display=swap" rel="stylesheet"> 
        <style>
body, html {
    height: 100vh;
    margin:0;
    padding:0;
    font-family: 'Lato', sans-serif;
}

.bgimg {
  /* Background image */
  background-image: url('/images/banners/Bgnollyn01.jpg');
  /* Full-screen */
  height: 100%;
  /* Center the background image */
  background-position: center;
  /* Scale and zoom in the image */
  background-size: cover;
  /* Add position: relative to enable absolutely positioned elements inside the image (place text) */
  position: relative;
  /* Add a white text color to all elements inside the .bgimg container */
  color: white;
  /* Add a font */
  font-family: 'Lato', sans-serif;
  /* Set the font-size to 25 pixels */
  font-size: 25px;
}

/* Position text in the top-left corner */
.topleft {
  position: absolute;
  top: 0;
  left: 16px;
   max-width: 150px;
}
.topleft p img { max-width: 100px;}


/* Position text in the bottom-left corner */
.bottomleft {
  position: absolute;
  bottom: 0;
  left: 16px;
}

/* Position text in the middle */
.middle {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  text-align: center;
  color:#ffffff; 
  background-color: #9e0028;
  padding-left: 10px;
  padding-right: 10px;

}

/* Style the <hr> element */
hr {
  margin: auto;
  width: 40%;
}

// Extra small devices (portrait phones, less than 576px)
// No media query for `xs` since this is the default in Bootstrap

// Small devices (landscape phones, 576px and up)
@media (mix-width: 480px) {  
    .middle { width: 100%; padding: 40px;  background-color: red; }
    h1{font-size: 15px;}
 }

// Medium devices (tablets, 768px and up)
@media (min-width: 768px) { ... }

// Large devices (desktops, 992px and up)
@media (min-width: 992px) { ... }

// Extra large devices (large desktops, 1200px and up)
@media (min-width: 1200px) { ... }
</style>
</head>




<body>


 <div class="bgimg">
  <div class="topleft">
    <p><img src="/images/logo/NF00.png"  /></p>
  </div>
  <div class="middle">
    <h1>NollyFlix is under maintainance</h1>
    <hr>
  </div>
  <div class="bottomleft">
    <p></p>
  </div>
</div> 
  

</body>
</html>