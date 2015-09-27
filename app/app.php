<?php

$app = new Silex\Application(); // Create the Silex application, in which all configuration is going to go

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

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

return $app;

?>