<p align="center"><a href="https://www.linkedin.com/in/salaheddinarhrimaz" target="_blank"><img src="https://github.com/salahedarhri/image-sizeify/blob/main/IS%20banner.jpeg" width="800" alt="IS Logo"></a></p>

# Image Sizeify

ImageSizeify is a powerful tool that allows users to resize images to specific dimensions, supporting multiple image formats. It offers predefined widths to choose from, making it easy to scale images for different use cases.It also provides the code adapted to it so the user can simply copy and paste it where needed.
 
## Requirements

- PHP (>=8.2)
- Composer
- MySQL >= 5.7 ( or sqlite >= 3.8.8 )
- Node.js & npm 

## Installation

1. Clone the repository :
    ```bash
    git clone https://github.com/salahedarhri/image-sizeify.git
    ```

2. Navigate to the project directory & Install :
    ```bash
    cd image-sizeify
    composer install
    npm install
    ```
    
4. Copy .env.example and fill it to connect to a mysql database :
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

5. Migrate:
    ```bash
    php artisan migrate
    ```

## Usage

To use Image Sizeify, Start the local development server in the bash terminal with :
    ```bash  
    php artisan serve 
    ```


