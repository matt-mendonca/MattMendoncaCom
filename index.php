<?php 
// Bootstrap
    require_once __DIR__.'/vendor/autoload.php';
    use Symfony\Component\Yaml\Yaml;
    use Binfo\Silex\MobileDetectServiceProvider;
    //use Notion\Helpers\ContentHelper;

    /* 
        Hack - had issues on dreamhost shared hosting - 
        Fatal error: Class 'Notion\Helpers\ContentHelper' not found in /home/mattmendonca/mattmendonca.com/index.php on line 43
    */
        class ContentHelper
        {
            public static function getDirectoryTree($dir) {
                $directory_tree = array();
                foreach ( $dir as $node ):
                    if ($node->isDir() && !$node->isDot()):
                        $directory_tree[$node->getFilename()] = getDirectoryTree(
                            new DirectoryIterator(
                                $node->getPathname()
                            )
                        );
                    elseif ($node->isFile()):
                        $directory_tree[] = $node->getFilename();
                    endif;
                endforeach;

                return $directory_tree;
            }
        }

    $app = new Silex\Application();

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/templates',
    ));

    $app->register(new MobileDetectServiceProvider());

// Debug  
    // $app['debug'] = true;
    // error_reporting(E_ALL);
    // ini_set('display_errors', TRUE);
    // ini_set('display_startup_errors', TRUE);
    

// Load pages
    $page_registery = ContentHelper::getDirectoryTree(new DirectoryIterator('content'));
    
    foreach ($page_registery as $key => $page_file):
        $page = Yaml::parse("content/{$page_file}"); 

        // Add trailing slash if not present.
            $page['route'] = substr($page['route'], -1) === '/' ? 
                $page['route'] : 
                $page['route'] . '/';
        
        $page['is_mobile'] = $app["mobile_detect"]->isMobile();

        $page['slug'] = str_replace('/', '', $page['route']);    

        // Define routes
            $app->get($page['route'], function () use ($app, $page) {

                $page['uri'] = $app["request"]->getRequestURI();

                // Check if PJAX request, send pjaxt template
                if($app['request']->headers->get('X-PJAX')):
                    return $app['twig']->render("x-pjax.{$page['template']}", $page);
                else:
                    return $app['twig']->render($page['template'], $page);
                endif;
            });
    endforeach;

// Error handling
    $app->error(function (\Exception $e, $code) use ($app) {
        switch ($code):
            case 404:
                $message = '<p>The requested page could not be found.</p> <h4><a href="/">Return to home.</a></h4>';
                break;
            default:
                $message = '<p>We are sorry, but something went terribly wrong.</p> <h4><a href="/">Return to home.</a></h4>';
        endswitch;

        if($app['debug']):
            $message .= "<pre><code>{$e}</code></pre>";
        endif;

        return $app['twig']->render('base.twig', array(
            'page_title' => $code,
            'content_title' => $code,
            'content' => $message,
            'is_mobile' => $app["mobile_detect"]->isMobile()
        ));
    });

$app->run();