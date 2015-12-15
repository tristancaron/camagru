/**
 * Created by tristan on 09/12/2015.
 */

'use strict';

(() => {
    const loginTab = document.querySelector('.login-tab');
    const loginContent = document.querySelector('#login-tab-content');
    const signupTab = document.querySelector('.signup-tab');
    const signupContent = document.querySelector('#signup-tab-content');
    const forgetLink = document.querySelector('#forget-password');
    const forgetContent = document.querySelector('#forget-tab-content');

    loginTab.addEventListener('click', event => {
        event.preventDefault();

        if (!loginContent.classList.contains('active'))
            loginContent.classList.add('active');

        if (signupContent.classList.contains('active'))
            signupContent.classList.remove('active');

        if (forgetContent.classList.contains('active'))
            forgetContent.classList.remove('active');

        if (!loginTab.firstElementChild.classList.contains('active')) {
            loginTab.firstElementChild.classList.add('active');
            signupTab.firstElementChild.classList.remove('active');
        }
    });

    signupTab.addEventListener('click', event => {
        event.preventDefault();

        if (!signupContent.classList.contains('active'))
            signupContent.classList.add('active');

        if (loginContent.classList.contains('active'))
            loginContent.classList.remove('active');

        if (forgetContent.classList.contains('active'))
            forgetContent.classList.remove('active');

        if (!signupTab.firstElementChild.classList.contains('active')) {
            signupTab.firstElementChild.classList.add('active');
            loginTab.firstElementChild.classList.remove('active');
        }
    });

    forgetLink.addEventListener('click', event => {
        event.preventDefault();

        if (signupContent.classList.contains('active'))
            signupContent.classList.remove('active');

        if (loginContent.classList.contains('active'))
            loginContent.classList.remove('active');

        if (!forgetContent.classList.contains('active'))
            forgetContent.classList.add('active');

        signupTab.firstElementChild.classList.remove('active');
        loginTab.firstElementChild.classList.remove('active');
    });
})();
