echo "deploying files to server"

scp -P 4422 -r * smarthost@197.253.124.137:/var/www/fieldserve

echo "Deployment complete!"
