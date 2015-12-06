<?php

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app->get('/', function (Silex\Application $app)  { // Match the root route (/) and supply the application as argument
    return $app['twig']->render( // Render the page index.html.twig
        'index.html.twig',
		array(
			'daysOfWeek' => $app['daysOfWeek'],
			'timeSlots' => $app['timeSlots'],
		)
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

$app->post('/newmeeting', function (Request $request) {
	
	$emails = $request->get('emails');
    $days = $request->get('days');
	$meeting_name = $request->get('meeting_name');
	$grid = $request->get('grid');
	
	$top_range = $request->get('top_range');
	$bottom_range = $request->get('bottom_range');
	
	$from_email = array_shift($emails);
	$to_emails = $emails;

    return new Response(var_dump($grid), 201);
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

?>