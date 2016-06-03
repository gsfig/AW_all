<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
| example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
| http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
| $route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
| $route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
| $route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples: my-controller/index -> my_controller/index
|   my-controller/my-method -> my_controller/my_method
*/



$route['user/signup'] = 'login_controller/signup';
$route['user/login'] = 'login_controller/login';
$route['user/logout'] = 'login_controller/logout';
//$route['user/annotations'] = 'login_controller/annotations/username';
//$route['user/(:any)/annotations'] = 'login_controller/annotations/username/$1';

$route['document'] = 'document_controller/document';

$route['document/(:num)/annotation'] = 'document_controller/paperAnnotation/idNCBI/$1';

//$route['document/(:num)'] = 'document_controller/document/id/$1';
$route['document/annotation'] = 'document_controller/paper_annotation';
$route['document/annotation/user'] = 'login_controller/annotations/user/$1';

$route['admin/delete'] = 'login_controller/delete';




$route['ibent_annotate'] = 'document_controller/free_text';

// $route['document/(:num)/'] = 'document_controller/document_annotation/id/$1';
// document_post
// idNCBI, title, abstract

// $route['papers'] = 'ibent_annotate/papers';
$route['queriesToId'] = 'ncbiController/queriesToId';
//$route['idToAnnotate'] = 'ibent_controller/paperAnnotate';
//$route['idToTitle'] = 'ncbiController/IdToTitle';
//$route['idToLink'] = 'ncbiController/idTolink';

$route['compound'] = 'Chebi_controller/compounds';
$route['compound/cheminfo'] = 'Chebi_controller/cheminfo';

$route['compounds_ontology'] = 'Chebi_compound/compounds_ontology';
$route['compounds_pathway'] = 'Chebi_compound/compounds_pathway';





$route['404_override'] = '';
$route['translate_uri_dashes'] = TRUE;
$route['default_controller'] = 'inicio';

/*
| -------------------------------------------------------------------------
| Sample REST API Routes
| -------------------------------------------------------------------------
*/
// $route['api/example/users/(:num)'] = 'api/example/users/id/$1'; // Example 4
// $route['api/example/users/(:num)(\.)([a-zA-Z0-9_-]+)(.*)'] = 'api/example/users/id/$1/format/$3$4'; // Example 8
