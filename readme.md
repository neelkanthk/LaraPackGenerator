#LaraPackGenerator

####Inspired by the response I got for LaraPackBoiler, I was wondering to create a simple web based application to create laravel packages.

####I wanted to keep the user interface as minimal as possible and yet easy to use. So I started working on Laravel Package Generator.

####Laravel package generator allows you to quickly create a laravel package structure for your project.

##Salient features:

1. Create a package of your choice of name.

2. Define your own package namespace.

3. Define your own controller classes, models, middlewares, requests, events, interfaces.

4. Automatically creates composer.json file, README file for installation instructions.

5. Adds GNU GPL version 3 licence.

6. Follows a structured approach to create the package.

7. All the above features in just few keyboard taps and just one click !!

##How to install and use.

1. Clone the project on your system.

2. Run composer.phar install

3. Run composer.phar dump-autoload

4. Give write permission to bootstrap/cache, storage, public folders.

4.1. sudo chmod 777 -R bootstrap/cache

4.2. sudo chmod 777 -R storage/

4.3. sudo chmod 777 -R public/

5. Run php artisan key:generate

6. Visit the localhost/LaraPackGenerator from browser.

##How to create package.

1. The UI is very easy to understand and use.

2. The Package namespace and Package name are the only required fields to create a directory structure of the pugin.

4. Add a comma separated list of the controller, model classes.

5. Click Generate.

6. That's it. just download and save the file, extract the zippped file and open up the README.md file.

##The installations instruction for the created package will be downloaded with package inside the README.md file.

