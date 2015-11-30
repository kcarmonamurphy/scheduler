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
$app['assetic.input.path_to_jquery_ui_css_min']    	= '../vendor/components/jqueryui/themes/base/jquery-ui.min.css';
$app['assetic.input.path_to_js']     				= '../assets/javascript/*.js';  
$app['assetic.input.path_to_jquery']    			= '../vendor/components/jquery/jquery.min.js';
$app['assetic.input.path_to_jquery_ui_js']    		= '../vendor/components/jqueryui/jquery-ui.min.js';
$app['assetic.input.path_to_bootstrap_js']    		= '../vendor/twbs/bootstrap/dist/js/bootstrap.min.js';
//$app['assetic.input.path_to_ember_js']    			= '../vendor/components/ember/ember.min.js';

//Outputs
$app['assetic.output.path_to_css']      			= '../html/css/styles.css';
$app['assetic.output.path_to_bootstrap_css']      	= '../html/css/bootstrap.css';
$app['assetic.output.path_to_jquery_ui_css']     	= '../html/css/jquery-ui.css';
$app['assetic.output.path_to_js']       			= '../html/js/scripts.js';
$app['assetic.output.path_to_bootstrap_js']       	= '../html/js/bootstrap.js';
$app['assetic.output.path_to_jquery_ui_js']       	= '../html/js/jquery-ui.js';
$app['assetic.output.path_to_jquery']       		= '../html/js/jquery.js';
//$app['assetic.output.path_to_ember_js']       	= '../html/js/ember.js';

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
			
			/* ---- STYLES ---- */
			
			//Styles
            $am->set('styles', new Assetic\Asset\AssetCache(
                new Assetic\Asset\GlobAsset(
                    $app['assetic.input.path_to_less'],
                    array($app['assetic.filter_manager']->get('lessphp'))
                ),
                new Assetic\Cache\FilesystemCache($app['assetic.path_to_cache'])
            ));
            $am->get('styles')->setTargetPath($app['assetic.output.path_to_css']);
			
			//Styles
            $am->set('styles_jquery_ui', new Assetic\Asset\AssetCache(
                new Assetic\Asset\GlobAsset(
                    $app['assetic.input.path_to_jquery_ui_css_min']
                ),
                new Assetic\Cache\FilesystemCache($app['assetic.path_to_cache'])
            ));
            $am->get('styles_jquery_ui')->setTargetPath($app['assetic.output.path_to_jquery_ui_css']);
			
			//Styles Bootstrap
			$am->set('styles_bootstrap', new Assetic\Asset\AssetCache(
                new Assetic\Asset\GlobAsset(
                    $app['assetic.input.path_to_bootstrap_less'],
                    array($app['assetic.filter_manager']->get('lessphp'))
                ),
                new Assetic\Cache\FilesystemCache($app['assetic.path_to_cache'])
            ));
            $am->get('styles_bootstrap')->setTargetPath($app['assetic.output.path_to_bootstrap_css']);
			
			/* ---- JAVASCRIPT ---- */
			
			//Scripts
            $am->set('scripts', new Assetic\Asset\AssetCache(
                new Assetic\Asset\GlobAsset($app['assetic.input.path_to_js']),
                new Assetic\Cache\FilesystemCache($app['assetic.path_to_cache'])
            ));
            $am->get('scripts')->setTargetPath($app['assetic.output.path_to_js']);
			
			//Scripts jQuery
			$am->set('scripts_jquery', new Assetic\Asset\AssetCache(
                new Assetic\Asset\GlobAsset($app['assetic.input.path_to_jquery']),
                new Assetic\Cache\FilesystemCache($app['assetic.path_to_cache'])
            ));
            $am->get('scripts_jquery')->setTargetPath($app['assetic.output.path_to_jquery']);
			
			//Scripts jQuery-UI
			$am->set('scripts_jquery_ui', new Assetic\Asset\AssetCache(
                new Assetic\Asset\GlobAsset($app['assetic.input.path_to_jquery_ui_js']),
                new Assetic\Cache\FilesystemCache($app['assetic.path_to_cache'])
            ));
            $am->get('scripts_jquery_ui')->setTargetPath($app['assetic.output.path_to_jquery_ui_js']);
			
			//Scripts Bootstrap
			$am->set('scripts_bootstrap', new Assetic\Asset\AssetCache(
                new Assetic\Asset\GlobAsset($app['assetic.input.path_to_bootstrap_js']),
                new Assetic\Cache\FilesystemCache($app['assetic.path_to_cache'])
            ));
            $am->get('scripts_bootstrap')->setTargetPath($app['assetic.output.path_to_bootstrap_js']);
			
			//Scripts Ember
//			$am->set('scripts_ember', new Assetic\Asset\AssetCache(
//                new Assetic\Asset\GlobAsset($app['assetic.input.path_to_ember_js']),
//                new Assetic\Cache\FilesystemCache($app['assetic.path_to_cache'])
//            ));
//            $am->get('scripts_ember')->setTargetPath($app['assetic.output.path_to_ember_js']);
			
            return $am;
        })
    );
}

?>