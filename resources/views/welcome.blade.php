<!DOCTYPE html>
<html>
<head>
    <title>Product App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="p-5">

<div class="container">

    <h2>Add Product</h2>

    <form id="productForm" class="card p-4 shadow-sm mb-4">
        <input type="text" name="name" placeholder="Product Name" class="form-control mb-2" required>
        <input type="number" name="quantity" placeholder="Quantity" class="form-control mb-2" required>
        <input type="number" name="price" placeholder="Price" class="form-control mb-2" required>
        <button class="btn btn-primary w-100">Submit</button>
    </form>

    <h3>Products</h3>

    {{-- SCROLL CONTAINER FIXED  --}}
    <div style="max-height: 300px; overflow-y: auto;">

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody id="productTable"></tbody>

            <tfoot>
                <tr>
                    <th colspan="4">Sum Total</th>
                    <th id="sumTotal">0</th>
                    <th></th>
                </tr>
            </tfoot>

        </table>

    </div>
</div>

<script>
const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

let products = [];
let editingIndex = null;

// LOAD TABLE
function loadProducts() {
    fetch('/products')
    .then(res => res.json())
    .then(data => {

        products = data;

        let html = '';
        let sum = 0;

        if (data.length === 0) {
            html = `<tr><td colspan="6" class="text-center">No products yet</td></tr>`;
        } else {

        data.forEach((p, index) => {

            html += `
                <tr>
                    <td>${p.name}</td>
                    <td>${p.quantity}</td>
                    <td>${Number(p.price).toFixed(2)}</td>
                    <td>${p.created_at}</td>
                    <td>${Number(p.total).toFixed(2)}</td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick="editProduct(${index})">Edit</button>
                        <button class="btn btn-sm btn-danger" onclick="deleteProduct(${index})">Delete</button>
                    </td>
                </tr>
            `;

            sum += Number(p.total);
        });
        }

        document.getElementById('productTable').innerHTML = html;
        document.getElementById('sumTotal').innerText = sum.toFixed(2);
    });
}

// EDIT FUNCTION
function editProduct(index) {
    const p = products[index];

    document.querySelector('[name=name]').value = p.name;
    document.querySelector('[name=quantity]').value = p.quantity;
    document.querySelector('[name=price]').value = p.price;

    editingIndex = index;
}

function deleteProduct(index) {
    if (!confirm("Are you sure you want to delete this item?")) return;

    const formData = new FormData();
    formData.append('index', index);

    fetch('/products/delete', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrf
        },
        body: formData
    })
    .then(() => loadProducts());
}

// SUBMIT FORM
document.getElementById('productForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    if (editingIndex !== null) {
        formData.append('index', editingIndex);
    }

    fetch('/products', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrf },
        body: formData
    })
    .then(() => {
        this.reset();
        editingIndex = null;
        loadProducts();
    });
});

// INIT
loadProducts();
</script>