# Webshop

Welcome to the **Webshop** project! This application is developed as part of my university coursework, focusing on enhancing the online shopping experience. It includes various features designed to facilitate seamless interactions for users.

## Features

- **Login Functionality**: Users can log in using their email address (as a username) and a password, with validation for both fields before submission.
- **User Registration**: New users can register by providing their email address, which must be unique and not empty. Upon registration, a randomly generated password is sent to the user via email for confirmation.
- **Database Design**: The database includes tables for customers, logs, products, invoice headers and items, points, and shopping cart, with passwords securely stored using SHA512 encryption.
- **Points Bonus System**: Users earn points for logins (2 points) and purchases (25 points), which are displayed after login and can be redeemed at checkout (100 points = $0.10).
- **Product Listings**: Showcase products with high-quality images, pricing, and specifications.
- **Shopping Cart Management**: Users can add items to their cart, remove them, and adjust quantities, with discounts applied for purchasing multiple identical items.
- **Checkout Process**: Each order receives a unique order number, and users can choose from various shipping options, with a checkbox for confirming privacy policy acceptance during the checkout process.
- **Order History**: Users can easily access past orders and reorder items.
- **Email Confirmation**: After payment, users receive an email containing their order details, including an invoice.
- **User Interface**: A navigation bar displays a cart icon showing the number of items, and a welcoming message appears on the homepage after login, along with a carousel display.
- **Security and Accessibility**: Certain pages require user login to access, while product listings are available without login, ensuring a clear and user-friendly design.
- **Two-Factor Authentication**: Enhance security with two-factor authentication for user accounts.
- **Password Reset**: Allow users to securely reset their passwords if forgotten.
- **Logout**: Securely log out of user accounts to protect personal information.

## Technologies Used

- **PHP Mailer**: Utilized for sending emails, such as password resets and order confirmations.
- **PHPGangsta**: Implemented for handling two-factor authentication.
- **Bootstrap**: Used for responsive styling to ensure a modern and user-friendly interface.
- **Custom CSS**: Additional styling to tailor the look and feel of the webshop.
- **Microsoft SQL Server**: The chosen database for storing user and product information.

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

5. **Environment Configuration**:

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
    ```

    Make sure to replace the placeholders with your actual database and email configuration details.

6. **Start the Application Using Docker**:
    ```bash
    docker-compose up
    ```

7. **To Stop the Application**:
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

This project is licensed under the GNU General Public License v3.0 - see the [LICENSE](LICENSE) file for details.

## Acknowledgments

- Special thanks to my university for providing the resources and support for this project.

Feel free to explore the code, and happy shopping!
