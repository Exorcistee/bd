
async function fetchProducts() {
    const response = await fetch("/api/products/get.php");
    const data = await response.json();

    if (data.success) {
        const productList = document.getElementById("product-list");
        productList.innerHTML = ""; // Очистка списка

        data.products.forEach(product => {
            const productItem = document.createElement("div");
            productItem.className = "product-item";
            productItem.innerHTML = `
                <h3>${product.name}</h3>
                <p>${product.description}</p>
                <p>Цена: ${product.price}₽</p>
            `;
            productList.appendChild(productItem);
        });
    } else {
        console.error(data.error || "Ошибка при загрузке продуктов");
    }
}

fetchProducts();

async function addToCart(productId) {
    try {
        const response = await fetch('/bd/api/carts/add_to_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: 1 
            }),
        });

        const data = await response.json();

        if (data.success) {
            alert('Товар добавлен в корзину');
        } else {
            alert(data.message);
        }
    } catch (error) {
        console.error('Ошибка при добавлении в корзину:', error);
        alert('Произошла ошибка при добавлении в корзину.');
    }
}

fetchProducts();