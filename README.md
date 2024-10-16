# Webshop

Welcome to the **Webshop** project! This application is developed as part of my university coursework, focusing on enhancing the online shopping experience. It includes various features designed to facilitate seamless interactions for users.

## Features

- **Product Listings**: Explore a variety of products with detailed descriptions and images.
- **User Login**: Secure user authentication to manage personal accounts and orders.
- **Shopping Cart**: Easily add, remove, and update products in your cart for a smooth shopping experience.

## Installation

To set up your environment, create a `.env` file in the root directory of the project with the following content:

```plaintext
DB_SERVER=YOUR_DB_SERVER
DB_USERNAME=YOUR_DB_USERNAME
DB_PASSWORD=YOUR_DB_PASSWORD
DB_NAME=YOUR_DB_NAME

MAILHOST=YOUR_MAILHOST
MAIL_USERNAME=YOUR_MAIL_USERNAME
MAIL_PASSWORD=YOUR_MAIL_PASSWORD
SEND_FROM=YOUR_SEND_FROM_EMAIL
SEND_FROM_NAME=YOUR_SEND_FROM_NAME
````

Make sure to replace the placeholders with your actual database and email configuration details.

## Getting Started

1. **Clone the Repository**:
    ```bash
    git clone https://github.com/do-martin/Webshop.git
    cd webshop
    ```

2. **Install Dependencies**:
   Open your command line interface and run:
    ```bash
    composer install
    ```

3. **Set Up Your Database**:
   - Execute the SQL commands found in the `SQL` folder to set up the necessary database schema and data.
   - Replace the path in the `11_insert_into_products.sql` file with your desired path for the clothing images. Ensure that the images are accessible from your application.

4. **Add Clothing Images**:
   - Create a folder named `rsc` in the root directory of your project.
   - Add your desired clothing images into this `rsc` folder.

5. **Start the Application Using Docker**:
    ```bash
    docker-compose up
    ```

6. **To Stop the Application**:
    ```bash
    docker-compose down
    ```

## Troubleshooting

If you encounter issues starting the SQL Server, make sure the following are configured correctly:

- Check if the SQL Server service is running.
- Ensure that the ports are not being used by other applications.
- Verify your `.env` file settings for any errors.

## Contributing

Contributions are welcome! If you have suggestions for improvements or new features, feel free to open an issue or submit a pull request.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Acknowledgments

- Special thanks to my university for providing the resources and support for this project.

Feel free to explore the code, and happy shopping!
