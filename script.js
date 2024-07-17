var boxPedido = document.querySelectorAll('.box-pedido');

function modalPedidos(){
    if(window.innerWidth < 992){
        boxPedido.forEach((pedido) =>{
            pedido.setAttribute("data-bs-toggle", "modal");
            pedido.setAttribute("data-bs-target", "#funcoes-pedido");
        })
    }else{
        boxPedido.forEach((pedido) =>{
            pedido.removeAttribute("data-bs-toggle");
            pedido.removeAttribute("data-bs-target");
        })
    }
}
window.addEventListener('resize', () => {
    modalPedidos()
})
modalPedidos();

const ctnPedidos = document.querySelector(".fixed-column");
const filtroIcone = document.querySelector(".icone-filtro");
var ctnFiltroHeight;
var windowScrollY;

function pedidosFixed(){
    var windowScrollY = window.scrollY;
    var windowHight = window.innerHeight;
    var ctnFiltroHeight = document.querySelector(".gestao-filtros").getBoundingClientRect().height;
    var filtroExpand = document.querySelector(".icone-filtro").getAttribute("aria-expanded");
    
    if(filtroExpand === "false"){
        ctnPedidos.style.top = windowScrollY + 'px';
        ctnPedidos.style.height = "auto"

    }if(windowScrollY > ctnFiltroHeight && filtroExpand === "true"){
        ctnPedidos.style.top = windowScrollY - ctnFiltroHeight + 'px';

    }if(windowScrollY < ctnFiltroHeight){
        ctnPedidos.style.top = "inherit"

    }if(filtroExpand === "true"){
        setTimeout(() =>{
            var ctnPosition = document.querySelector(".fixed-column").getBoundingClientRect().top
            ctnPedidos.style.height = `calc(${windowHight - ctnPosition}px - 1rem)`
            console.log(ctnPosition)
            }, 300);     
        }
    }
filtroIcone.addEventListener('click', pedidosFixed);
window.addEventListener('scroll', pedidosFixed);
