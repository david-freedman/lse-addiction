#!/bin/bash

# One Script to Rule Them All: Laravel + Ngrok
# Simple, reliable, no URL swapping

echo "🚀 Starting Laravel + Ngrok (Simple & Reliable)"
echo "==============================================="

# Save original URLs before making changes
echo "💾 Saving original configuration..."
ORIGINAL_APP_URL=$(grep "^APP_URL=" .env | cut -d'=' -f2-)
ORIGINAL_ASSET_URL=$(grep "^ASSET_URL=" .env | cut -d'=' -f2-)
ORIGINAL_SESSION_SECURE=$(grep "^SESSION_SECURE_COOKIE=" .env | cut -d'=' -f2-)

# Save to temporary file for restoration
echo "$ORIGINAL_APP_URL" > /tmp/laravel_ngrok_app_url.bak
if [ ! -z "$ORIGINAL_ASSET_URL" ]; then
    echo "$ORIGINAL_ASSET_URL" > /tmp/laravel_ngrok_asset_url.bak
else
    echo "null" > /tmp/laravel_ngrok_asset_url.bak
fi
if [ ! -z "$ORIGINAL_SESSION_SECURE" ]; then
    echo "$ORIGINAL_SESSION_SECURE" > /tmp/laravel_ngrok_session_secure.bak
else
    echo "false" > /tmp/laravel_ngrok_session_secure.bak
fi

# Clean up any existing processes
echo "🧹 Cleaning up existing processes..."
pkill -f "ngrok" 2>/dev/null || true
sleep 2

# Check if Docker services are running
echo "🐳 Checking Docker services..."
if ! docker compose ps | grep -q "Up"; then
    echo "❌ Docker services are not running!"
    echo "   Please start them first: docker compose up -d"
    exit 1
fi

NGINX_STATUS=$(docker compose ps nginx --format json | grep -o '"State":"[^"]*' | cut -d'"' -f4)
if [ "$NGINX_STATUS" != "running" ]; then
    echo "❌ Nginx container is not running!"
    echo "   Please start Docker services: docker compose up -d"
    exit 1
fi

echo "✅ Docker services are running"

# Build assets first
echo "📦 Building assets for production..."
npm run build
if [ $? -ne 0 ]; then
    echo "❌ Asset build failed!"
    exit 1
fi

# Copy assets to Docker container
echo "📋 Copying assets to Docker container..."
docker compose exec -T app php artisan config:clear > /dev/null 2>&1
docker compose exec -T app php artisan view:clear > /dev/null 2>&1

echo "✅ Nginx is serving the app on http://localhost"

# Start ngrok in background
echo "🌐 Starting ngrok tunnel..."
ngrok http 80 --log=stdout > /tmp/ngrok.log &
NGROK_PID=$!

# Wait for ngrok to establish tunnel
echo "⏳ Waiting for ngrok tunnel..."
sleep 8

# Get ngrok URL
echo "🔍 Getting ngrok URL..."
NGROK_URL=""
for i in {1..10}; do
    NGROK_URL=$(curl -s http://localhost:4040/api/tunnels 2>/dev/null | grep -o '"public_url":"https://[^"]*' | head -1 | cut -d'"' -f4)
    if [ ! -z "$NGROK_URL" ]; then
        break
    fi
    echo "   Attempt $i/10..."
    sleep 2
done

if [ -z "$NGROK_URL" ]; then
    echo "❌ Failed to get ngrok URL!"
    echo "   Check manually at: http://localhost:4040"
    echo "   Nginx is running at: http://localhost"
    echo ""
    echo "🛑 To stop ngrok:"
    echo "   kill $NGROK_PID"
    exit 1
fi

# Update .env file
echo "📝 Updating .env with ngrok URL..."
if [[ "$OSTYPE" == "darwin"* ]]; then
    # macOS
    sed -i '' "s|^APP_URL=.*|APP_URL=$NGROK_URL|g" .env
    sed -i '' "s|^ASSET_URL=.*|ASSET_URL=$NGROK_URL|g" .env
    sed -i '' "s|^SESSION_SECURE_COOKIE=.*|SESSION_SECURE_COOKIE=true|g" .env
    # Add ASSET_URL if it doesn't exist
    if ! grep -q "^ASSET_URL=" .env; then
        echo "ASSET_URL=$NGROK_URL" >> .env
    fi
    # Add SESSION_SECURE_COOKIE if it doesn't exist
    if ! grep -q "^SESSION_SECURE_COOKIE=" .env; then
        echo "SESSION_SECURE_COOKIE=true" >> .env
    fi
else
    # Linux
    sed -i "s|^APP_URL=.*|APP_URL=$NGROK_URL|g" .env
    sed -i "s|^ASSET_URL=.*|ASSET_URL=$NGROK_URL|g" .env
    sed -i "s|^SESSION_SECURE_COOKIE=.*|SESSION_SECURE_COOKIE=true|g" .env
    # Add ASSET_URL if it doesn't exist
    if ! grep -q "^ASSET_URL=" .env; then
        echo "ASSET_URL=$NGROK_URL" >> .env
    fi
    # Add SESSION_SECURE_COOKIE if it doesn't exist
    if ! grep -q "^SESSION_SECURE_COOKIE=" .env; then
        echo "SESSION_SECURE_COOKIE=true" >> .env
    fi
fi

# Clear and cache Laravel config to pick up new URLs
echo "🧹 Clearing and caching Laravel config..."
docker compose exec -T app php artisan config:clear > /dev/null 2>&1
docker compose exec -T app php artisan config:cache > /dev/null 2>&1
docker compose exec -T app php artisan route:clear > /dev/null 2>&1
docker compose exec -T app php artisan view:clear > /dev/null 2>&1

echo ""
echo "🎉 SUCCESS! Your Laravel app is now accessible via ngrok:"
echo ""
echo "   🌐 Public URL:  $NGROK_URL"
echo "   🏠 Local URL:   http://localhost"
echo "   📊 Ngrok Status: http://localhost:4040"
echo ""
echo "✨ Your app should now work perfectly on external devices!"
echo ""
echo "📝 APP_URL has been updated in your .env file"
echo ""
echo "🛑 To stop all services, press Ctrl+C"

# Cleanup function to restore original configuration
cleanup() {
    echo ""
    echo "🛑 Stopping ngrok..."
    kill $NGROK_PID 2>/dev/null

    echo "🔄 Restoring original configuration..."

    # Restore APP_URL
    if [ -f /tmp/laravel_ngrok_app_url.bak ]; then
        RESTORE_APP_URL=$(cat /tmp/laravel_ngrok_app_url.bak)
        if [[ "$OSTYPE" == "darwin"* ]]; then
            sed -i '' "s|^APP_URL=.*|APP_URL=$RESTORE_APP_URL|g" .env
        else
            sed -i "s|^APP_URL=.*|APP_URL=$RESTORE_APP_URL|g" .env
        fi
        rm /tmp/laravel_ngrok_app_url.bak
    fi

    # Restore or remove ASSET_URL
    if [ -f /tmp/laravel_ngrok_asset_url.bak ]; then
        RESTORE_ASSET_URL=$(cat /tmp/laravel_ngrok_asset_url.bak)
        if [ "$RESTORE_ASSET_URL" = "null" ]; then
            # Remove ASSET_URL if it didn't exist before
            if [[ "$OSTYPE" == "darwin"* ]]; then
                sed -i '' "/^ASSET_URL=/d" .env
            else
                sed -i "/^ASSET_URL=/d" .env
            fi
        else
            # Restore original ASSET_URL
            if [[ "$OSTYPE" == "darwin"* ]]; then
                sed -i '' "s|^ASSET_URL=.*|ASSET_URL=$RESTORE_ASSET_URL|g" .env
            else
                sed -i "s|^ASSET_URL=.*|ASSET_URL=$RESTORE_ASSET_URL|g" .env
            fi
        fi
        rm /tmp/laravel_ngrok_asset_url.bak
    fi

    # Restore SESSION_SECURE_COOKIE
    if [ -f /tmp/laravel_ngrok_session_secure.bak ]; then
        RESTORE_SESSION_SECURE=$(cat /tmp/laravel_ngrok_session_secure.bak)
        if [[ "$OSTYPE" == "darwin"* ]]; then
            sed -i '' "s|^SESSION_SECURE_COOKIE=.*|SESSION_SECURE_COOKIE=$RESTORE_SESSION_SECURE|g" .env
        else
            sed -i "s|^SESSION_SECURE_COOKIE=.*|SESSION_SECURE_COOKIE=$RESTORE_SESSION_SECURE|g" .env
        fi
        rm /tmp/laravel_ngrok_session_secure.bak
    fi

    # Clear Laravel config cache to remove ngrok URLs
    echo "🧹 Clearing Laravel config cache..."
    docker compose exec -T app php artisan config:clear > /dev/null 2>&1

    echo "✅ Configuration restored to local development settings!"
    echo "   APP_URL: $RESTORE_APP_URL"

    exit 0
}

# Trap to cleanup on exit
trap cleanup INT TERM

# Keep script running and show logs
echo "📋 Logs (press Ctrl+C to stop):"
echo "================================"

# Monitor ngrok status
while true; do
    sleep 10
    if ! kill -0 $NGROK_PID 2>/dev/null; then
        echo "❌ Ngrok tunnel stopped unexpectedly!"
        break
    fi
    echo "✅ $(date '+%H:%M:%S') - Ngrok tunnel active (PID $NGROK_PID)"
done

# Cleanup if we exit the loop (call cleanup function)
cleanup
