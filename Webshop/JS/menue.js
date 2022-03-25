const menuebutton = document.getElementsByClassName('menuebutton')[0]
const navigationslinks = document.getElementsByClassName('navigations-links')[0]

menuebutton.addEventListener('click', () =>{
    navigationslinks.classList.toggle('active');
})