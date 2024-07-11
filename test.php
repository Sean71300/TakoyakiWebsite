<html>
    <head>
        <title>Quantity Adjustment</title>
        <style>
            body {
                display: flex;
                justify-content: center;
                align-items: center;
            }
            button {
                font-size: 24px;
                padding: 8px 12px;
                margin: 0 8px;
            }
            #number {
                font-size: 24px;
                min-width: 40px;
                text-align: center;
            }
        </style>
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                const quantity = document.getElementById('number');
                const addNumber = document.getElementById('add');
                const minusNumber = document.getElementById('minus');

                let number = 1;

                addNumber.addEventListener('click', () => {
                    number++;
                    quantity.textContent = number;
                });

                minusNumber.addEventListener('click', () => {
                    if (number > 1) {
                        number--;
                        quantity.textContent = number;
                    }
                });
            });
        </script>
    </head>

    <body>
        <button id="minus">-</button>
        <span id="number">1</span>
        <button id="add">+</button>
        <span>Lorem</span>
    </body>
</html>