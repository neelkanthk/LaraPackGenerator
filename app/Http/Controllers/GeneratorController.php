<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\GeneratorRequest;
use Storage;
use App\Http\Helpers;
use File;
use Log;
use ZipArchive;
use Auth;
use Event;
use App\Events\PackageDownloadEvent;
use Session;

class GeneratorController extends Controller {

    public function __construct() {
        ;
    }

    public function generatepackage(GeneratorRequest $request) {

        $package_namespace = str_replace(' ', '', ucwords($request->namespace));
        $package_name = str_replace(' ', '_', $request->name);
        $package_controllers = Helpers::string_to_array($request->controllers);
        $package_models = Helpers::string_to_array($request->models);
        $package_middlewares = Helpers::string_to_array($request->middlewares);
        $package_requests = Helpers::string_to_array($request->requests);
        $package_events = Helpers::string_to_array($request->events);
        $package_interfaces = Helpers::string_to_array($request->interfaces);

        //create a directory skeleton
        //move the directory skeleton to the public directory
        try {
            $src = storage_path('app/stubs/larapackboiler');
            $dest = public_path('packages/' . $package_name);
            File::copyDirectory($src, $dest);
        } catch (Exception $ex) {
            Log::error('Error while copying the directory skeleton', ['Exception' => $ex]);
        }

        //rename the application/src/resources/views/packagename directory
        try {
            $oldPackageViewDir = public_path("packages/$package_name/application/src/resources/views/packagename");
            $newPackageViewDir = public_path("packages/$package_name/application/src/resources/views/$package_name");
            File::move($oldPackageViewDir, $newPackageViewDir);
        } catch (Exception $ex) {
            Log::error('Error while renaming the views/packagename directory', ['LPG_Exception' => $ex]);
        }


        Helpers::create_file_from_stub('controller', $package_controllers, $package_namespace, $package_name);
        Helpers::create_file_from_stub('model', $package_models, $package_namespace, $package_name);
        Helpers::create_file_from_stub('middleware', $package_middlewares, $package_namespace, $package_name);
        Helpers::create_file_from_stub('request', $package_requests, $package_namespace, $package_name);
        Helpers::create_file_from_stub('event', $package_events, $package_namespace, $package_name);
        Helpers::create_file_from_stub('interface', $package_interfaces, $package_namespace, $package_name);

        //create zip
        $zipFrom = public_path("packages/$package_name");
        $zipName = $package_name . "_" . date("d_M_Y_H_m_s") . "__" . time() . ".zip";
        $zipTo = public_path("packages/$zipName");
        Helpers::createZip($zipFrom, $zipTo, true);
        //delete the non zipped folder
        File::deleteDirectory(public_path("packages/$package_name"));


        
        //download
        $pathToZipFile = public_path("packages/$zipName");
        Session::flash('lpg.downloaded', 'true');
        //return redirect()->route('rt_home');
        //dd($pathToZipFile);
        return response()->download($pathToZipFile, $package_name);
    }

}
