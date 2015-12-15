/**
 * Created by tristan on 09/12/2015.
 */
'use strict';

(() => {
    // *************************************************************************
    // Global use
    // *************************************************************************

    let source = null;

    const pictureView = document.querySelector('#picture-view');
    const context = pictureView.getContext('2d');

    const layers = new Array(2);

    const drawLayers = () => {
        pictureView.width = 320;
        pictureView.height = 240;

        if (layers[0])
            context.drawImage(layers[0], 0, 0, 320, 240);
        if (layers[1])
            context.drawImage(layers[1], 0, 0, 320, 240);
    };

    // *************************************************************************
    // Take a picture
    // *************************************************************************

    const getMedia = navigator.webkitGetUserMedia || navigator.mozGetUserMedia;
    const snapButton = document.querySelector('#input-webcam');

    snapButton.addEventListener('click', () => {
        getMedia.bind(navigator)({
            video: true,
            audio: false
        }, stream => {
            const tmp = document.createElement('video');

            tmp.addEventListener('canplay', () => {
                layers[0] = tmp;
                tmp.pause();
                drawLayers();
                stream.getTracks()[0].stop();

                source = 'webcam';
            });
            tmp.src = window.URL.createObjectURL(stream);
        }, console.error.bind(console));
    });

    // *************************************************************************
    // Get local picture
    // *************************************************************************

    const inputLocal = document.querySelector('#input-local');

    inputLocal.addEventListener('change', () => {
        const localPicture = new Image();

        localPicture.addEventListener('load', () => {
            layers[0] = localPicture;

            drawLayers();
            source = 'local';
        });

        localPicture.src = window.URL.createObjectURL(inputLocal.files[0]);
    });

    // *************************************************************************
    // Filter select
    // *************************************************************************

    const filterSelect = document.querySelector('#input-filter');

    filterSelect.addEventListener('change', () => {
        const localPicture = new Image();

        localPicture.addEventListener('load', () => {
            layers[1] = localPicture;

            drawLayers();
        });

        localPicture.src = filterSelect.selectedOptions[0].value;
    });

    // *************************************************************************
    // Intercept form submit
    // *************************************************************************

    const form = document.querySelector('form');

    form.addEventListener('submit', event => {
        event.preventDefault();

        const formData = new FormData();

        if (source === 'webcam') {
            const tmp = document.createElement('canvas');
            tmp.width = 320;
            tmp.height = 240;

            tmp.getContext('2d').drawImage(layers[0], 0, 0, 320, 240);

            const binStr = atob(tmp.toDataURL().split(',')[1]);
            const len = binStr.length;
            const arr = new Uint8Array(len);

            for (let i = 0; i < len; i++) {
                arr[i] = binStr.charCodeAt(i);
            }

            formData.append('picture', new Blob([arr], {type: 'image/png'}));
        } else {
            formData.append('picture', form['picture'].files[0]);
        }

        formData.append('filter', form['filter'].selectedOptions[0].value);

        fetch(form.action, {
            method: 'POST',
            credentials: 'same-origin',
            body: formData
        }).then(() => {
            window.location.reload();
        });
    });
})();