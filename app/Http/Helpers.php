<?php

namespace App\Http;

use Storage;
use File;
use Log;
use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use App\Package;
use Session;

class Helpers {

    protected $package_root;
    protected $package_source;
    protected $controller_dir;
    protected $model_dir;
    protected $middleware_dir;
    protected $request_dir;

    public function __construct() {
        $this->package_root = "/application";
        $this->package_source = $this->package_root . "/src";

        $this->routes_dir = $this->package_source . "/Http/";
        $this->controller_dir = $this->package_source . "/Http/Controllers/";
        $this->model_dir = $this->package_source . "/Models/";
        $this->middleware_dir = $this->package_source . "/Http/Middlewares/";
        $this->request_dir = $this->package_source . "/Http/Requests/";
        $this->event_dir = $this->package_source . "/Events/";
        $this->listener_dir = $this->package_source . "/Listeners/";
        $this->interface_dir = $this->package_source . "/Interfaces/";
        $this->config_dir = $this->package_source . "/config/";
        $this->provider_dir = $this->package_source . "/Providers/";
    }

    /**
     * Converts a delimited string to array
     * @param string $str String to be converted
     * @param string $delimiter Delimiter
     * @return array
     * @author Neelkanth Kaushik
     */
    public static function string_to_array($str = '', $delimiter = ',') {

        //remove spaces
        $str = str_replace(' ', '_', $str);
        //remove white spaces
        $str = preg_replace('/\s+/', '', $str);
        //create array
        $array = explode($delimiter, $str);

        return $array;
    }

    /**
     * Create file from stubs
     * @param type $type
     * @param type $filenames
     * @return type
     */
    public static function create_file_from_stub($stubType = 'controller', $filenames = array(), $namespace = 'Package', $name = 'Package') {
        //make first letter capital because all stub files have first letter capital
        $helper = new Helpers();
        $data = '';
        $namespace = ucfirst($namespace);
        //get the content of stub file
        try {
            $contents = Storage::disk('local')->get("stubs/" . $stubType . "Stub.txt");
        } catch (Exception $ex) {
            Log::error("Error while getting the content of stub file for: $stubType", ['Exception' => $ex]);
        }


        //loop through all file names to be created
        if (isset($filenames) && !empty($filenames)):
            foreach ($filenames as $filename):
                if (isset($filename) && !empty($filename)):
                    $filename = ucfirst($filename);
                    $data = '';
                    //replace the placeholders and 
                    //create the file
                    if (str_contains($contents, '##NAMESPACE##')) :
                        $data = str_replace('##NAMESPACE##', $namespace, $contents);
                    endif;
                    try {
                        if ($stubType == 'controller') :
                            $data = str_replace('##CONTROLLERNAME##', $filename, $data);
                            $status = File::put(public_path('packages/' . $name . $helper->controller_dir . $filename . '.php'), $data);
                        endif;
                        if ($stubType == 'model') :
//                    dd($helper->model_dir);
                            $data = str_replace('##MODELNAME##', $filename, $data);
                            $status = File::put(public_path('packages/' . $name . $helper->model_dir . $filename . '.php'), $data);
                        endif;
                        if ($stubType == 'middleware') :
//                    dd(public_path('packages/' . $name . $helper->middleware_dir . $filename . '.php'));
                            $data = str_replace('##MIDDLEWARENAME##', $filename, $data);
                            Session::put('lpg_session.package_data.middlewarename', $filename);
                            $status = File::put(public_path('packages/' . $name . $helper->middleware_dir . $filename . '.php'), $data);
                        endif;
                        if ($stubType == 'request') :
                            $data = str_replace('##REQUESTNAME##', $filename, $data);
                            $status = File::put(public_path('packages/' . $name . $helper->request_dir . $filename . '.php'), $data);
                        endif;
                        if ($stubType == 'event') :
                            $data = str_replace('##EVENTNAME##', $filename, $data);
                            $status = File::put(public_path('packages/' . $name . $helper->event_dir . $filename . '.php'), $data);

                            //create listener for each event
                            //get listener stub file
                            $listenerStub = Storage::disk('local')->get("stubs/listenerStub.txt");
                            $data = str_replace('##NAMESPACE##', $namespace, $listenerStub);
                            $data = str_replace('##EVENTNAME##', $filename, $data);
                            Session::put('lpg_session.package_data.eventname', $filename);
                            $status = File::put(public_path('packages/' . $name . $helper->listener_dir . $filename . 'Listener.php'), $data);
                        endif;
                        if ($stubType == 'interface') :
                            $data = str_replace('##INTERFACENAME##', $filename, $data);
                            $status = File::put(public_path('packages/' . $name . $helper->interface_dir . $filename . '.php'), $data);
                        endif;
                    } catch (Exception $ex) {
                        Log::error("Error while generating $stubType", ['Exception' => $ex]);
                    }
                endif;
            endforeach;
        endif;
        $data = '';

        //create Route file
        try {
            $contents = Storage::disk('local')->get("stubs/routeStub.txt");
        } catch (Exception $ex) {
            Log::error("Error while getting stub for routes file", ['Exception' => $ex]);
        }

        try {
            //replace ##PACKAGENAME## in routes file
            $data = str_replace('##PACKAGENAME##', $name, $contents);

            //write to the routes file
            $status = File::put(public_path('packages/' . $name . $helper->routes_dir . 'routes.php'), $data);
        } catch (Exception $ex) {
            Log::error("Error while generating routes file", ['Exception' => $ex]);
        }

        //Create config file
        try {
            $data = Storage::disk('local')->get("stubs/configStub.txt");
        } catch (Exception $ex) {
            Log::error("Error while getting stub for config file", ['Exception' => $ex]);
        }

        try {
            $status = File::put(public_path('packages/' . $name . $helper->config_dir . $name . '.php'), $data);
        } catch (Exception $ex) {
            Log::error("Error while generating config file", ['Exception' => $ex]);
        }

        $data = '';
        //create composer.json
        try {
            $contents = Storage::disk('local')->get("stubs/composerStub.txt");
        } catch (Exception $ex) {
            Log::error("Error while getting stub for composer file", ['Exception' => $ex]);
        }

        try {
            //replace ##PACKAGENAME## in composer.json file
            $data = str_replace('##PACKAGENAME##', $name, $contents);
            //replace ##AUTHOR##
            $data = str_replace('##AUTHOR##', 'neelkanth', $data);
            //replace ##PACKAGE_DESCRIPTION##
            $data = str_replace('##PACKAGE_DESCRIPTION##', 'Lorem ipsum doler sit.', $data);
            //replace ##LICENCE##
            $data = str_replace('##LICENCE##', 'GNU-GPL v3', $data);
            //write to the composer.json file
            $status = File::put(public_path('packages/' . $name . '/composer.json'), $data);
        } catch (Exception $ex) {
            Log::error("Error while generating composer.json file", ['Exception' => $ex]);
        }

        //Create README.md file
        $data = '';
        try {
            $contents = Storage::disk('local')->get("stubs/readmeStub.txt");
        } catch (Exception $ex) {
            Log::error("Error while getting stub for README file", ['Exception' => $ex]);
        }

        try {
            //replace ##PACKAGENAME## in readme file
            $data = str_replace('##PACKAGENAME##', $name, $contents);
            $data = str_replace('##NAMESPACE##', $namespace, $data);
            //write to the readme file
            $status = File::put(public_path('packages/' . $name . '/README.md'), $data);
        } catch (Exception $ex) {
            Log::error("Error while generating readme file", ['Exception' => $ex]);
        }
        //Create Service provider
        $data = '';
        try {
            $contents = Storage::disk('local')->get("stubs/providerStub.txt");
        } catch (Exception $ex) {
            Log::error("Error while getting stub for service provider", ['Exception' => $ex]);
        }

        try {
            //replace namespace in provider
            $data = str_replace('##NAMESPACE##', $namespace, $contents);
            //replace package name in provider
            $data = str_replace('##PACKAGENAME##', $name, $data);
            //replace middleware in provider

            if ($stubType == 'middleware'):
                if (!empty(Session::get('lpg_session.package_data.middlewarename'))):
                    $middlewarenames = $filenames;
                    //declare standard middleware register string
                    $strMiddlewareRegister = '$this->app["router"]->middleware("##MIDDLEWARENAME##", "##NAMESPACE##\Application\Http\Middlewares\##MIDDLEWARENAME##");';
                    //define temporary string to hold intermediate string replacements
                    $tempStr = "";
                    //final array of all middlewares
                    $finalMiddlewareArray = array();
                    //echo "<pre>";
                    if (isset($middlewarenames) && !empty($middlewarenames)):
                        foreach ($middlewarenames as $middlewarename):
                            //repace middleware name
                            $tempStr = str_replace('##MIDDLEWARENAME##', $middlewarename, $strMiddlewareRegister);
                            //replace namespace name 
                            $tempStr = str_replace('##NAMESPACE##', $namespace, $tempStr);
                            //add to final array
                            $finalMiddlewareArray[] = $tempStr;
                        endforeach;
                    endif;
                    //now write each item of this array after a particular line in the provider stub file
                    //create string from array
                    $middlewares = implode("\n", $finalMiddlewareArray);

                    //find the position after which to append the middlewares
                    $len = strlen('##REGISTER_MIDDLEWARES##');
                    $pos = strpos($data, '##REGISTER_MIDDLEWARES##') + $len + 1;

                    $tempStr = null;
                    $tempStr = substr_replace($data, $middlewares, $pos, 0);

                    //create final final to write
                    $data = str_replace('##REGISTER_MIDDLEWARES##', '//Register package middlewares below.', $tempStr);
                endif;
                //write to the file
                $status = File::put(public_path('packages/' . $name . $helper->provider_dir . $name . 'ServiceProvider.php'), $data);

            endif;
        } catch (Exception $ex) {
            Log::error("Error while generating service provider", ['Exception' => $ex]);
        }


        //create event service provider
        if (!empty(Session::get('lpg_session.package_data.eventname'))) {
            $data = '';
            try {
                $contents = Storage::disk('local')->get("stubs/eventProviderStub.txt");
            } catch (Exception $ex) {
                Log::error("Error while getting stub for event service provider", ['Exception' => $ex]);
            }

            try {
                //replace ##PACKAGENAME##
                $data = str_replace('##PACKAGENAME##', $name, $contents);
                $data = str_replace('##NAMESPACE##', $namespace, $data);
                $eventname = Session::get('lpg_session.package_data.eventname');
                $data = str_replace('##EVENTNAME##', $eventname, $data);
                //write
                $status = File::put(public_path('packages/' . $name . $helper->provider_dir . $name . 'EventServiceProvider.php'), $data);
            } catch (Exception $ex) {
                Log::error("Error while generating event provider file", ['Exception' => $ex]);
            }
        }

        return;
    }

    /**
     * Creates Zip of the package folder recursively
     * @param type $source
     * @param type $destination
     * @param string $flag
     * @return boolean
     */
    public static function createZip($source, $destination, $flag = '') {

        if (extension_loaded('zip')) {
            if (file_exists($source)) {
                $zip = new ZipArchive();
                if ($zip->open($destination, ZIPARCHIVE::CREATE)) {
                    $source = realpath($source);
                    if (is_dir($source)) {
                        $iterator = new RecursiveDirectoryIterator($source);
                        // skip dot files while iterating 
                        $iterator->setFlags(RecursiveDirectoryIterator::SKIP_DOTS);
                        $files = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::SELF_FIRST);
                        foreach ($files as $file) {
                            $file = realpath($file);
                            if (is_dir($file)) {
                                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                            } else if (is_file($file)) {
                                $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
                            }
                        }
                    } else if (is_file($source)) {
                        $zip->addFromString(basename($source), file_get_contents($source));
                    }
                }
                return $zip->close();
            }
        }
        return false;
    }

}
