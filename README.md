This project is tell explain you about PHP CI/CD IN aws
-------------------------------------------------------


Create a instance in aws
-------------------------

Download .pem file when you are creating instance some where
-----------------------------------

Login create GIT HUB Account, And create an Account
---------------------------------------------------

Install Apache and php to your ec2 instance
---------------------------------------------

Make the PHP file run in ec2 instance
--------------------------------------

The go to your Git hub account
------------------------------

Create a repository and make the repository in such a way that you can pull and push code
-----------------------------------------------------------------------------------------

Go to your repository -> setting -> secret and variables -> Action and here you create your secret variables EC2_SSH_KEY,EC2_PUBLIC_IP
--------------------------------------------------------------------------------------------------------------------------------------

And then you create your workflow
--------------------------------------
your-project/
└── .github/
    └── workflows/
        └── php-cicd.yml

Sample code will be for .yml file (PHP)
----------------------------------------
name: PHP CI/CD Pipeline

on:
  push:
    branches: [ "main" ]
  pull_request:
    branches: [ "main" ]

env:
  PHP_VERSION: '8.3'                         # Change to your target PHP version
  TARGET_DIR: /var/www/html/my-app          # Path to your server's web root
  EC2_USER: ubuntu                           # Your server's SSH username

jobs:
  # --- 1. CONTINUOUS INTEGRATION (CI) JOB ---
  test-and-build:
    name: Validate & Test Code
    runs-on: ubuntu-latest

    steps:
    - name: Checkout Code
      uses: actions/checkout@v4

    - name: Setup PHP Environment
      uses: shivammathur/setup-php@v2
      with:
        php-version: ${{ env.PHP_VERSION }}
        extensions: mbstring, xml, ctype, iconv, mysql, zip # Add required extensions
        coverage: none

    - name: Get Composer Cache Directory
      id: composer-cache
      run: echo "dir=$(composer config cache-files-dir)" >> $GITHUB_OUTPUT

    - name: Cache Composer Dependencies
      uses: actions/cache@v4
      with:
        path: ${{ steps:composer-cache.outputs.dir }}
        key: ${{ runner.os }}-composer-${{ hashFiles('**/composer.lock') }}
        restore-keys: ${{ runner.os }}-composer-

    - name: Validate composer.json
      run: composer validate --strict

    - name: Install Dependencies
      run: composer install --prefer-dist --no-progress

    # OPTIONAL: Uncomment below if you use PHPUnit for automated tests
    # - name: Run Test Suite
    #   run: vendor/bin/phpunit

  # --- 2. CONTINUOUS DEPLOYMENT (CD) JOB ---
  deploy:
    name: Deploy to Server
    needs: test-and-build                    # Only runs if the CI job succeeds
    if: github.event_name == 'push'          # Only deploys on pushes, not PRs
    runs-on: ubuntu-latest

    steps:
    - name: Checkout Code
      uses: actions/checkout@v4

    - name: Setup PHP for Production
      uses: shivammathur/setup-php@v2
      with:
        php-version: ${{ env.PHP_VERSION }}

    - name: Install Production Dependencies
      run: |
        composer install --no-dev --optimize-autoloader --no-interaction

    - name: Deploy to Server via SSH rsync
      uses: easingthemes/ssh-deploy@main     
      with:
        SSH_PRIVATE_KEY: ${{ secrets.SERVER_SSH_KEY }}
        REMOTE_HOST: ${{ secrets.SERVER_IP }}
        REMOTE_USER: ${{ env.EC2_USER }}
        TARGET: ${{ env.TARGET_DIR }}
        ARGS: "-rlgoDzv --no-perms --no-owner --no-group --no-t --delete --exclude='.git/' --exclude='.github/'"
        SSH_CMD_ARGS: "-o StrictHostKeyChecking=no"
        SCRIPT_AFTER: |
          # Set safe permissions for the web server
          sudo chown -R ubuntu:www-data ${{ env.TARGET_DIR }}
          sudo chmod -R 775 ${{ env.TARGET_DIR }}
          
          # Run Laravel/Symfony framework optimization commands if needed
          # cd ${{ env.TARGET_DIR }} && php artisan config:cache
          
          # Reload the web server to clear OPcache
          sudo systemctl reload apache2



Step 3: Set up GitHub SecretsTo make the deployment work securely without exposing sensitive passwords or keys, add your server credentials to GitHub:Go to your GitHub repository -> Settings.On the left sidebar, click Secrets and variables -> Actions.Click New repository secret to add the following three secrets:SERVER_SSH_KEY: The complete text contents of your private SSH key file (typically id_rsa or your .pem file).SERVER_IP: The public IP address or domain name of your hosting server.
--------------------------------------------------------------------------------------------------

Step 4: Trigger the WorkflowCommit your changes and push them to your repository:bash
git add .
git commit -m "Add CI/CD pipeline"
git push origin main