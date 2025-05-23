# Codenudge Symfony demo

## Features

### Product Listing
The application includes a feature to display a list of products from the database. 

- **URL**: `/products`
- **Controller**: `ProductController::index`
- **Entity**: `Product` with properties:
  - id (integer)
  - name (string)
  - price (float)
  - description (text, nullable)

### Shopping Cart
The application includes a feature to add products to a shopping cart.

- **URLs**:
  - `/cart` - View cart contents
  - `/cart/add/{id}` - Add product to cart
  - `/cart/remove/{id}` - Remove product from cart
  - `/cart/clear` - Clear cart
- **Controllers**:
  - `CartController::viewCart`
  - `CartController::addToCart`
  - `CartController::removeFromCart`
  - `CartController::clearCart`
- **Entity**: `Cart` with properties:
  - id (integer)
  - sessionId (string)
  - items (array)
  - createdAt (datetime)

### How to Use
1. Access the `/products` URL in your browser
2. View the list of products displayed in a table format
3. If no products exist, a message will be displayed
4. Click "Add to Cart" button to add a product to your cart
5. Specify the quantity of the product to add
6. View your cart by clicking "View Cart" button
7. Update quantities or remove items from your cart
8. Clear your cart by clicking "Clear Cart" button
