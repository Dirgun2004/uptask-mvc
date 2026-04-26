const mobileMenu = document.querySelector('#mobile-menu');
const sideBar = document.querySelector('.sidebar')
const tituloUpTask = document.querySelector('#titulo-uptask');

if(mobileMenu){
    mobileMenu.addEventListener('click', function(){

        sideBar.classList.toggle('mostrar');
        tituloUpTask.classList.toggle('ocultar');
    });
}

window.addEventListener('resize', function(){
    const anchoPantalla = document.body.clientWidth;
    if(anchoPantalla > 768){
        sideBar.classList.remove('mostrar');
        tituloUpTask.classList.remove('ocultar');
    }
})
