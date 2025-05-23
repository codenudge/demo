# Codenudge demo

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

### How to Use
1. Access the `/products` URL in your browser
2. View the list of products displayed in a table format
3. If no products exist, a message will be displayed
