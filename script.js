document.addEventListener("DOMContentLoaded", () => {
    const carrito = [];
    const listaCarrito = document.getElementById("lista-carrito");
    const totalCarrito = document.getElementById("total-carrito");
    const vaciarCarritoBtn = document.getElementById("vaciar-carrito");

    // Agregar productos al carrito
    document.querySelectorAll(".agregar-carrito").forEach(button => {
        button.addEventListener("click", () => {
            const producto = {
                id: button.dataset.id,
                nombre: button.dataset.nombre,
                precio: parseFloat(button.dataset.precio)
            };

            carrito.push(producto);
            actualizarCarrito();
        });
    });
    

    // Actualizar la vista del carrito
    function actualizarCarrito() {
        listaCarrito.innerHTML = "";
        let total = 0;

        carrito.forEach((producto, index) => {
            total += producto.precio;
            const li = document.createElement("li");
            li.textContent = `${producto.nombre} - $${producto.precio.toFixed(2)}`;
            
            // Botón para eliminar producto
            const eliminarBtn = document.createElement("button");
            eliminarBtn.textContent = "X";
            eliminarBtn.style.marginLeft = "10px";
            eliminarBtn.addEventListener("click", () => {
                carrito.splice(index, 1);
                actualizarCarrito();
            });

            li.appendChild(eliminarBtn);
            listaCarrito.appendChild(li);
        });

        totalCarrito.textContent = `Total: $${total.toFixed(2)}`;
    }

    // Vaciar carrito
    vaciarCarritoBtn.addEventListener("click", () => {
        carrito.length = 0;
        actualizarCarrito();
    });

    



    
});
