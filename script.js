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

// função sticky para barra lateral de pedidos

const ctnPedidos = document.querySelector(".fixed-column");
const filtroIcone = document.querySelector(".icone-filtro");


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
        ctnPedidos.style.top = "0px"

    }if(filtroExpand === "true"){
        setTimeout(() =>{
            var ctnPosition = document.querySelector(".fixed-column").getBoundingClientRect().top
            ctnPedidos.style.height = `calc(${windowHight - ctnPosition}px - 1rem)`

            }, 300);     
        }
    }
filtroIcone.addEventListener('click', pedidosFixed);
window.addEventListener('scroll', pedidosFixed);

// atualização icones status

var statusPedido = document.querySelectorAll(".icone-status")

statusPedido.forEach(pedido =>{
    var status = pedido.getAttribute("title");
    if(status === "Recebido"){
        pedido.classList.add("fa-solid", "fa-cart-flatbed");
    }if(status === "Finalizado"){
        pedido.classList.add("fa-solid", "fa-circle-check");
        pedido.style.color = "#157347";
    }if(status === "Confirmado"){
        pedido.classList.add("fa-solid", "fa-thumbs-up");
        pedido.style.color = "#F2CB05";
    }if(status === "Preparando"){
        pedido.classList.add("fa-solid", "fa-boxes-packing");
        pedido.style.color = "#F2CB05";
    }if(status === "Faturando"){
        pedido.classList.add("fa-solid", "fa-cash-register");
        pedido.style.color = "#F2CB05";
    }if(status === "Novo"){
        pedido.classList.add("fa-solid", "fa-plus");
    }if(status === "Entregando"){
        pedido.classList.add("fa-solid", "fa-truck");
        pedido.style.color = "#F2CB05";
    }if(status === "Entregue"){
        pedido.classList.add("fa-solid", "fa-circle-check");
        pedido.style.color = "#157347";
    }if(status === "Cancelado"){
        pedido.classList.add("fa-solid", "fa-ban");
        pedido.style.color = "tomato";
    }
    
})



// chamar conteúdo para iframe
$('.box-pedido').on('click',function(){
    // console.log($(this).attr('data-codigo'));
    $('.background-right').css("display", "none");
    $('#pedido_edicao').css("display", "inherit");
    $('#pedido_edicao').attr('src','info-pedido.php?codigo='+$(this).attr('data-codigo'));
 });