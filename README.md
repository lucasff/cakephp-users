CakePHP Users Plugin
=============

Users Plugin, focused on details to easy user management for CakePHP 2.x

## Requirements

* CakePHP 2.x
* Bootstrap 3.x (3.0 support)

## Installation

Ensure require is present in composer.json. This will install the plugin into Plugin/BoostCake:

	{
		"require": {
			"lucasff/cakephp-users": "dev-master"
		}
	}

### Enable plugin

You need to enable the plugin in your app/Config/bootstrap.php file:

`CakePlugin::load('Users');`

If you are already using `CakePlugin::loadAll();`, then this is not necessary.
