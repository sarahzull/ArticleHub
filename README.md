## Installation

### Prerequisites
- PHP >= 8.2
- Composer
- Node.js >= 20.x
- npm
- MySQL or compatible database server

### Steps

1. **Clone the repository:**
    ```bash
    git clone https://github.com/sarahzull/ArticleHub.git
    cd ArticleHub
    ```

2. **Install PHP dependencies:**
    ```bash
    composer install
    ```

3. **Install JavaScript dependencies:**
    ```bash
    npm install
    ```

4. **Download .env file:**
   - Go to [Access Link](https://shorturl.at/kyzH1)
   - Request access, and after access is granted, download the `.env.articlehub` file.
   - Place the downloaded file in the project folder and rename it to `.env`.
   - Update database credentials in `.env` file.

5. **Generate application key:**
    ```bash
    php artisan key:generate
    ```

6. **Run database migrations:**
    ```bash
    php artisan migrate
    ```

## Usage

### Running the server for development:

```bash
php artisan serve
npm run dev
