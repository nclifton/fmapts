# fmapts - find me a place to stay

## About fmapts

fmapts is a web application that uses the Laravel framework and Vue.js.
I built it as a "tech-test". see [below][Full Stack Lead Developer Test]

fmapts is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Installation 
Two docker methods, Dockerfile and docker-compose. Which one you use depends on your local setup.

### Dockerfile
Dockerfile will re-build everything including pulling the source from github. 
This approach should allow things to work if all you have to start with is the Dockerfile.
I am attempting to create a pre-built image at docker hub, so all you need is:

`docker run --env ATLAS_KEY=123456789101112 -p 80:80 -p 443:443 -dP cliftonwebfoundry/fmapts:latest`

### docker-compose
docker-compose uses volumes to share the 'built' project directory with /var/www/html in the docker instance. 
This assumes you have 'built' the project ... that is, pulled from the github repository and used composer to fill out the vendor directory,
and setup the .env file, ... and created the application key,  and yarn and yarn dev.

### the .env file
The only thing in the `.env` file you need to know about is `ATLAS_KEY`, it needs to be setup with the ATLAS developer key.
The example `.env.example` has it in there, not sure if it should, but it lets the Dockerfile build a working image without having to 
specify `--env ATLAS_KEY=...` with the `docker run` command.

### /etc/hosts
Add fmapts.demo to /etc/hosts for ip 127.0.0.1

### start it up

https://fmapts.demo

## Laravel
Built from the standard boilerplate [Laravel](https://laravel.com/).

## Hey where is the controller?

I see no Fat Controller here (toot toot!).

Instead of using a controller I've just put the little bit of stuff there is in the route file  `routes/api.php`
And also using a bit of route URI segment binding for the product selection in `app/Providers/RouteServiceProvider.php`.

## Remote Model
Uses the Remote Model ORM package (https://github.com/Torann/remote-model) to provide a neat way to encapsulate the 
ATLAS API interfaces in the Product, Area and Region Model Classes.
Uses the guzzle/guzzle http client (https://github.com/guzzle/guzzle) to handle the http request/response.

## Caching
Uses Laravel Cache to reduce the calls to the ATLAS service.

## Client side - Vue.js
Uses some extra vue.js packages that don't come standard with Laravel
- [bootstrap-vue](https://bootstrap-vue.js.org/) 
- [progress-bar-4-axios](https://github.com/rikmms/progress-bar-4-axios#readme)
- [vue-infinite-loading](https://github.com/PeachScript/vue-infinite-loading)

There is only the one Vue component - [MainComponent](https://github.com/nclifton/fmapts/blob/master/resources/js/components/MainComponent.vue) 
that interacts with the service routes provided by the web service.

Hopefully the UI is intuitive.

## API Routes
There are four API routes serviced by the web service and the root route:

  Domain | Method   | URI                   | Name     | Action  | Middleware
---------|----------|-----------------------|----------|---------|------------
         | GET|HEAD | /                     |          | Closure | web        
         | GET|HEAD | api/areas             | areas    | Closure | api        
         | GET|HEAD | api/product/{product} | product  | Closure | api        
         | GET|HEAD | api/products          | products | Closure | api        
         | GET|HEAD | api/regions           | regions  | Closure | api        


---



# Full Stack Lead Developer Test
## Purpose
The purpose of this test is to determine your proficiency with PHP
and your skill
as a full-stack developer.
## Task
Build a single page application that uses data provided from the

[ATDW Atlas API](http://developer.atdw.com.au/ATLAS/API/ATDWO-atlas.html) to list

accommodation options in New South Wales. Using the service you
develop the frontend
Application shall enable the user to filter results by regions and
areas as defined
by the ATDW API.
When a product is clicked the user should see the details of the
listing.
This is to be built by creating a PHP service that consumes the data
from the Atlas
API in JSON. The service will then provide the data to the frontend.
The visual result we want is to have a page that uses a frontend
framework
(Vue or React) to render and filter the content.
A minimum of two filters are required:
* Filter by Region
* Filter by Area
### Data source
* Data can be called using the methods defined at

[ATDW Atlas API](http://developer.atdw.com.au/ATLAS/API/ATDWO-atlas.html).

All documentation can be found via this link.
* For development the API key is: `123456789101112`
## Requirements
* Responses from Atlas will be handled in JSON
* Composer and [Packagist](https://packagist.org/) must be used for
external
requests (no raw curl or wget)
* Docker must be used to run the application
* A Readme, Dockerfile and a docker-compose.yml must be included in
the repo.
* PHP 7+ must be used
* Documentation is crucial
* Implementation of infinite scroll
## What we are looking for
* Documentation
* Code style
* Commit history

### Bonus
Additional consideration will be given for:
* Content order is randomised
* Implementation of caching
### Hint
* The ATDW uses UTF-16LE encoding
* Loading all content and filtering in the frontend is not an option
due to the
volume


