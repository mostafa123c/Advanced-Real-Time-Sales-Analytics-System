# Real-Time Sales Analytics System

A Laravel-based application that provides real-time analytics for sales data. The system offers dynamic product recommendations, pricing suggestions based on sales patterns, and weather-influenced marketing strategies. It utilizes real-time broadcasting to update analytics dashboards instantly when new orders are created.

## AI-Assisted Components

-   **AI Prompt Generation**: The system uses OpenAI API to generate prompts for sales recommendations. The `AIPromptGenerator` service creates structured prompts for the AI model.
-   **Weather-Based Recommendations**: Product recommendations based on current weather conditions are generated using the OpenWeatherMap API through the `WeatherRecommendationService`.

## Manual Implementation

-   **Real-time Analytics Dashboard**: Built with Laravel, Pusher for real-time updates, and frontend visualization.
-   **Order Processing and Analytics System**: Complete order management and It's Analytics.
-   **Event Broadcasting**: Custom events for real-time updates using Laravel's event broadcasting system.
-   **API Endpoints**: RESTful API for orders, analytics, and recommendations.
-   **Database Structure**: Optimized schema for tracking products, orders ana All queries made by Query Builder.

## Prerequisites

-   PHP 8.2 or higher
-   Composer
-   Node.js and NPM
-   SQLite or MySQL
-   Redis (optional, for improved caching and queues)

## Installation

### Basic Setup

1. Clone the repository:

    ```bash
    git clone https://github.com/mostafa123c/Advanced-Real-Time-Sales-Analytics-System.git
    cd Advanced-Real-Time-Sales-Analytics-System
    ```

2. Install PHP dependencies:

    ```bash
    composer install
    ```

3. Install JavaScript dependencies:

    ```bash
    npm install
    ```

4. Build frontend assets:

    ```bash
    npm run build
    ```

5. Copy the environment file:

    ```bash
    cp .env.example .env
    ```

6. Generate application key:

    ```bash
    php artisan key:generate
    ```

### Configuration

#### Database Configuration

The project is configured to use SQLite by default:

 1.For SQLite (default), ensure the database file exists:
    ```bash
    touch database/database.sqlite
    ```

#### Queue Configuration

The system uses queues for processing events and notifications:

1. By default, the queue is set to use the database:

    ```
    QUEUE_CONNECTION=database
    ```

2. If you have Redis running, you can switch to Redis for better performance:

    ```
    QUEUE_CONNECTION=redis
    REDIS_HOST=127.0.0.1
    REDIS_PASSWORD=null
    REDIS_PORT=6379
    ```

3. Start the queue worker in a separate terminal:

    ```bash
    php artisan queue:work
    ```

#### Cache Configuration

1. By default, the cache is set to use the database:

    ```
    CACHE_STORE=database
    ```

2. If you have Redis running, you can switch to Redis for better performance:

    ```
    CACHE_STORE=redis
    ```

#### API Keys Configuration

Replace the following API keys in your `.env` file with your own:

1. OpenAI Github Token API Key:

    ```
    OPENAI_GITHUB_TOKEN=your_github_token_openai_api_key
    ```

2. OpenWeatherMap API Key:

    ```
    OPENWEATHER_API_KEY=your_openweather_api_key
    ```

#### Broadcasting Configuration

The system uses Pusher for real-time updates:

1. Update the Pusher credentials in your `.env` file:
    ```
    BROADCAST_CONNECTION=pusher
    PUSHER_APP_ID=your_app_id
    PUSHER_APP_KEY=your_app_key
    PUSHER_APP_SECRET=your_app_secret
    PUSHER_APP_CLUSTER=your_app_cluster
    ```

### Database Setup

1. Run migrations to create the database tables and seed it:

    ```bash
    php artisan migrate:fresh --seed
    ```

### Storage Configuration

1. Create a symbolic link from `public/storage` to `storage/app/public`:

    ```bash
    php artisan storage:link
    ```

### Starting the Application

1. Start the development server:

    ```bash
    php artisan serve
    ```

2. Access the application at `http://localhost:8000`

## API Endpoints

### Orders

-   **Create Order**
    -   URL: `POST /api/v1/orders`
    -   Description: Creates a new order and triggers real-time analytics updates
    -   Required fields: `product_id`, `quantity`

### Analytics

-   **Get Analytics**
    -   URL: `GET /api/v1/analytics`
    -   Description: Returns current sales analytics including total revenue, top products, and revenue changes

### Recommendations

-   **Get Recommendations**

    -   URL: `GET /api/v1/recommendations`
    -   Description: Returns AI-generated sales recommendations and weather-based product suggestions

-   **Get Dynamic Pricing**

    -   URL: `GET /api/v1/pricing-suggestions`
    -   Description: Returns weather-influenced pricing suggestions for products

## Testing

### API Testing with Postman

1. Import the Postman collection provided in the project
2. Set up your environment variables in Postman:
    - `base_url`: `http://localhost:8000/`

### Running Tests

The project includes automated tests that can be run with:

```bash
php artisan test
```

## Front-End Dashboard

The application includes four main dashboard pages that can be accessed at `http://localhost:8000`:

1. **Analytics Dashboard** (`/dashboard/analytics`): Real-time sales metrics with live updates when new orders are placed. You'll see:

    - Sales performance
    - Top Products
    - Revenue changes over time
    - Real-time order notifications

2. **Recommendations Dashboard** (`/dashboard/recommendations`): AI-generated product recommendations and weather recommendations that update dynamically based on:

    - Sales patterns
    - Weather conditions
    - Customer behavior

3. **Pricing Dashboard** (`/dashboard/pricing`): Dynamic pricing suggestions with:

    - Market-based price adjustments
    - Weather-influenced pricing
    - Competitive analysis

4. **Add New Order** (`/dashboard/add-order`): Add New Order Page To:

    - Test The real-time functionality

All dashboards feature real-time updates through Pusher broadcasting, ensuring you receive instant notifications for new orders and analytics changes.
