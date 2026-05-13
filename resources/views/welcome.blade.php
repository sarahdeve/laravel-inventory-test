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

    <form id="productForm" class="mb-4">
        <input type="text" name="name" placeholder="Product Name" class="form-control mb-2" required>
        <input type="number" name="quantity" placeholder="Quantity" class="form-control mb-2" required>
        <input type="number" name="price" placeholder="Price" class="form-control mb-2" required>
        <button class="btn btn-primary">Submit</button>
    </form>

    <h3>Products</h3>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Date</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody id="productTable"></tbody>

        <tfoot>
            <tr>
                <th colspan="4">Sum Total</th>
                <th id="sumTotal">0</th>
            </tr>
        </tfoot>
    </table>

</div>

<script>
const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// LOAD TABLE
function loadProducts() {
    fetch('/products')
    .then(res => res.json())
    .then(data => {
        let html = '';
        let sum = 0;

        data.forEach(p => {
            html += `
                <tr>
                    <td>${p.name}</td>
                    <td>${p.quantity}</td>
                    <td>${p.price}</td>
                    <td>${p.created_at}</td>
                    <td>${p.total}</td>
                </tr>
            `;
            sum += p.total;
        });

        document.getElementById('productTable').innerHTML = html;
        document.getElementById('sumTotal').innerText = sum;
    });
}

// SUBMIT FORM (AJAX)
document.getElementById('productForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('/products', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrf
        },
        body: formData
    })
    .then(() => {
        this.reset();
        loadProducts();
    });
});

// INITIAL LOAD
loadProducts();
</script>

</body>
</html>