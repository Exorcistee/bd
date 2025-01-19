document.getElementById('add-category-form').addEventListener('submit', async function (e) {
    e.preventDefault();

    const categoryName = document.getElementById('category-name').value;

    try {
        const response = await fetch('/bd/api/categories/add_category.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                category_name: categoryName,
            }),
        });

        const result = await response.json();

        if (result.success) {
            alert('Категория успешно добавлена!');
            addCategoryToTable(result.category);
            document.getElementById('category-name').value = '';
        } else {
            alert(`Ошибка: ${result.message}`);
        }
    } catch (error) {
        console.error('Ошибка:', error);
        alert('Не удалось добавить категорию. Попробуйте позже.');
    }
});

// Функция для динамического добавления строки в таблицу
function addCategoryToTable(category) {
    const tableBody = document.querySelector('#categories-table tbody');
    const newRow = document.createElement('tr');

    newRow.innerHTML = `
        <td>${category.id}</td>
        <td>${category.category_name}</td>
        <td>
            <button onclick="deleteCategory(${category.id})">Удалить</button>
        </td>
    `;

    tableBody.appendChild(newRow);
}

// Загрузка товаров
async function loadProducts() {
    const response = await fetch('/bd/api/admin/get_product.php');
    const data = await response.json();

    console.log('Ответ API:', data); // Вывод ответа в консоль для отладки

    if (data.success) {
        const tableBody = document.querySelector('#products-table tbody');
        tableBody.innerHTML = '';

        data.products.forEach(product => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${product.id}</td>
                <td>${product.name}</td>
                <td>${product.description}</td>
                <td>${parseFloat(product.price).toFixed(2)} руб.</td>
                <td>
                    <button onclick="deleteProduct(${product.id})">Удалить</button>
                </td>
            `;
            tableBody.appendChild(row);
        });
    } else {
        alert('Ошибка при загрузке списка товаров: ' + data.message);
    }
}

// Удаление товара
async function deleteProduct(productId) {
    const response = await fetch('/bd/api/admin/delete_product.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ product_id: productId }),
    });

    const data = await response.json();
    if (data.success) {
        alert('Товар удален');
        loadProducts(); 
    } else {
        alert('Ошибка: ' + data.message);
    }
}

async function loadCategories() {
    try {
        const response = await fetch('/bd/api/categories/get_categories.php');
        const data = await response.json();

        const categorySelect = document.getElementById('product-category');
        
        if (data.success) {
            categorySelect.innerHTML = '<option value="" disabled selected>Выберите категорию</option>';
            
            data.categories.forEach(category => {
                const option = document.createElement('option');
                option.value = category.id;
                option.textContent = category.category_name;
                categorySelect.appendChild(option);
            }); 
        } else {
            alert('Ошибка при загрузке категорий: ' + data.message);
        }
    } catch (error) {
        console.error('Ошибка при загрузке категорий:', error);
        alert('Не удалось загрузить категории. Попробуйте позже.');
    }
}

document.addEventListener('DOMContentLoaded', loadCategories);

async function deleteCategory(categoryId) {
    try {
        const response = await fetch(`/bd/api/categories/delete_category.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: categoryId }),
        });

        const result = await response.json();

        if (result.success) {
            alert('Категория успешно удалена!');
            loadCategories(); 
        } else {
            alert('Ошибка при удалении категории: ' + result.message);
        }
    } catch (error) {
        console.error('Ошибка при удалении категории:', error);
        alert('Не удалось удалить категорию. Попробуйте позже.');
    }
}

document.addEventListener('DOMContentLoaded', loadCategories);

document.addEventListener("DOMContentLoaded", () => {
    const reviewsTable = document.getElementById("reviews-table").querySelector("tbody");

    function loadReviews() {
        fetch('api/reviews/get_reviews.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    reviewsTable.innerHTML = ""; 
                    data.data.forEach(review => {
                        const row = document.createElement("tr");
                        row.innerHTML = `
                            <td>${review.id}</td>
                            <td>${review.user_id}</td>
                            <td>${review.product_id}</td>
                            <td>${review.rating}</td>
                            <td>${review.review_text}</td>
                            <td><button class="delete-button" data-id="${review.id}">Удалить</button></td>
                        `;
                        reviewsTable.appendChild(row);
                    });

                    document.querySelectorAll(".delete-button").forEach(button => {
                        button.addEventListener("click", () => {
                            const reviewId = button.getAttribute("data-id");
                            deleteReview(reviewId);
                        });
                    });
                } else {
                    console.error(data.message);
                }
            })
            .catch(error => console.error("Ошибка загрузки отзывов:", error));
    }

    function deleteReview(id) {
        fetch('api/reviews/delete_reviews.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Отзыв успешно удален.");
                    loadReviews(); 
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error("Ошибка при удалении отзыва:", error));
    }

    loadReviews();
});

document.addEventListener("DOMContentLoaded", () => {
    const usersTable = document.getElementById("users-table").querySelector("tbody");

    function loadUsers() {
        fetch('api/users/get_users.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    usersTable.innerHTML = ""; 
                    data.data.forEach(user => {
                        const row = document.createElement("tr");
                        row.innerHTML = `
                            <td>${user.id}</td>
                            <td>${user.name}</td>
                            <td>${user.login}</td>
                            <td>${user.password}</td>
                            <td>
                                <select class="user-role" data-id="${user.id}">
                                    <option value="1" ${user.role_id == 1 ? "selected" : ""}>Пользователь</option>
                                    <option value="2" ${user.role_id == 2 ? "selected" : ""}>Менеджер</option>
                                    <option value="3" ${user.role_id == 3 ? "selected" : ""}>Администратор</option>
                                </select>
                            </td>
                            <td>${user.cart_id}</td>
                            <td>
                                <button class="delete-user-button" data-id="${user.id}">Удалить</button>
                            </td>
                        `;
                        usersTable.appendChild(row);
                    });

                    document.querySelectorAll(".delete-user-button").forEach(button => {
                        button.addEventListener("click", () => {
                            const userId = button.getAttribute("data-id");
                            deleteUser(userId);
                        });
                    });

                    document.querySelectorAll(".user-role").forEach(select => {
                        select.addEventListener("change", (event) => {
                            const userId = select.getAttribute("data-id");
                            const newRoleId = event.target.value;
                            updateUserRole(userId, newRoleId);
                        });
                    });
                }
            })
            .catch(error => console.error("Ошибка загрузки пользователей:", error));
    }

    function deleteUser(id) {
        fetch('api/users/delete_user.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Пользователь успешно удален.");
                    loadUsers();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error("Ошибка при удалении пользователя:", error));
    }

    function updateUserRole(id, roleId) {
        fetch('api/users/update_role.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id, role_id: roleId })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Роль пользователя успешно обновлена.");
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error("Ошибка при обновлении роли пользователя:", error));
    }


    loadUsers();
});

document.getElementById('add-product-form').addEventListener('submit', async (e) => {
    e.preventDefault();  

    const formData = new FormData(e.target); 

    try {
        const response = await fetch('/bd/api/admin/add_product.php', {
            method: 'POST',
            body: formData, 
        });

        const data = await response.json(); 

        if (data.success) {
            alert('Товар успешно добавлен');
            e.target.reset();  
            loadProducts();
        } else {
            alert('Ошибка: ' + data.message);  
        }
    } catch (error) {
        console.error('Ошибка при отправке формы:', error);
        alert('Произошла ошибка при добавлении товара.');
    }
});

loadProducts();