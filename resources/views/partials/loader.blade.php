{{-- loader.blade.php --}}
<style>
    .loader-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        /* background-color: rgba(168, 125, 223, 1);  */
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        opacity: 1; 
        transition: opacity 0.7s ease-in-out; 
    }

    .loader-logo {
        width: 240px; 
        height: 240px; 
        margin-bottom: 25px; 
    }

    .p2p-logo-animated-path {
        fill: transparent; 
        stroke: white;
        stroke-linecap: round;
        stroke-linejoin: round;
        transition-property: fill, stroke;
        transition-duration: 0.4s; 
        transition-timing-function: ease-out;
    }

    .p2p-path-1,
    .p2p-path-2,
    .p2p-path-3 {
        stroke-width: 5;
    }

    .p2p-logo-animated-path.filled {
        fill: white;
        stroke: white; 
    }

    .progress-bar-container {
        width: 180px;
        height: 12px;
        background-color: rgba(255, 255, 255, 0.3);
        border-radius: 6px;
        overflow: hidden;
        box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
    }

    .progress-bar-fill {
        width: 0%;
        height: 100%;
        background-color: white;
        border-radius: 6px;
        transition: width 0.2s ease-out;
    }

    body.loaded .loader-overlay {
        opacity: 0;
        pointer-events: none;
    }
</style>

<div class="loader-overlay bg-gradient-to-r from-[#38A3A5] to-[#80ED99]" id="loaderOverlay">
    <svg id="Layer_2" class="loader-logo p2p-logo-animated" xmlns="http://www.w3.org/2000/svg" viewBox="-80 -50 734.66 662.34">
      <g id="Layer_7">
        <g id="p2p_logo">
          <path class="p2p-logo-animated-path p2p-path-1" d="M0,104.34h65v30.32c7.51-7.21,39.49-36.3,86-35.32,48.99,1.04,94.31,35.02,114,86l-60,64c6.95-28.18-3.33-57.83-26.06-75.31-21.63-16.64-51.08-19.61-75.94-7.69-4.16,2.53-33.38,20.95-39.21,58.34-5.5,35.24,13.96,60.57,17.21,64.66,0,0,68,69,121-24,0,0,67-59,99-113,0,0,38-60-18-87,0,0-53-17-67,42,0,0-16-15-63-19,2.21-9.63,10.25-38.95,38-62C223.08-.3,259.11.01,268,.34c6.62-.58,44.71-3.29,77,25,34.89,30.57,35.08,72.87,35,79-.47,12.94-2.52,32.6-10.75,54.62-5.1,13.65-13.95,31.88-58.25,83.38-12.44,14.46-29.6,33.78-51,56-4.66,10.91-13.1,25.28-26.75,39.38-18.75,19.38-48.25,38.12-91.25,35.62,0,0-47,0-76-35v124H0V104.34Z"/>
          <path class="p2p-logo-animated-path p2p-path-2" d="M268,300.34h34v62.32h-84.47s27.47-16.32,50.47-62.32Z"/>
          <path class="p2p-logo-animated-path p2p-path-3" d="M378,256.34v206h-66v-206s61-55,77-130c0,0,63.34-65,147.17,0,0,0,54.17,41,48,113.5,0,0-4.17,98.5-73.17,122.5,0,0-66,33-124-14v-65s28,39,78,28c0,0,51-13,53-69,0,0,8-61-52-82-3.86-.81-42.87-8.32-69.57,17.65-15.33,14.9-19.32,33.75-20.43,39.35-3.47,17.49-.17,31.85,2,39Z"/>
        </g>
      </g>
    </svg>
    <div class="progress-bar-container">
        <div class="progress-bar-fill" id="progressBarFill"></div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const paths = [
            { el: document.querySelector('.p2p-path-1'), length: 0 },
            { el: document.querySelector('.p2p-path-2'), length: 0 },
            { el: document.querySelector('.p2p-path-3'), length: 0 }
        ];
        const progressBarFill = document.getElementById('progressBarFill');
        const loaderOverlay = document.getElementById('loaderOverlay');
        let pathsFound = true;

        paths.forEach(pathObj => {
            if (pathObj.el) {
                pathObj.length = pathObj.el.getTotalLength();
                pathObj.el.style.strokeDasharray = pathObj.length;
                pathObj.el.style.strokeDashoffset = pathObj.length;
            } else {
                console.error('SVG path not found for animation: ', pathObj);
                pathsFound = false;
            }
        });

        if (!pathsFound || !progressBarFill) {
            console.error('Required elements for loader animation are missing.');
            window.addEventListener('load', function() { 
                setTimeout(() => {
                    if (loaderOverlay) document.body.classList.add('loaded');
                }, 500);
            });
            return;
        }

        let progress = 0;
        const animationDuration = 1500; 
        let startTime = null;
        let animationFrameId = null;
        let hasWindowLoaded = false;

        function animate(timestamp) {
            if (!startTime) startTime = timestamp;
            const elapsedTime = timestamp - startTime;
            progress = Math.min(elapsedTime / animationDuration, 1);

            paths.forEach(pathObj => {
                if (pathObj.el) {
                    const offset = pathObj.length - (progress * pathObj.length);
                    pathObj.el.style.strokeDashoffset = offset;
                }
            });

            if (progressBarFill) progressBarFill.style.width = (progress * 100) + '%';

            if (progress < 1) {
                animationFrameId = requestAnimationFrame(animate);
            } else {
                paths.forEach(pathObj => {
                    if (pathObj.el) pathObj.el.style.strokeDashoffset = 0;
                });
                if (progressBarFill) progressBarFill.style.width = '100%';
                if (hasWindowLoaded) hideLoader();
            }
        }

        animationFrameId = requestAnimationFrame(animate);

        function hideLoader() {
            paths.forEach(pathObj => {
                if (pathObj.el) {
                    pathObj.el.style.strokeDashoffset = 0;
                }
            });
            if (progressBarFill) progressBarFill.style.width = '100%';

            requestAnimationFrame(() => {
                paths.forEach(pathObj => {
                    if (pathObj.el) {
                        pathObj.el.classList.add('filled');
                    }
                });

                setTimeout(() => {
                    document.body.classList.add('loaded');
                }, 450); 
            });
        }

        window.addEventListener('load', function() {
            hasWindowLoaded = true;
            if (progress >= 1) {
                hideLoader();
            }
        });
    });
</script>