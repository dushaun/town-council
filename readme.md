# Firmstep Developer Test

This is DuShaun’s development test for Firmstep

### Installation & Setup
This project was developed in the PHP framework Laravel, the best way to run it is through its dedicated dev environment called Valet.

Valet requires macOS and [Homebrew](http://brew.sh/). Before installation, you should make sure that no other programs (such as Apache or Nginx) are binding to your local machine's port 80.

To get Valet up and running, you should do the following:

* Install or update Homebrew to the latest version using ```brew update```.
* Install PHP 7.1 using Homebrew via ```brew install homebrew/php/php71```.
* Install Valet with [Composer](https://getcomposer.org/) via ```composer global require laravel/valet```. Make sure the  ```~/.composer/vendor/bin``` directory is in your system's "PATH".
* Run the ```valet install``` command. This will configure and install Valet and DnsMasq, and register Valet's daemon to launch when your system starts.

Next, [download](https://github.com/dushaunac/town-council) and copy the project into a designated dev folder such as ```~/Sites```. That folder will be the one that Valet "parks" itself to serve the project website. To do this, you will need to do the following:

* In terminal ```cd ~/Sites``` and run ```valet park```. This command will register your current working directory as a path that Valet should search for sites.
* Next, ```cd town-council/``` to run ```npm install```
* To check that Valet is working open ```http://town-council.dev``` in your browser to see if it is serving.
* If Valet is working, continue with the setup steps below. Otherwise, refer to the offical [Valet documentation](https://laravel.com/docs/5.3/valet)

You will now need to setup the database to complete the project setup. If you do not have a preferred database try MariaDB by running ```brew install mariadb``` on your command line. Once MariaDB has been installed, you may start it using the ```brew services start mariadb``` command. You can then connect to the database at ```127.0.0.1``` using the ```root``` username and an empty string for the password.

My preferred database client is [Sequel Pro](https://www.sequelpro.com/) to access and interact with database, but to setup the database for the project in terminal:

* Enter ```mysql -uroot``` to get into MySQL.
* Next, enter ```mysql> CREATE DATABASE town;``` to setup the database.
* Finally, run ```php artisan migrate```. Laravel will then setup the tables on the database for the project.

The project setup is now complete and ready for use.

That should be it. Now you should be able to launch the website to use the features required for this challenge. If you get stuck on any part of this installation process, please have a look at the documentation:

* [Laravel Valet](https://laravel.com/docs/5.3/valet)
* [Homebrew](http://brew.sh/)
* [Composer](https://getcomposer.org/doc/)
* [MySQL](http://dev.mysql.com/doc/mysql-getting-started/en/)

### Explanation

For this project I chose to use [Laravel](https://laravel.com/) for the PHP framework and [VueJS](https://vuejs.org/) for the JavaScript implementation. The reason I used these two technologies is because I use them on a daily basis at my current place of employment, using these technologies have helped advance response times and interfaces with their current application.

Consequently I am very comfortable with these technologies, without taking error fixing time into consideration, I was able to complete this project in 4-6 hours.

### Improvements

I am glad that I finished this project, but if I were to improve the project I would focus on these three points:

##### Realtime Infrastructure
On the project's website, the main page displays a public queue so that customers can see it. The issue here is that the queue component on the admin page updates once a new customer is submitted, but the queue on the main page does not.

What I would do to resolve this would be to implement a realtime infrastructure (such as [Socket.io](http://socket.io/) & [Pusher](https://pusher.com/)) to trigger the relevant events to update the public queue on the main page when a new customer is added. Also, the great thing about Laravel is that it has it's own library called [Echo](https://laravel.com/docs/5.3/broadcasting#installing-laravel-echo) to help implement and manage realtime infrastructure such as the providers I mentioned above.

##### Reference Table on Database
Another thing I would do would be to have reference tables for static data such as the customer type and the associated service. Doing something like this would help tidy up some of the templating inside the VueJS components from this:
```html
<td style="text-align: center">
    <span v-if="customer.type == 0">Citizen</span>
    <span v-if="customer.type == 1">Organisation</span>
    <span v-if="customer.type == 2">Anonymous</span>
</td>
```

To something like this:
```html
<td style="text-align: center">
    {{ customer.type }}
</td>
```

Laravel can help with this by binding data to Models with [Eloquent](https://laravel.com/docs/5.3/eloquent) Relationships. Once the relationship has been stated on the model, it will be very easy to attach the relevant reference data to each queue entry. Here is an example:

Queue.php
```php
public function type()
{
    return $this->hasOne(Type::class, 'type');
}
```

QueueAPIController.php
```php
public function index()
{
    $queue = Queue::with('type')
        ->get()
        ->toArray();

    return response()->json([
        'queue' => $queue
    ], 200);
}
```

public-queue.vue
```html
<td style="text-align: center">
    {{ customer.type.name }}
</td>
```

##### Updating & Deleting/Check Off entries

The last thing I would improve would be to add the ability to update or delete and entry in the queue. This would probably be used to update incorrect data and delete entries from the queue that have been delt with by the council.

Laravel has a ```SoftDeletes``` trait that can be added to Models, so that rather than actually deleting an entry from the queue table, it will timestamp the entry in a ```deleted_at``` column and Laravel knows not to retrieve entries that have a deleted_at timestamp. Here is an example of how it would be implemented:

```php
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Queue extends Model
{
    use SoftDeletes;

    protected $table = 'queue';
}
```