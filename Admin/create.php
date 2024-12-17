<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">

    <style>
        h1 {
            text-align: center;
            margin-bottom: 30px;
            margin-top: 250px;
            font-family: 'Press Start 2P', cursive;
            animation: float 3s ease-in-out infinite;
            color:  #A87C41;
            text-shadow: 1px 1px 0 #000, -1px 1px 0 #000, 1px -1px 0 #000, -1px -1px 0 #000;
            display: flex;
            justify-content: center;
            gap: 5px; /* Adds spacing between letters */
            font-size: 85px;
            
        }
        
        h1 span {
            display: inline-block;
            animation: wave 1.5s ease-in-out infinite;
            animation-delay: calc(var(--i) * 0.1s);
        }

        /* Keyframes for the wave animation */
        @keyframes wave {
            0%, 100% {
                transform: translateY(0); /* Initial position */
            }
            50% {
                transform: translateY(-20px); /* Move up */
            }
        }

        /* Apply staggered delay using nth-child */
        h1 span:nth-child(1) { --i: 0; }
        h1 span:nth-child(2) { --i: 1; }
        h1 span:nth-child(3) { --i: 2; }
        h1 span:nth-child(4) { --i: 3; }
        h1 span:nth-child(5) { --i: 4; }
        h1 span:nth-child(6) { --i: 5; }
        h1 span:nth-child(7) { --i: 6; }

        /* Define the floating animation */
        @keyframes float {
            0% {
                transform: translateY(0); /* Initial position */
            }
            50% {
                transform: translateY(-20px); /* Move up */
            }
            100% {
                transform: translateY(0); /* Back to initial position */
            }
        }
        @font-face {
            font-family: 'Minecraft';
            src: url('minecraft-font.ttf') format('truetype'); /* Optional font */
        }
 
    </style>
</head>
<body>
    <h1>
        <span>W</span>
        <span>E</span>
        <span>L</span>
        <span>C</span>
        <span>O</span>
        <span>M</span>
        <span>E</span>
        <span>!</span>
        <span>!</span>
    </h1>
</body>
</html>
