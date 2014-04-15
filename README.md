wpgists.org
===========

A space for you to share snippets of code that extend the WordPress projects you love.

## Hacking

Want to contribute to wpgists.org? Great!

Provided you have your local environment configured, here's how you can get set up locally:

1. Clone the repo: `git clone --recursive git@github.com:danielbachhuber/wpgists.org.git wpgists.dev`
1. Create a `/etc/hosts` record to wpgists.dev
1. Inside of wpgists.dev, add a `wp-config-env.php` file with something like this:

    define( 'DB_NAME', 'wpgists' );
    define( 'DB_USER', 'root' );
    define( 'DB_PASSWORD', '' );

    define( 'LOCAL_DEV', true );

    define( 'WP_SITEURL', 'http://wpgists.dev/wp' );
    define( 'WP_HOME', 'http://wpgists.dev' );

1. If you haven't created a database already, run `wp db create`
1. Install WordPress with `wp core install`. Without any configuration parameters, the default admin user:pass is "wpgists:wpgists".
