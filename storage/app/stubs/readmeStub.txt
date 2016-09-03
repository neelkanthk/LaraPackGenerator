# ##PACKAGENAME##

###Configuration instructions.

1. Extract ##PACKAGENAME## archive to the Laravel's vendor folder.

2. Add ##NAMESPACE##\Application\Providers\##PACKAGENAME##ServiceProvider::class to config/app.php's providers array.
   Add ##NAMESPACE##\Application\Providers\##PACKAGENAME##EventServiceProvider::class to config/app.php's providers array (Optional. Add only if event is created.).

3. Add the following line to the project's composer.json psr-4 array:
    
    ```
  "psr-4": {
              "App\\": "app/",
              "##NAMESPACE##\\Application\\" : "vendor/##PACKAGENAME##/application/src/"
          }
    ```
4. Run ``` composer dump-autoload ``` from your project root.
5.  (Optional) 

    Run following command to move the package assets, views, config files to your application folder.

     ```
    php artisan vendor:publish
    ```
6. (Optional but Recommended) 
   
    Test your installation by visiting the following URL in your browser.

    http://your-siteurl/##PACKAGENAME##/test

    If you see "You are ready to start building your package." then you have successfully configured the package boilerplate.

7. That's it. Now, You are ready to develop your package.