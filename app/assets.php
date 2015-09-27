<?php

use SilexAssetic\AsseticServiceProvider;

//PATHS
$app['assetic.enabled']              				= true;
$app['assetic.path_to_web']          				= __DIR__;
$app['assetic.path_to_cache']        				= '../cache' ;
$app['assetic.input.path_to_assets'] 				= '../assets';

//Inputs
$app['assetic.input.path_to_less']    				= '../assets/less/*.less';
$app['assetic.input.path_to_bootstrap_less']    	= '../vendor/twbs/bootstrap/less/bootstrap.less';
$app['assetic.input.path_to_js']     				= '../assets/javascript/*.js';   

//Outputs
$app['assetic.output.path_to_css']      			= '../html/css/styles.css';
$app['assetic.output.path_to_bootstrap_css']      	= '../html/css/bootstrap.css';
$app['assetic.output.path_to_js']       			= '../html/javascript/scripts.js';


if (isset($app['assetic.enabled']) && $app['assetic.enabled']) {
    $app->register(new AsseticServiceProvider(), array(
        'assetic.options' => array(
            'debug'            => $app['debug'],
            'auto_dump_assets' => $app['debug'],
        )
    ));
    $app['assetic.filter_manager'] = $app->share(
        $app->extend('assetic.filter_manager', function ($fm, $app) {
            $fm->set('lessphp', new Assetic\Filter\LessphpFilter());
            return $fm;
        })
    );
    $app['assetic.asset_manager'] = $app->share(
        $app->extend('assetic.asset_manager', function ($am, $app) {
			
			$am->set('stylesBootstrap', new Assetic\Asset\AssetCache(
                new Assetic\Asset\GlobAsset(
                    $app['assetic.input.path_to_bootstrap_less'],
                    array($app['assetic.filter_manager']->get('lessphp'))
                ),
                new Assetic\Cache\FilesystemCache($app['assetic.path_to_cache'])
            ));
            $am->get('stylesBootstrap')->setTargetPath($app['assetic.output.path_to_bootstrap_css']);
			
            $am->set('styles', new Assetic\Asset\AssetCache(
                new Assetic\Asset\GlobAsset(
                    $app['assetic.input.path_to_less'],
                    array($app['assetic.filter_manager']->get('lessphp'))
                ),
                new Assetic\Cache\FilesystemCache($app['assetic.path_to_cache'])
            ));
            $am->get('styles')->setTargetPath($app['assetic.output.path_to_css']);
			
            $am->set('scripts', new Assetic\Asset\AssetCache(
                new Assetic\Asset\GlobAsset($app['assetic.input.path_to_js']),
                new Assetic\Cache\FilesystemCache($app['assetic.path_to_cache'])
            ));
            $am->get('scripts')->setTargetPath($app['assetic.output.path_to_js']);
			
            return $am;
        })
    );
}

?>