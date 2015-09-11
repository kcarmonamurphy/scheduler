<?php
require_once __DIR__.'/../vendor/autoload.php'; // Add the autoloading mechanism of Composer
require_once __DIR__.'/../assets/php/variables.php';

$app = new Silex\Application(); // Create the Silex application, in which all configuration is going to go

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../templates', // The path to the templates, which is in our case points to /var/www/templates
));

$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
    $twig->addFunction(new \Twig_SimpleFunction('asset', function ($asset) use ($app) {
        return sprintf('%s/%s', trim($app['request']->getBasePath()), ltrim($asset, '/'));
    }));
    return $twig;
}));

$app->get('/', function (Silex\Application $app)  { // Match the root route (/) and supply the application as argument
    return $app['twig']->render( // Render the page index.html.twig
        'index.html.twig',
		array()
    );
})->bind('index');

$app->get('/blog', function (Silex\Application $app)  { // Match the root route (/) and supply the application as argument
    return $app['twig']->render( // Render the page index.html.twig
        'blog.html.twig',
        array(
            'articles' => $app['articles'], // Supply arguments to be used in the template
        )
    );
})->bind('blog');

$app->post('/hello', function (Request $request) {
    $name = $request->get('name'); // where 'name' is your form field's name
    return 'Hello '.$app->escape($name);
});




$app->get('/{id}', function (Silex\Application $app, $id)  { // Add a parameter for an ID in the route, and it will be supplied as argument in the function
    if (!array_key_exists($id, $app['articles'])) {
        $app->abort(404, 'The article could not be found');
    }
    $article = $app['articles'][$id];
    return $app['twig']->render(
        'single.html.twig',
        array(
            'article' => $article,
        )
    );
})
    ->assert('id', '\d+') // specify that the ID should be an integer
    ->bind('single'); // name the route so it can be referred to later in the section 'Generating routes'

// This should be the last line
$app->run(); // Start the application, i.e. handle the request
?>
