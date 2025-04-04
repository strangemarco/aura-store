document.addEventListener("DOMContentLoaded", () => {
    
    let carrito = JSON.parse(localStorage.getItem("carrito")) || [];
    const listaCarrito = document.getElementById("lista-carrito");
    const totalCarrito = document.getElementById("total-carrito");
    const vaciarCarritoBtn = document.getElementById("vaciar-carrito");
    const comprarBtn = document.getElementById("realizar-compra");
  
    function actualizarCarrito() {
      listaCarrito.innerHTML = "";
      let total = 0;
  
      if (carrito.length === 0) {
        listaCarrito.innerHTML = "<p>Tu carrito está vacío.</p>";
      }
  
      carrito.forEach((producto, index) => {
        total += producto.precio * producto.cantidad;
  
        const li = document.createElement("li");
        li.innerHTML = `
          <div class="carrito-item">
            <img src="${producto.imagen}" alt="${producto.nombre}" class="carrito-img">
            <div class="carrito-detalle">
              <strong>${producto.nombre}</strong><br>
              Precio: $${producto.precio.toFixed(2)}<br>
              Cantidad: ${producto.cantidad}
            </div>
            <button class="eliminar-producto" data-index="${index}">🗑</button>
          </div>
        `;
        listaCarrito.appendChild(li);
      });
  
      totalCarrito.textContent = `Total: $${total.toFixed(2)}`;
      localStorage.setItem("carrito", JSON.stringify(carrito));
    }
  
    document.addEventListener("click", (e) => {
      if (e.target.classList.contains("agregar-carrito")) {
        const producto = {
          id: e.target.dataset.id,
          nombre: e.target.dataset.nombre,
          precio: parseFloat(e.target.dataset.precio),
          imagen: e.target.dataset.imagen,
          cantidad: 1
        };
  
        const existe = carrito.find(p => p.id === producto.id);
        if (existe) {
          existe.cantidad++;
        } else {
          carrito.push(producto);
        }
  
        localStorage.setItem("carrito", JSON.stringify(carrito));
        alert(`"${producto.nombre}" se añadió al carrito`);
        actualizarCarrito();
      }
  
      if (e.target.classList.contains("eliminar-producto")) {
        const index = e.target.dataset.index;
        carrito.splice(index, 1);
        actualizarCarrito();
      }
    });
  
    vaciarCarritoBtn?.addEventListener("click", () => {
      if (carrito.length === 0) {
        alert("El carrito ya está vacío.");
        return;
      }
      if (confirm("¿Seguro que deseas vaciar el carrito?")) {
        carrito = [];
        actualizarCarrito();
      }
    });
  
    comprarBtn?.addEventListener("click", () => {
      const metodoPago = document.getElementById("metodoPago")?.value;
  
      if (carrito.length === 0) {
          alert("Tu carrito está vacío.");
          return;
      }
  
      if (!metodoPago) {
          alert("Por favor selecciona un método de pago.");
          return;
      }
  
      // ✨ Capturamos todos los datos
      const nombre = document.getElementById("nombre")?.value;
      const correoInput = document.getElementById("correo");
      const correo = correoInput ? correoInput.value.trim() : "";
      
      if (!correo) {
          alert("Por favor ingresa un correo electrónico válido.");
          return;
      }
      
      const carnet = document.getElementById("carnet")?.value;
      const telefono = document.getElementById("telefono")?.value;
      const direccion = document.getElementById("direccion")?.value;
  
      if (!nombre || !correo || !carnet || !telefono || !direccion) {
          alert("Por favor completa todos los campos de entrega.");
          return;
      }
  
      const clienteID = localStorage.getItem("cliente_id");

  

  
      fetch("registrar_compra.php", {
          method: "POST",
          headers: {
              "Content-Type": "application/json"
          },
          body: JSON.stringify({
              carrito,
              clienteID,
              metodoPago,
              nombre,
              correo, // <--- NUEVO
              carnet,
              telefono,
              direccion
          })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
            carrito = [];
            localStorage.setItem("carrito", JSON.stringify(carrito));
            mostrarModal(); // 🎉 Mostrar modal
        } else {
            alert("Error: " + data.error);
        }
    })
    
    .catch(err => {
        console.error(err);
        alert("Ocurrió un error al procesar la compra.");
    });
    
  });
  
  
    actualizarCarrito();



    
  });
  