document.addEventListener('DOMContentLoaded', function() {
    const quantityInputs = document.querySelectorAll('.quantity-input');

    quantityInputs.forEach(input => {
        input.addEventListener('input', function() {
            const productId = this.previousElementSibling.value;
            const newQuantity = this.value;
            const priceElement = this.closest('tr').querySelector('.product-price');
            const pricePerItem = parseFloat(priceElement.dataset.pricePerItem);

            // Update subtotal
            const newSubtotal = newQuantity * pricePerItem;
            priceElement.textContent = newSubtotal;

            // Update total
            let newTotal = 0;
            document.querySelectorAll('.product-price').forEach(priceEl => {
                newTotal += parseFloat(priceEl.textContent.replace('Rp ', ''));
            });
            document.querySelector('.cart-total .total-price').textContent = 'Rp ' + newTotal;

            // Send AJAX request to update session
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'keranjang.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.send('ajax=1&product_id=' + productId + '&product_quantity=' + newQuantity);

            xhr.onload = function() {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    document.querySelectorAll('.total-price').forEach(totalPriceEl => {
                        totalPriceEl.textContent = 'Rp ' + response.total;
                    });
                }
            };
        });
    });
});
