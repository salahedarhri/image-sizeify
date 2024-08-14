<p align="center"><a href="https://www.linkedin.com/in/salaheddinarhrimaz" target="_blank"><img src="https://github.com/salahedarhri/image-sizeify/blob/main/IS%20banner.jpg" width="800" alt="IS Logo"></a></p>

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

## Screenshots

<img src="https://github.com/salahedarhri/image-sizeify/blob/main/home-light.png" width="100%" alt="Image Sizeify Home">
<img src="https://github.com/salahedarhri/image-sizeify/blob/main/home.png" width="100%" alt="Image Sizeify Home Dark">
<img src="https://github.com/salahedarhri/image-sizeify/blob/main/images-light.png" width="100%" alt="Image Sizeify Images">
<img src="https://github.com/salahedarhri/image-sizeify/blob/main/images.png" width="100%" alt="Image Sizeify Images Dark">
<img src="https://github.com/salahedarhri/image-sizeify/blob/main/donwload-light.png" width="100%" alt="Image Sizeify Download">
<img src="https://github.com/salahedarhri/image-sizeify/blob/main/download.png" width="100%" alt="Image Sizeify Donwload Dark">
<img src="https://github.com/salahedarhri/image-sizeify/blob/main/downloaded-light.png" width="100%" alt="Image Sizeify Downloaded">
<img src="https://github.com/salahedarhri/image-sizeify/blob/main/downloaded.png" width="100%" alt="Image Sizeify Downloaded Dark">





