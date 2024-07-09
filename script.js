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