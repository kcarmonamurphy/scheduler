<?php

$app = new Silex\Application(); // Create the Silex application, in which all configuration is going to go

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

use SilexAssetic\AsseticServiceProvider;
//use Assetic\Filter\LessphpFilter;

echo __DIR__;

$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../templates', // The path to the templates, which is in our case points to /var/www/templates
));

$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
    $twig->addFunction(new \Twig_SimpleFunction('asset', function ($asset) use ($app) {
        return sprintf('%s/%s', trim($app['request']->getBasePath()), ltrim($asset, '/'));
		//return __DIR__.'/../assets';
    }));
    return $twig;
}));

//PATHS
$app['assetic.enabled']              = true;
$app['cache.path']					 = '../assets/cache';
$app['assetic.path_to_cache']        = $app['cache.path'] . '/assetic' ;
$app['assetic.path_to_web']          = __DIR__;
$app['assetic.input.path_to_assets'] = '../assets';

//Inputs
$app['assetic.input.path_to_less']    = '../assets/less/*.less';
//	array(
//										'../vendor/twbs/bootstrap/less/bootstrap.less',
//										'../assets/less/*.less',
//										
//										);

$app['assetic.input.path_to_bootstrap_less']    = '../vendor/twbs/bootstrap/less/bootstrap.less';
$app['assetic.input.path_to_js']     = '../assets/javascript/*.js';   
//	= array(
//    //__DIR__.'/../vendor/twitter/bootstrap/js/bootstrap-tooltip.js',
//    //__DIR__.'/../vendor/twitter/bootstrap/js/*.js',
//    '..assets/javascript/scripts.js'
//);

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
			
            $am->set('styles', new Assetic\Asset\AssetCache(
                new Assetic\Asset\GlobAsset(
                    $app['assetic.input.path_to_less'],
                    array($app['assetic.filter_manager']->get('lessphp'))
                ),
                new Assetic\Cache\FilesystemCache($app['assetic.path_to_cache'])
            ));
            $am->get('styles')->setTargetPath($app['assetic.output.path_to_css']);
			
			$am->set('stylesBootstrap', new Assetic\Asset\AssetCache(
                new Assetic\Asset\GlobAsset(
                    $app['assetic.input.path_to_bootstrap_less'],
                    array($app['assetic.filter_manager']->get('lessphp'))
                ),
                new Assetic\Cache\FilesystemCache($app['assetic.path_to_cache'])
            ));
            $am->get('stylesBootstrap')->setTargetPath($app['assetic.output.path_to_bootstrap_css']);
			
            $am->set('scripts', new Assetic\Asset\AssetCache(
                new Assetic\Asset\GlobAsset($app['assetic.input.path_to_js']),
                new Assetic\Cache\FilesystemCache($app['assetic.path_to_cache'])
            ));
            $am->get('scripts')->setTargetPath($app['assetic.output.path_to_js']);
			
            return $am;
        })
    );
}

return $app;

?>